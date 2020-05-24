<?php

namespace App\Entity;

use App\Repository\SpotifyPlaylistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SpotifyPlaylistRepository::class)
 */
class SpotifyPlaylist
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
    private $pid;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageURL;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=SpotifyUser::class, inversedBy="playlists")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\Column(type="boolean")
     */
    private $collaborative;

    /**
     * @ORM\Column(type="boolean")
     */
    private $public;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lastUpdate;

    /**
     * @ORM\OneToOne(targetEntity=SpotifyPlaylistAnalysis::class, mappedBy="playlist", cascade={"persist", "remove"})
     */
    private $analysis;

    /**
     * @ORM\ManyToMany(targetEntity=SpotifyTrack::class, inversedBy="playlists")
     */
    private $tracks;

    public function __construct()
    {
        $this->tracks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPid(): ?string
    {
        return $this->pid;
    }

    public function setPid(string $pid): self
    {
        $this->pid = $pid;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImageURL(): ?string
    {
        return $this->imageURL;
    }

    public function setImageURL(?string $imageURL): self
    {
        $this->imageURL = $imageURL;

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

    public function getOwner(): ?SpotifyUser
    {
        return $this->owner;
    }

    public function setOwner(?SpotifyUser $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getCollaborative(): ?bool
    {
        return $this->collaborative;
    }

    public function setCollaborative(bool $collaborative): self
    {
        $this->collaborative = $collaborative;

        return $this;
    }

    public function getPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): self
    {
        $this->public = $public;

        return $this;
    }

    public function getLastUpdate(): ?\DateTimeInterface
    {
        return $this->lastUpdate;
    }

    public function setLastUpdate(\DateTimeInterface $lastUpdate): self
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    public function getAnalysis(): ?SpotifyPlaylistAnalysis
    {
        return $this->analysis;
    }

    public function setAnalysis(SpotifyPlaylistAnalysis $analysis): self
    {
        $this->analysis = $analysis;

        // set the owning side of the relation if necessary
        if ($analysis->getPlaylist() !== $this) {
            $analysis->setPlaylist($this);
        }

        return $this;
    }

    /**
     * @return Collection|SpotifyTrack[]
     */
    public function getTracks(): Collection
    {
        return $this->tracks;
    }

    public function addTrack(SpotifyTrack $track): self
    {
        if (!$this->tracks->contains($track)) {
            $this->tracks[] = $track;
        }

        return $this;
    }

    public function removeTrack(SpotifyTrack $track): self
    {
        if ($this->tracks->contains($track)) {
            $this->tracks->removeElement($track);
        }

        return $this;
    }
}
