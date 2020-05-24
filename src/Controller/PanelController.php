<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\AutomatedUpdate;
use App\Entity\UserAction;
use App\Model\SpotifyModel;
use App\Model\TwitterModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/panel", name="panel_")
 */
class PanelController extends AbstractController
{
    /**
     * @Route("/setup", name="setup")
     */
    public function setup(TwitterModel $twitterModel, Request $request)
    {
        $user = $this->getUser();

        if($user->getSetupCompleted()) return $this->redirectToRoute("panel_home");

        if($request->isMethod("post") && $request->request->has("interval")) {
            $interval = $request->request->get("interval");

            switch($interval) {
                case "n": case "h1": case "h12": case "d1": case "w1": $interval = $interval; break;
                default: $interval = "h1";
            }
            
            $entityManager = $this->getDoctrine()->getManager();

            $twitterModel->fetchUserData($user,true,$interval);
            
            $user->setSetupCompleted(true);
            $entityManager->persist($user);

            $entityManager->flush();

            return new Response("OK");
        }

        $twitterUser = $twitterModel->verifyCredentials($user);

        return $this->render('panel/setup.html.twig', [
            'twitterUser' => $twitterUser
        ]);
    }

    /**
     * @Route("/home", name="home")
     */
    public function home(TwitterModel $twitterModel, Request $request)
    {
        if(!$this->getUser()->getSetupCompleted()) return $this->redirectToRoute("panel_setup");

        if($request->isMethod("post")) {
            $twitterModel->fetchUserData($this->getUser());

            return new Response("OK");
        }

        $monthlyHistory = $twitterModel->getTotalHistory($this->getUser(),"month",true);
        $dailyHistory = $twitterModel->getTotalHistory($this->getUser(),"day",true);
        $hourlyHistory = $twitterModel->getTotalHistory($this->getUser(),"hour",true);

        $entityManager = $this->getDoctrine()->getManager();

        $recentActivities = $entityManager->getRepository(UserAction::class)
                                ->findActivitesByUser($this->getUser(),10);
        
        $automatedUpdate = $entityManager->getRepository(AutomatedUpdate::class)->findOneBy(["user"=>$this->getUser()->getId()]);
            
        return $this->render('panel/home.html.twig', [
            'monthlyHistory' => $monthlyHistory,
            'dailyHistory' => $dailyHistory,
            'hourlyHistory' => $hourlyHistory,
            'recentActivities' => $recentActivities,
            'canUpdate' => $automatedUpdate->getLastUpdate() < new \DateTime("-59 minutes")
        ]);
    }

    /**
     * @Route("/activities", name="activities")
     */
    public function activities()
    {
        if(!$this->getUser()->getSetupCompleted()) return $this->redirectToRoute("panel_setup");

        $activities = $this->getDoctrine()->getManager()->getRepository(UserAction::class)
                        ->findActivitesByUser($this->getUser(),null);

        return $this->render('panel/activities.html.twig', [
            'activities' => $activities
        ]);
    }

    /**
     * @Route("/settings", name="settings")
     */
    public function settings(TwitterModel $twitterModel, Request $request)
    {
        if(!$this->getUser()->getSetupCompleted()) return $this->redirectToRoute("panel_setup");

        $entityManager = $this->getDoctrine()->getManager();

        $automatedUpdate = $entityManager->getRepository(AutomatedUpdate::class)->findOneBy(["user"=>$this->getUser()->getId()]);
        
        if($request->isMethod("post") && $request->request->has("interval")) {
            $interval = $request->request->get("interval");

            switch($interval) {
                case "n": case "h1": case "h12": case "d1": case "w1": $interval = $interval; break;
                default: $interval = "h1";
            }

            $automatedUpdate->setUpdateInterval($interval);

            $nextUpdate = null;

            switch($interval) {
                case "h1": $nextUpdate = new \DateTime("+1 hours"); break;
                case "h12": $nextUpdate = new \DateTime("+12 hours"); break;
                case "d1": $nextUpdate = new \DateTime("+1 days"); break;
                case "w1": $nextUpdate = new \DateTime("+7 days"); break;
            }

            $automatedUpdate->setNextUpdate($nextUpdate);

            $entityManager->persist($automatedUpdate);

            $entityManager->flush();

            return new Response("OK");
        }

        $currentState = ["Every hour","h1"];

        switch($automatedUpdate->getUpdateInterval()) {
            case "n": $currentState = ["Never (manually)","n"]; break;
            case "h1": $currentState = ["Every hour","h1"]; break;
            case "h12": $currentState = ["Every 12 hours","h12"]; break;
            case "d1": $currentState = ["Every day","d1"]; break;
            case "w1": $currentState = ["Every week","w1"]; break;
        }

        $twitterUser = $twitterModel->verifyCredentials($this->getUser());

        return $this->render('panel/settings.html.twig', [
            'twitterUser' => $twitterUser,
            'currentState' => $currentState,
        ]);
    }

    /**
     * @Route("/spotify/history", name="spotify_history")
     */
    public function spotifyHistory()
    {
        $recentTracks = $this->getDoctrine()->getManager()->createQuery(
            "select h,t,a from App\Entity\SpotifyTrackHistory h
            left join h.spotifyTrack t
            left join t.additional a
             where h.user = :user
             order by h.timestamp desc"
            )->setParameter("user",$this->getUser()->getId())->getArrayResult();

        return $this->render('panel/spotify/history.html.twig', [
            'recentTracks' => $recentTracks
        ]);
    }

    /**
     * @Route("/spotify/playlist/{id}", name="spotify_playlist")
     */
    public function spotifyPlaylist(SpotifyModel $spotifyModel, $id)
    {
        $playlist = $this->getDoctrine()->getManager()->createQuery(
            "select p,t,a from App\Entity\SpotifyPlaylist p
             left join p.tracks t
             left join t.additional a
             where p.pid = :id"
        )->setParameter(":id",$id)->getOneOrNullResult() ?? $spotifyModel->getPlaylist($id,$spotifyModel->getWebAPI($this->getUser()),true,true,true);

        return $this->render('panel/spotify/playlist.html.twig', [
            'playlist' => $playlist
        ]);
    }
}
