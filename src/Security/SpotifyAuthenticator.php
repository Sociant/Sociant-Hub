<?php

namespace App\Security;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Entity\LoginAttempt;
use App\Entity\User;
use App\Model\SpotifyModel;
use App\Model\TwitterModel;
use Doctrine\ORM\EntityManagerInterface;
use SpotifyWebAPI\SpotifyWebAPI;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class SpotifyAuthenticator extends AbstractGuardAuthenticator
{

    private $entityManager;
    private $params;
    private $spotifyModel;

    public function __construct(EntityManagerInterface $entityManager, ParameterBagInterface $params, SpotifyModel $spotifyModel)
    {
        $this->entityManager = $entityManager;
        $this->params = $params;
        $this->spotifyModel = $spotifyModel;
    }

    public function supports(Request $request)
    {
        return 'login_callback_spotify' === $request->attributes->get('_route') &&
            $request->query->has("code");
    }

    public function getCredentials(Request $request)
    {
        return [
            'code' => $request->query->get("code")
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $session = $this->spotifyModel->getSession();
        $session->requestAccessToken($credentials["code"]);
        $accessToken = $session->getAccessToken();

        $api = new SpotifyWebAPI();
        $api->setAccessToken($accessToken);

        $spotifyAccount = $api->me();

        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            "spotifyID" => $spotifyAccount->id
        ]);

        if($user == null) $user = new User();

        $spotifyUser = $this->spotifyModel->createSpotifyUserFromArray($spotifyAccount);

        $user->setSpotifyUser($spotifyUser);
        $user->setSpotifyID($spotifyAccount->id);
        $user->setSpotifyAccessToken($accessToken);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new RedirectResponse("/");
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse("/panel/home");
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse("/");
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
