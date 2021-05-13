<?php

namespace App\Entity;

use App\Repository\BannedUserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BannedUserRepository::class)
 */
class BannedUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $twitterUserId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTwitterUserId(): ?string
    {
        return $this->twitterUserId;
    }

    public function setTwitterUserId(string $twitterUserId): self
    {
        $this->twitterUserId = $twitterUserId;

        return $this;
    }
}
