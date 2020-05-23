<?php

namespace App\Entity;

use App\Repository\UserStatisticRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserStatisticRepository::class)
 */
class UserStatistic
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="statistics")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $followerCount;

    /**
     * @ORM\Column(type="integer")
     */
    private $followingCount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
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

    public function getFollowerCount(): ?int
    {
        return $this->followerCount;
    }

    public function setFollowerCount(int $followerCount): self
    {
        $this->followerCount = $followerCount;

        return $this;
    }

    public function getFollowingCount(): ?int
    {
        return $this->followingCount;
    }

    public function setFollowingCount(int $followingCount): self
    {
        $this->followingCount = $followingCount;

        return $this;
    }
}
