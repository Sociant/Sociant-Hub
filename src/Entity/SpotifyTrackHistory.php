<?php

namespace App\Entity;

use App\Repository\SpotifyTrackHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SpotifyTrackHistoryRepository::class)
 */
class SpotifyTrackHistory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="spotifyTrackHistory")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=SpotifyTrack::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $spotifyTrack;

    /**
     * @ORM\Column(type="datetime")
     */
    private $timestamp;

    /**
     * @ORM\Column(type="bigint")
     */
    private $next;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getSpotifyTrack(): ?SpotifyTrack
    {
        return $this->spotifyTrack;
    }

    public function setSpotifyTrack(?SpotifyTrack $spotifyTrack): self
    {
        $this->spotifyTrack = $spotifyTrack;

        return $this;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getNext(): ?int
    {
        return $this->next;
    }

    public function setNext(int $next): self
    {
        $this->next = $next;

        return $this;
    }
}
