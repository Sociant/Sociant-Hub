<?php

namespace App\Model;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Entity\AutomatedUpdate;
use App\Entity\SpotifyPlaylist;
use App\Entity\SpotifyPlaylistAnalysis;
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
        if(isset($user->followers)) {
            $spotifyUser->setFollowerCount($user->followers->total);
            $spotifyUser->setFullUser(true);
        }
        if(isset($user->images) && sizeof($user->images) > 0) 
            $spotifyUser->setProfileImageURL($user->images[0]->url);

        $this->entityManager->persist($spotifyUser);
        if ($instantFlush) $this->entityManager->flush();
    
        return $spotifyUser;
    }

    public function updateRecentlyPlayed(User $user, $limit = 10, $storeData = true, $fetchAudioFeatures = true, $fetchAdditionalData = true, SpotifyWebAPI $api = null, $updateInterval = "m15") {
        $api = $api ?? $this->getWebAPI($user);
        $lastUpdate = $user->getAutomatedUpdate() ? $user->getAutomatedUpdate()->getLastUpdate() : null;
        if($lastUpdate == null || $lastUpdate < new \DateTime("-10 minutes")) {
        
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

                    if(sizeof($ids) > 0) {
                        $fullTracks = $api->getTracks($ids);

                        foreach($fullTracks->tracks as $track) {
                            $spotifyTrack = $tracks[$track->id]['item'];
                            $spotifyTrack->setAdditional($this->getAdditionalFromObject($track,$spotifyTrack));
                            $this->entityManager->persist($spotifyTrack);
                        }
                    }
                }
            }

            
            $automatedUpdate = $user->getAutomatedUpdate();

            if($automatedUpdate == null) {
                $automatedUpdate = new AutomatedUpdate();
                $automatedUpdate->setUser($user);
                $automatedUpdate->setUpdateInterval($updateInterval);
            }

            $automatedUpdate->setLastUpdate(new \DateTime());
            
            $nextUpdate = null;

            switch($automatedUpdate->getUpdateInterval()) {
                case "m15": $nextUpdate = new \DateTime("+15 minutes"); break;
                case "m30": $nextUpdate = new \DateTime("+30 minutes"); break;
                case "h1": $nextUpdate = new \DateTime("+1 hours"); break;
                case "d1": $nextUpdate = new \DateTime("+1 days"); break;
                case "w1": $nextUpdate = new \DateTime("+7 days"); break;
            }

            $automatedUpdate->setNextUpdate($nextUpdate);

            $this->entityManager->persist($automatedUpdate);

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

    public function getPlaylist($id, SpotifyWebAPI $api, $store = true, $retrieveTracks = false, $analyzePlaylist = false) {
        $playlist = $api->getPlaylist($id);

        if($store) {
            $spotifyPlaylist = $this->storePlaylist($playlist,null,$retrieveTracks,$api,$analyzePlaylist,true,true);
            return $spotifyPlaylist;
        }

        return $playlist;
    }

    public function storePlaylist(object $playlist, array $tracks = null, $retrieveTracks = false, SpotifyWebAPI $api, $analyzePlaylist = false, $checkDuplicate = true, $instantFlush = true, SpotifyPlaylist $spotifyPlaylist = null): SpotifyPlaylist {
        $spotifyPlaylist = $spotifyPlaylist ?? ($checkDuplicate ? $this->entityManager->getRepository(SpotifyPlaylist::class)->findOneBy(["pid"=>$playlist->id]) : null) ?? new SpotifyPlaylist();
    
        $spotifyPlaylist->setPid($playlist->id);
        $spotifyPlaylist->setDescription($playlist->description);

        $coverImage = null;

        foreach($playlist->images as $image) {
            $height = $image->height ? $image->height : 0;
            if($coverImage == null || $coverImage[0] < $height)
                $coverImage = [$height,$image->url];
        }

        if($coverImage != null)
            $spotifyPlaylist->setImageURL($coverImage[1]);

        $spotifyPlaylist->setName($playlist->name);
        $spotifyPlaylist->setOwner($this->createSpotifyUserFromArray($playlist->owner, false, null, false));
        $spotifyPlaylist->setCollaborative($playlist->collaborative);
        $spotifyPlaylist->setPublic($playlist->public);
        $spotifyPlaylist->setLastUpdate(new \DateTime());

        if($tracks != null) {
            foreach($tracks as $track)
                $spotifyPlaylist->addTrack($track);
        } else if($retrieveTracks) {
            $ids = [];
            $next = null;
            $offset = 0;
            $first = true;

            while($first || $next != null) {
                $first = false;

                $result = $api->getPlaylistTracks($spotifyPlaylist->getPid(),[
                    "fields" => "items(track.id),total,next",
                    "limit" => 100,
                    "offset" => $offset
                ]);

                foreach($result->items as $item) {
                    array_push($ids,$item->track->id);
                }

                $offset+=100;
                $next = $result->next;
            }

            if(sizeof($ids) > 0) {

                $idChunks = array_chunk($ids,100);

                foreach($idChunks as $chunk) {
                    $existingTracks = $this->entityManager->getRepository(SpotifyTrack::class)->findBy([
                        "tid" => $chunk
                    ]);

                    foreach($existingTracks as $track) {
                        if(($key = array_search($track->getTid(),$ids)) !== false)
                            unset($ids[$key]);
                        $spotifyPlaylist->addTrack($track);
                    }
                }

                $retrieveChunks = array_chunk($ids,50);

                foreach($retrieveChunks as $chunk) {
                    $tracks = $api->getTracks($chunk);

                    foreach($tracks->tracks as $track) {
                        $spotifyTrack = $this->storeTrack($track,null,false,false);
                        $spotifyTrack->setAdditional($this->getAdditionalFromObject($track,$spotifyTrack));
                        $spotifyPlaylist->addTrack($spotifyTrack);
                    }
                }

            }

            if($analyzePlaylist) {
                $needAnalization = [];

                foreach($spotifyPlaylist->getTracks() as $track)
                    if(!$track->getAudioFeaturesLoaded()) array_push($needAnalization,$track);
                
                if(sizeof($needAnalization) > 0) {
                    $analizationChunks = array_chunk($needAnalization,100);

                    foreach($analizationChunks as $chunk) {
                        $ids = [];
                        foreach($chunk as $track)
                            array_push($ids,$track->getTid());
                        $analizations = $api->getAudioFeatures($ids);
                        foreach($analizations->audio_features as $analization) {
                            foreach($needAnalization as $track) {
                                if($track->getTid() == $analization->id)
                                    $track = $this->storeTrack(null,$analization,false,false,$track,true);
                            }
                        }
                    }
                }

                $playlistAnalysis = $spotifyPlaylist->getAnalysis() ?? new SpotifyPlaylistAnalysis();
                $total = 0;

                $stats = [
                    "danceability" => 0,
                    "energy" => 0,
                    "keyValue" => 0,
                    "loudness" => 0,
                    "mode" => 0,
                    "speechiness" => 0,
                    "acousticness" => 0,
                    "instrumentalness" => 0,
                    "liveness" => 0,
                    "valence" => 0,
                    "tempo" => 0,
                    "timeSignature" => 0,
                    "duration" => 0
                ];

                foreach($spotifyPlaylist->getTracks() as $track) {
                    if($track->getAudioFeaturesLoaded()) {
                        $total++;

                        $stats["danceability"]      += $track->getDanceability();
                        $stats["energy"]            += $track->getEnergy();
                        $stats["keyValue"]          += $track->getKeyValue();
                        $stats["loudness"]          += $track->getLoudness();
                        $stats["mode"]              += $track->getMode();
                        $stats["speechiness"]       += $track->getSpeechiness();
                        $stats["acousticness"]      += $track->getAcousticness();
                        $stats["instrumentalness"]  += $track->getInstrumentalness();
                        $stats["liveness"]          += $track->getLiveness();
                        $stats["valence"]           += $track->getValence();
                        $stats["tempo"]             += $track->getTempo();
                        $stats["timeSignature"]     += $track->getTimeSignature();
                    }

                    $stats["duration"] += $track->getDuration();
                }

                $playlistAnalysis->setPlaylist($spotifyPlaylist);
                $playlistAnalysis->setDanceability($stats["danceability"] / $total);
                $playlistAnalysis->setEnergy($stats["energy"] / $total);
                $playlistAnalysis->setKeyValue($stats["keyValue"] / $total);
                $playlistAnalysis->setLoudness($stats["loudness"] / $total);
                $playlistAnalysis->setMode($stats["mode"] / $total);
                $playlistAnalysis->setSpeechiness($stats["speechiness"] / $total);
                $playlistAnalysis->setAcousticness($stats["acousticness"] / $total);
                $playlistAnalysis->setInstrumentalness($stats["instrumentalness"] / $total);
                $playlistAnalysis->setLiveness($stats["liveness"] / $total);
                $playlistAnalysis->setValence($stats["valence"] / $total);
                $playlistAnalysis->setTempo($stats["tempo"] / $total);
                $playlistAnalysis->setTimeSignature($stats["timeSignature"] / $total);
                $playlistAnalysis->setTotalDuration($stats["duration"]);

                $spotifyPlaylist->setAnalysis($playlistAnalysis);

                $this->entityManager->persist($playlistAnalysis);
            }
        }

        $this->entityManager->persist($spotifyPlaylist);
        if($instantFlush) $this->entityManager->flush();

        return $spotifyPlaylist;
    }

    public function storeTrack(?object $track, object $audioFeatures = null, $checkDuplicate = true, $instantFlush = true, SpotifyTrack $spotifyTrack = null, $onlyAudioAnalysis = false): SpotifyTrack {
        $spotifyTrack = $spotifyTrack ?? ($checkDuplicate ? $this->entityManager->getRepository(SpotifyTrack::class)->findOneBy(["tid"=>$track->id]) : null) ?? new SpotifyTrack();
        
        if(!$onlyAudioAnalysis) {
            $spotifyTrack->setTid($track->id);
            $spotifyTrack->setName($track->name);
            $spotifyTrack->setPreviewURL($track->preview_url);
            $spotifyTrack->setDuration($track->duration_ms);
        }
        
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
