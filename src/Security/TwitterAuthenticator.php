<?php

namespace App\Security;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Entity\BannedUser;
use App\Entity\LoginAttempt;
use App\Entity\User;
use App\Handler\ApiExportHandler;
use App\Model\TwitterModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class TwitterAuthenticator extends AbstractGuardAuthenticator
{

    private $entityManager;
    private $params;
    private $twitterModel;
    private $apiExportHandler;
    private $container;

    public function __construct(EntityManagerInterface $entityManager, ParameterBagInterface $params, TwitterModel $twitterModel, ApiExportHandler $apiExportHandler, ContainerInterface $container)
    {
        $this->entityManager = $entityManager;
        $this->params = $params;
        $this->twitterModel = $twitterModel;
        $this->apiExportHandler = $apiExportHandler;
        $this->container = $container;
    }

    public function supports(Request $request)
    {
        return 'login_callback' === $request->attributes->get('_route') &&
            $request->query->has("oauth_token") &&
            $request->query->has("oauth_verifier");
    }

    public function getCredentials(Request $request)
    {
        return [
            'oauthToken' => $request->query->get("oauth_token"),
            'oauthVerifier' => $request->query->get("oauth_verifier")
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $loginAttempt = $this->entityManager->getRepository(LoginAttempt::class)->findOneBy([
            "oauthToken" => $credentials["oauthToken"]
        ]);

        if($loginAttempt) {
            $connection = new TwitterOAuth(
                $this->params->get("twitter_api_key"),
                $this->params->get("twitter_api_secret_key"),
                $loginAttempt->getOauthToken(),
                $loginAttempt->getOauthTokenSecret()
            );

            $accessToken = $connection->oauth("oauth/access_token", [
                "oauth_verifier" => $credentials["oauthVerifier"]
            ]);

            $userId = $accessToken["user_id"];

            $user = $this->entityManager->getRepository(User::class)->findOneBy([
                "uuid" => $userId
            ]);

            $this->entityManager->remove($loginAttempt);

            $bannedUser = $this->entityManager->getRepository(BannedUser::class)->findBy([
                'twitterUserId' => $userId
            ]);

            if(sizeof($bannedUser) > 0)
                throw new CustomUserMessageAuthenticationException('banned');

            if($user == null) $user = new User();
            $user->setUuid($userId);
            $user->setTwitterUserScreenName($accessToken["screen_name"]);
            $user->setTwitterUserOauthToken($accessToken["oauth_token"]);
            $user->setTwitterUserOauthTokenSecret($accessToken["oauth_token_secret"]);

            $this->twitterModel->verifyCredentials($user);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $user;
        } else
            throw new CustomUserMessageAuthenticationException("token");
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if($request->query->get("return_type","login") == "api_token") {
            return new JsonResponse([
                "error" => $exception->getMessage()
            ]);
        }

        return new RedirectResponse("/?login_error=" . urlencode($exception->getMessage()));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        /** @var User $user */
        $user = $token->getUser();
        $apiToken = $user->getApiToken();

        if(is_null($apiToken)) {
            $apiToken = $this->generateUUID();
            $user->setApiToken($apiToken);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        if($request->query->get("return_type","login") == "api_token")
            return new RedirectResponse($this->container->getParameter("mobile_platform_schema") . "?token=" . $apiToken);

        return new RedirectResponse("/profile");
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse("/");
    }

    public function supportsRememberMe()
    {
        return false;
    }

    private function generateUUID() {
        $uuid = sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0x0fff ) | 0x4000,
            mt_rand( 0, 0x3fff ) | 0x8000,
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );

        $check = $this->entityManager->getRepository(User::class)->findOneBy(["apiToken" => $uuid]);
        return $check != null ? $this->generateUUID() : $uuid;
    }
}
