<?php

namespace App\Model;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Entity\AutomatedUpdate;
use App\Entity\SpotifyUser;
use App\Entity\User;
use App\Entity\UserAction;
use App\Entity\UserAnalytics;
use App\Entity\UserRelation;
use App\Entity\UserStatistic;
use Doctrine\ORM\EntityManagerInterface;
use SpotifyWebAPI\Session;

class SpotifyModel
{

    private $entityManager;
    private $clientID;
    private $clientSecret;
    private $callbackURL;

    public function __construct(EntityManagerInterface $entityManager, $clientID, $clientSecret, $callbackURL)
    {
        $this->entityManager = $entityManager;
        $this->clientID = $clientID;
        $this->clientSecret = $clientSecret;
        $this->callbackURL = $callbackURL;
    }

    public function getSession(): Session
    {
        return new Session(
            $this->clientID,
            $this->clientSecret,
            $this->callbackURL
        );
    }

    public function createSpotifyUserFromArray($user, $skipDatabase = false, ?SpotifyUser $spotifyUser = null, $instantFlush = true): SpotifyUser 
    {

        if ($skipDatabase) $spotifyUser = $spotifyUser ?? new SpotifyUser();
        else $spotifyUser = $this->entityManager->getRepository(SpotifyUser::class)->findOneBy(["sid" => $user->id]) ?? new SpotifyUser();

        $spotifyUser->setSid($user->id);
        $spotifyUser->setCreated(new \DateTime());
        $spotifyUser->setDisplayName($user->display_name);
        $spotifyUser->setFollowerCount($user->followers->total);
        if(sizeof($user->images) > 0) 
            $spotifyUser->setProfileImageURL($user->images[0]->url);

        $this->entityManager->persist($spotifyUser);
        if ($instantFlush) $this->entityManager->flush();
    
        return $spotifyUser;
    }
}
