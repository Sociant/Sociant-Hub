<?php

namespace App\Entity;

use App\Repository\UserAnalyticsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserAnalyticsRepository::class)
 */
class UserAnalytics
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="analytics", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @ORM\Column(type="integer")
     */
    private $verifiedFollowers;

    /**
     * @ORM\Column(type="integer")
     */
    private $protectedFollowers;

    /**
     * @ORM\ManyToOne(targetEntity=TwitterUser::class)
     */
    private $mostStatuses;

    /**
     * @ORM\ManyToOne(targetEntity=TwitterUser::class)
     */
    private $mostFollowers;

    /**
     * @ORM\ManyToOne(targetEntity=TwitterUser::class)
     */
    private $oldestAccount;

    /**
     * @ORM\Column(type="integer")
     */
    private $statusCount;

    /**
     * @ORM\Column(type="integer")
     */
    private $favoriteCount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    public function getVerifiedFollowers(): ?int
    {
        return $this->verifiedFollowers;
    }

    public function setVerifiedFollowers(int $verifiedFollowers): self
    {
        $this->verifiedFollowers = $verifiedFollowers;

        return $this;
    }

    public function getProtectedFollowers(): ?int
    {
        return $this->protectedFollowers;
    }

    public function setProtectedFollowers(int $protectedFollowers): self
    {
        $this->protectedFollowers = $protectedFollowers;

        return $this;
    }

    public function getMostStatuses(): ?TwitterUser
    {
        return $this->mostStatuses;
    }

    public function setMostStatuses(?TwitterUser $mostStatuses): self
    {
        $this->mostStatuses = $mostStatuses;

        return $this;
    }

    public function getMostFollowers(): ?TwitterUser
    {
        return $this->mostFollowers;
    }

    public function setMostFollowers(?TwitterUser $mostFollowers): self
    {
        $this->mostFollowers = $mostFollowers;

        return $this;
    }

    public function getOldestAccount(): ?TwitterUser
    {
        return $this->oldestAccount;
    }

    public function setOldestAccount(?TwitterUser $oldestAccount): self
    {
        $this->oldestAccount = $oldestAccount;

        return $this;
    }

    public function getStatusCount(): ?int
    {
        return $this->statusCount;
    }

    public function setStatusCount(int $statusCount): self
    {
        $this->statusCount = $statusCount;

        return $this;
    }

    public function getFavoriteCount(): ?int
    {
        return $this->favoriteCount;
    }

    public function setFavoriteCount(int $favoriteCount): self
    {
        $this->favoriteCount = $favoriteCount;

        return $this;
    }
}
