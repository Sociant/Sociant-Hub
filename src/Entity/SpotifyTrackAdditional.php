<?php

namespace App\Entity;

use App\Repository\SpotifyTrackAdditionalRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SpotifyTrackAdditionalRepository::class)
 */
class SpotifyTrackAdditional
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=SpotifyTrack::class, inversedBy="additional", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $spotifyTrack;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $albumID;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $albumName;

    /**
     * @ORM\Column(type="object")
     */
    private $artists;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $albumImageURL;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpotifyTrack(): ?SpotifyTrack
    {
        return $this->spotifyTrack;
    }

    public function setSpotifyTrack(SpotifyTrack $spotifyTrack): self
    {
        $this->spotifyTrack = $spotifyTrack;

        return $this;
    }

    public function getAlbumID(): ?string
    {
        return $this->albumID;
    }

    public function setAlbumID(string $albumID): self
    {
        $this->albumID = $albumID;

        return $this;
    }

    public function getAlbumName(): ?string
    {
        return $this->albumName;
    }

    public function setAlbumName(string $albumName): self
    {
        $this->albumName = $albumName;

        return $this;
    }

    public function getArtists()
    {
        return $this->artists;
    }

    public function setArtists($artists): self
    {
        $this->artists = $artists;

        return $this;
    }

    public function getAlbumImageURL(): ?string
    {
        return $this->albumImageURL;
    }

    public function setAlbumImageURL(string $albumImageURL): self
    {
        $this->albumImageURL = $albumImageURL;

        return $this;
    }
}
