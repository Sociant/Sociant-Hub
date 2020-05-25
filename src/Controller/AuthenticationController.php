<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Abraham\TwitterOAuth\TwitterOAuth;
use App\Entity\LoginAttempt;
use App\Model\SpotifyModel;
use Symfony\Component\HttpFoundation\Request;

class AuthenticationController extends AbstractController
{
    /**
     * @Route("/login/twitter", name="login_twitter")
     */
    public function twitterLogin()
    {
        $connection = new TwitterOAuth($this->getParameter("twitter_api_key"), $this->getParameter("twitter_api_secret_key"));
        $requestToken = $connection->oauth("oauth/request_token", ["oauth_callback" => $this->getParameter("twitter_callback_url")]);

        $loginAttempt = new LoginAttempt();
        $loginAttempt->setCreated(new \DateTime());
        $loginAttempt->setOauthToken($requestToken["oauth_token"]);
        $loginAttempt->setOauthTokenSecret($requestToken["oauth_token_secret"]);
        $loginAttempt->setOauthCallbackConfirmed($requestToken["oauth_callback_confirmed"] == "true");

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($loginAttempt);
        $entityManager->flush();

        return $this->redirect($connection->url("oauth/authorize", ["oauth_token" => $requestToken["oauth_token"]]));
    }

    /**
     * @Route("/login/spotify", name="login_spotify")
     */
    public function spotifyLogin(SpotifyModel $spotifyModel)
    {
        $options = [
            'scope' => [
                'user-read-playback-state',
                'user-read-currently-playing',
                'playlist-read-collaborative',
                'playlist-read-private',
                'user-library-read',
                'user-top-read',
                'user-read-playback-position',
                'user-read-recently-played',
                'user-follow-read',
                'streaming',
                'playlist-modify-private'
            ]
        ];

        return $this->redirect($spotifyModel->getSession()->getAuthorizeUrl($options));
    }

    /**
     * @Route("/login/callback/twitter", name="login_callback_twitter")
     */
    public function loginCallbackTwitter(Request $request) {}

    /**
     * @Route("/login/callback/spotify", name="login_callback_spotify")
     */
    public function loginCallbackSpotify(Request $request) {}
    

    /**
     * @Route("/logout", name="logout")
     */
    public function logout() {}
}
