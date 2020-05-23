<?php

namespace App\Entity;

use App\Repository\LoginAttemptRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LoginAttemptRepository::class)
 */
class LoginAttempt
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
    private $created;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $oauthToken;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $oauthTokenSecret;

    /**
     * @ORM\Column(type="boolean")
     */
    private $oauthCallbackConfirmed;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOauthToken(): ?string
    {
        return $this->oauthToken;
    }

    public function setOauthToken(string $oauthToken): self
    {
        $this->oauthToken = $oauthToken;

        return $this;
    }

    public function getOauthTokenSecret(): ?string
    {
        return $this->oauthTokenSecret;
    }

    public function setOauthTokenSecret(string $oauthTokenSecret): self
    {
        $this->oauthTokenSecret = $oauthTokenSecret;

        return $this;
    }

    public function getOauthCallbackConfirmed(): ?bool
    {
        return $this->oauthCallbackConfirmed;
    }

    public function setOauthCallbackConfirmed(bool $oauthCallbackConfirmed): self
    {
        $this->oauthCallbackConfirmed = $oauthCallbackConfirmed;

        return $this;
    }
}
