<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Abraham\TwitterOAuth\TwitterOAuth;
use App\Entity\AutomatedUpdate;
use App\Entity\LoginAttempt;
use App\Entity\User;
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
            'twitterUser' => $twitterUser,
            'darkmode' => $request->cookies->has("darkmode") ? $request->cookies->get("darkmode") : false
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

        $recentActivities = $entityManager->createQuery(
            "select a,tu from App\Entity\UserAction a
            left join a.twitterUser tu
            where a.user = :user
            order by a.id desc"
            )->setParameter(":user",$this->getUser()->getId())
            ->setMaxResults(10)
            ->getResult();

        $automatedUpdate = $entityManager->getRepository(AutomatedUpdate::class)->findOneBy(["user"=>$this->getUser()->getId()]);
            
        return $this->render('panel/home.html.twig', [
            'monthlyHistory' => $monthlyHistory,
            'dailyHistory' => $dailyHistory,
            'hourlyHistory' => $hourlyHistory,
            'recentActivities' => $recentActivities,
            'darkmode' => $request->cookies->has("darkmode") ? $request->cookies->get("darkmode") : false,
            'canUpdate' => $automatedUpdate->getLastUpdate() < new \DateTime("-59 minutes")
        ]);
    }

    /**
     * @Route("/activities", name="activities")
     */
    public function activities(Request $request)
    {
        if(!$this->getUser()->getSetupCompleted()) return $this->redirectToRoute("panel_setup");

        $activities = $this->getDoctrine()->getManager()->createQuery(
            "select a,tu from App\Entity\UserAction a
            left join a.twitterUser tu
            where a.user = :user
            order by a.id desc"
            )->setParameter(":user",$this->getUser()->getId())
            ->getResult();

        return $this->render('panel/activities.html.twig', [
            'activities' => $activities,
            'darkmode' => $request->cookies->has("darkmode") ? $request->cookies->get("darkmode") : false
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
            'darkmode' => $request->cookies->has("darkmode") ? $request->cookies->get("darkmode") : false
        ]);
    }
}
