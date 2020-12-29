<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Abraham\TwitterOAuth\TwitterOAuth;
use App\Entity\LoginAttempt;
use Symfony\Component\HttpFoundation\Request;

class AuthenticationController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request)
    {
        $callbackURL = $this->getParameter("twitter_callback_url");
        $callbackURL .= (strpos($callbackURL,"?") !== false ? "&" : "?") . "return_type=" . $request->query->get("return_type","login");

        $connection = new TwitterOAuth($this->getParameter("twitter_api_key"), $this->getParameter("twitter_api_secret_key"));
        $requestToken = $connection->oauth("oauth/request_token", ["oauth_callback" => $callbackURL]);

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
     * @Route("/login/callback", name="login_callback")
     */
    public function loginCallback(Request $request) {}
    

    /**
     * @Route("/logout", name="logout")
     */
    public function logout() {}
}
