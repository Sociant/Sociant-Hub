<?php

namespace App\Entity;

use App\Repository\SpotifyTrackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SpotifyTrackRepository::class)
 */
class SpotifyTrack
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $previewURL;

    /**
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @ORM\Column(type="boolean")
     */
    private $audioFeaturesLoaded = false;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $danceability;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $energy;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $keyValue;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $loudness;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $mode;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $speechiness;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $acousticness;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $instrumentalness;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $liveness;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $valence;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $tempo;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $timeSignature;

    /**
     * @ORM\OneToOne(targetEntity=SpotifyTrackAdditional::class, mappedBy="spotifyTrack", cascade={"persist", "remove"})
     */
    private $additional;

    /**
     * @ORM\ManyToMany(targetEntity=SpotifyPlaylist::class, mappedBy="tracks")
     */
    private $playlists;

    public function __construct()
    {
        $this->playlists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTid(): ?string
    {
        return $this->tid;
    }

    public function setTid(string $tid): self
    {
        $this->tid = $tid;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPreviewURL(): ?string
    {
        return $this->previewURL;
    }

    public function setPreviewURL(?string $previewURL): self
    {
        $this->previewURL = $previewURL;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getAudioFeaturesLoaded(): ?bool
    {
        return $this->audioFeaturesLoaded;
    }

    public function setAudioFeaturesLoaded(bool $audioFeaturesLoaded): self
    {
        $this->audioFeaturesLoaded = $audioFeaturesLoaded;

        return $this;
    }

    public function getDanceability(): ?float
    {
        return $this->danceability;
    }

    public function setDanceability(?float $danceability): self
    {
        $this->danceability = $danceability;

        return $this;
    }

    public function getEnergy(): ?float
    {
        return $this->energy;
    }

    public function setEnergy(?float $energy): self
    {
        $this->energy = $energy;

        return $this;
    }

    public function getKeyValue(): ?int
    {
        return $this->keyValue;
    }

    public function setKeyValue(?int $keyValue): self
    {
        $this->keyValue = $keyValue;

        return $this;
    }

    public function getLoudness(): ?float
    {
        return $this->loudness;
    }

    public function setLoudness(?float $loudness): self
    {
        $this->loudness = $loudness;

        return $this;
    }

    public function getMode(): ?int
    {
        return $this->mode;
    }

    public function setMode(?int $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    public function getSpeechiness(): ?float
    {
        return $this->speechiness;
    }

    public function setSpeechiness(?float $speechiness): self
    {
        $this->speechiness = $speechiness;

        return $this;
    }

    public function getAcousticness(): ?float
    {
        return $this->acousticness;
    }

    public function setAcousticness(?float $acousticness): self
    {
        $this->acousticness = $acousticness;

        return $this;
    }

    public function getInstrumentalness(): ?float
    {
        return $this->instrumentalness;
    }

    public function setInstrumentalness(?float $instrumentalness): self
    {
        $this->instrumentalness = $instrumentalness;

        return $this;
    }

    public function getLiveness(): ?float
    {
        return $this->liveness;
    }

    public function setLiveness(?float $liveness): self
    {
        $this->liveness = $liveness;

        return $this;
    }

    public function getValence(): ?float
    {
        return $this->valence;
    }

    public function setValence(?float $valence): self
    {
        $this->valence = $valence;

        return $this;
    }

    public function getTempo(): ?float
    {
        return $this->tempo;
    }

    public function setTempo(?float $tempo): self
    {
        $this->tempo = $tempo;

        return $this;
    }

    public function getTimeSignature(): ?int
    {
        return $this->timeSignature;
    }

    public function setTimeSignature(?int $timeSignature): self
    {
        $this->timeSignature = $timeSignature;

        return $this;
    }

    public function getAdditional(): ?SpotifyTrackAdditional
    {
        return $this->additional;
    }

    public function setAdditional(SpotifyTrackAdditional $additional): self
    {
        $this->additional = $additional;

        // set the owning side of the relation if necessary
        if ($additional->getSpotifyTrack() !== $this) {
            $additional->setSpotifyTrack($this);
        }

        return $this;
    }

    /**
     * @return Collection|SpotifyPlaylist[]
     */
    public function getPlaylists(): Collection
    {
        return $this->playlists;
    }

    public function addPlaylist(SpotifyPlaylist $playlist): self
    {
        if (!$this->playlists->contains($playlist)) {
            $this->playlists[] = $playlist;
            $playlist->addTrack($this);
        }

        return $this;
    }

    public function removePlaylist(SpotifyPlaylist $playlist): self
    {
        if ($this->playlists->contains($playlist)) {
            $this->playlists->removeElement($playlist);
            $playlist->removeTrack($this);
        }

        return $this;
    }
}
