<?php

namespace App\Entity;

use App\Repository\SpotifyPlaylistAnalysisRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SpotifyPlaylistAnalysisRepository::class)
 */
class SpotifyPlaylistAnalysis
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=SpotifyPlaylist::class, inversedBy="analysis", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $playlist;

    /**
     * @ORM\Column(type="float")
     */
    private $danceability;

    /**
     * @ORM\Column(type="float")
     */
    private $energy;

    /**
     * @ORM\Column(type="integer")
     */
    private $keyValue;

    /**
     * @ORM\Column(type="float")
     */
    private $loudness;

    /**
     * @ORM\Column(type="integer")
     */
    private $mode;

    /**
     * @ORM\Column(type="float")
     */
    private $speechiness;

    /**
     * @ORM\Column(type="float")
     */
    private $acousticness;

    /**
     * @ORM\Column(type="float")
     */
    private $instrumentalness;

    /**
     * @ORM\Column(type="float")
     */
    private $liveness;

    /**
     * @ORM\Column(type="float")
     */
    private $valence;

    /**
     * @ORM\Column(type="float")
     */
    private $tempo;

    /**
     * @ORM\Column(type="integer")
     */
    private $timeSignature;

    /**
     * @ORM\Column(type="bigint")
     */
    private $totalDuration;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlaylist(): ?SpotifyPlaylist
    {
        return $this->playlist;
    }

    public function setPlaylist(SpotifyPlaylist $playlist): self
    {
        $this->playlist = $playlist;

        return $this;
    }

    public function getDanceability(): ?float
    {
        return $this->danceability;
    }

    public function setDanceability(float $danceability): self
    {
        $this->danceability = $danceability;

        return $this;
    }

    public function getEnergy(): ?float
    {
        return $this->energy;
    }

    public function setEnergy(float $energy): self
    {
        $this->energy = $energy;

        return $this;
    }

    public function getKeyValue(): ?int
    {
        return $this->keyValue;
    }

    public function setKeyValue(int $keyValue): self
    {
        $this->keyValue = $keyValue;

        return $this;
    }

    public function getLoudness(): ?float
    {
        return $this->loudness;
    }

    public function setLoudness(float $loudness): self
    {
        $this->loudness = $loudness;

        return $this;
    }

    public function getMode(): ?int
    {
        return $this->mode;
    }

    public function setMode(int $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    public function getSpeechiness(): ?float
    {
        return $this->speechiness;
    }

    public function setSpeechiness(float $speechiness): self
    {
        $this->speechiness = $speechiness;

        return $this;
    }

    public function getAcousticness(): ?float
    {
        return $this->acousticness;
    }

    public function setAcousticness(float $acousticness): self
    {
        $this->acousticness = $acousticness;

        return $this;
    }

    public function getInstrumentalness(): ?float
    {
        return $this->instrumentalness;
    }

    public function setInstrumentalness(float $instrumentalness): self
    {
        $this->instrumentalness = $instrumentalness;

        return $this;
    }

    public function getLiveness(): ?float
    {
        return $this->liveness;
    }

    public function setLiveness(float $liveness): self
    {
        $this->liveness = $liveness;

        return $this;
    }

    public function getValence(): ?float
    {
        return $this->valence;
    }

    public function setValence(float $valence): self
    {
        $this->valence = $valence;

        return $this;
    }

    public function getTempo(): ?float
    {
        return $this->tempo;
    }

    public function setTempo(float $tempo): self
    {
        $this->tempo = $tempo;

        return $this;
    }

    public function getTimeSignature(): ?int
    {
        return $this->timeSignature;
    }

    public function setTimeSignature(int $timeSignature): self
    {
        $this->timeSignature = $timeSignature;

        return $this;
    }

    public function getTotalDuration(): ?string
    {
        return $this->totalDuration;
    }

    public function setTotalDuration(string $totalDuration): self
    {
        $this->totalDuration = $totalDuration;

        return $this;
    }
}
