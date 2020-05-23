<?php

namespace App\Entity;

use App\Repository\AutomatedUpdateRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AutomatedUpdateRepository::class)
 */
class AutomatedUpdate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="automatedUpdate", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $updateInterval;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $nextUpdate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastUpdate;

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

    public function getUpdateInterval(): ?string
    {
        return $this->updateInterval;
    }

    public function setUpdateInterval(string $updateInterval): self
    {
        $this->updateInterval = $updateInterval;

        return $this;
    }

    public function getNextUpdate(): ?\DateTimeInterface
    {
        return $this->nextUpdate;
    }

    public function setNextUpdate(?\DateTimeInterface $nextUpdate): self
    {
        $this->nextUpdate = $nextUpdate;

        return $this;
    }

    public function getLastUpdate(): ?\DateTimeInterface
    {
        return $this->lastUpdate;
    }

    public function setLastUpdate(?\DateTimeInterface $lastUpdate): self
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }
}
