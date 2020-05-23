<?php

namespace App\Model;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Entity\AutomatedUpdate;
use App\Entity\SpotifyTrack;
use App\Entity\SpotifyTrackAdditional;
use App\Entity\SpotifyTrackHistory;
use App\Entity\SpotifyUser;
use App\Entity\User;
use App\Entity\UserAction;
use App\Entity\UserAnalytics;
use App\Entity\UserRelation;
use App\Entity\UserStatistic;
use Doctrine\ORM\EntityManagerInterface;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;

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

    public function getWebAPI(User $user, $verifyCredentials = true): SpotifyWebAPI
    {
        $session = $this->getSession();
        $session->setAccessToken($user->getSpotifyAccessToken());
        $session->setRefreshToken($user->getSpotifyRefreshToken());

        $api = new SpotifyWebAPI([
            'auto_refresh' => true
        ],$session);

        $api->setAccessToken($user->getSpotifyAccessToken());
        
        if($verifyCredentials) {
            $this->verifyCredentials($api);

            if($session->getAccessToken() != $user->getSpotifyAccessToken() ||
                $session->getRefreshToken() != $user->getSpotifyRefreshToken()) {
                    $user->setSpotifyAccessToken($session->getAccessToken());
                    $user->setSpotifyRefreshToken($session->getRefreshToken());

                    $this->entityManager->persist($user);
                    $this->entityManager->flush();
                }
        }

        return $api;
    }

    public function verifyCredentials(SpotifyWebAPI $api): SpotifyUser {
        return $this->createSpotifyUserFromArray($api->me());
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

    public function updateRecentlyPlayed(User $user, $limit = 10, $storeData = true, $fetchAudioFeatures = true, $fetchAdditionalData = true, SpotifyWebAPI $api = null) {
        $api = $api ?? $this->getWebAPI($user);

        $lastTrack = $this->entityManager->createQuery(
            "select h from App\Entity\SpotifyTrackHistory h
             where h.user = :user
             order by h.next desc"
        )->setParameter("user",$user->getId())->setMaxResults(1)->getOneOrNullResult();

        $recentTracks = $api->getMyRecentTracks([
            "limit" => $limit > 50 ? 50 : $limit,
            "after" => $lastTrack ? $lastTrack->getNext() : null
        ]);

        $next = $recentTracks->cursors ? $recentTracks->cursors->after : time()*1000;

        $items = $recentTracks->items;

        if($storeData) {
            $tracks = [];
            $ids = [];

            foreach($items as $track) {
                array_push($ids,$track->track->id);
                $tracks[$track->track->id] = [
                    'item' => null,
                    'track' => $track->track,
                    'played_at' => $track->played_at,
                    'audioFeaturesGiven' => false,
                    'audioFeatures' => null
                ];
            }

            $existingTracks = $this->entityManager->getRepository(SpotifyTrack::class)->findBy([
                "tid" => $ids
            ]);

            foreach($existingTracks as $track) {
                $tracks[$track->getTid()]["item"] = $track;
                $tracks[$track->getTid()]["audioFeaturesGiven"] = $track->getAudioFeaturesLoaded();
            }

            if($fetchAudioFeatures) {
                $ids = [];

                foreach($tracks as $tid=>$track)
                    if(!$track["audioFeaturesGiven"])
                        array_push($ids,$tid);

                $audioFeatures = $api->getAudioFeatures($ids);

                foreach($audioFeatures->audio_features as $item) {
                    if($item != null) $tracks[$item->id]["audioFeatures"] = $item;
                }
            }

            foreach($tracks as $id=>$track) {
                $spotifyTrack = $this->storeTrack($track['track'],$track['audioFeatures'],false,false,$track['item']);
                $tracks[$id]['item'] = $spotifyTrack;
                
                $spotifyTrackHistory = new SpotifyTrackHistory();
                $spotifyTrackHistory->setUser($user);
                $spotifyTrackHistory->setSpotifyTrack($spotifyTrack);
                $spotifyTrackHistory->setTimestamp(new \DateTime("@".strtotime($track['played_at'])));
                $spotifyTrackHistory->setNext($next);

                $this->entityManager->persist($spotifyTrackHistory);
            }

            if($fetchAdditionalData) {
                $ids = [];

                foreach($tracks as $track) {
                    if($track['item']->getAdditional() == null)
                        array_push($ids,$track['item']->getTid());
                }

                $fullTracks = $api->getTracks($ids);

                foreach($fullTracks->tracks as $track) {
                    $spotifyTrack = $tracks[$track->id]['item'];
                    $spotifyTrack->setAdditional($this->getAdditionalFromObject($track,$spotifyTrack));
                    $this->entityManager->persist($spotifyTrack);
                }
            }

            $this->entityManager->flush();
        }
    }

    public function getAdditionalFromObject(object $data): SpotifyTrackAdditional {
        $spotifyTrackAdditional = new SpotifyTrackAdditional();

        $spotifyTrackAdditional->setAlbumID($data->album->id);
        $spotifyTrackAdditional->setAlbumName($data->album->name);
        
        $artists = [];

        foreach($data->artists as $artist) {
            array_push($artists,[
                "id" => $artist->id,
                "name" => $artist->name,
                "url" => $artist->href
            ]);
        }

        $spotifyTrackAdditional->setArtists($artists);

        $coverImage = null;
        foreach($data->album->images as $image) {
            if($coverImage == null || $coverImage[0] < $image->height)
                $coverImage = [$image->height,$image->url];
        }

        $spotifyTrackAdditional->setAlbumImageURL($coverImage[1]);

        return $spotifyTrackAdditional;
    }

    public function storeTrack(object $track, object $audioFeatures = null, $checkDuplicate = true, $instantFlush = true, SpotifyTrack $spotifyTrack = null): SpotifyTrack {
        $spotifyTrack = $spotifyTrack ?? ($checkDuplicate ? $this->entityManager->getRepository(SpotifyTrack::class)->findOneBy(["tid"=>$track["id"]]) : null) ?? new SpotifyTrack();
        
        $spotifyTrack->setTid($track->id);
        $spotifyTrack->setName($track->name);
        $spotifyTrack->setPreviewURL($track->preview_url);
        $spotifyTrack->setDuration($track->duration_ms);
        
        if($audioFeatures != null) {
            $spotifyTrack->setAudioFeaturesLoaded(true);
            $spotifyTrack->setDanceability($audioFeatures->danceability);
            $spotifyTrack->setEnergy($audioFeatures->energy);
            $spotifyTrack->setKeyValue($audioFeatures->key);
            $spotifyTrack->setLoudness($audioFeatures->loudness);
            $spotifyTrack->setMode($audioFeatures->mode);
            $spotifyTrack->setSpeechiness($audioFeatures->speechiness);
            $spotifyTrack->setAcousticness($audioFeatures->acousticness);
            $spotifyTrack->setInstrumentalness($audioFeatures->instrumentalness);
            $spotifyTrack->setLiveness($audioFeatures->liveness);
            $spotifyTrack->setValence($audioFeatures->valence);
            $spotifyTrack->setTempo($audioFeatures->tempo);
            $spotifyTrack->setTimeSignature($audioFeatures->time_signature);
        }

        $this->entityManager->persist($spotifyTrack);
        if($instantFlush) $this->entityManager->flush();

        return $spotifyTrack;
    }
}
