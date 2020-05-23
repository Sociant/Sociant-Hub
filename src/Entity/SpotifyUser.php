<?php

namespace App\Entity;

use App\Repository\SpotifyUserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SpotifyUserRepository::class)
 */
class SpotifyUser
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
    private $sid;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $displayName;

    /**
     * @ORM\Column(type="integer")
     */
    private $followerCount;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $profileImageURL;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSid(): ?string
    {
        return $this->sid;
    }

    public function setSid(string $sid): self
    {
        $this->sid = $sid;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): self
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function getFollowerCount(): ?int
    {
        return $this->followerCount;
    }

    public function setFollowerCount(int $followerCount): self
    {
        $this->followerCount = $followerCount;

        return $this;
    }

    public function getProfileImageURL(): ?string
    {
        return $this->profileImageURL;
    }

    public function setProfileImageURL(?string $profileImageURL): self
    {
        $this->profileImageURL = $profileImageURL;

        return $this;
    }
}
