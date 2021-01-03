<?php

namespace App\Entity;

use App\Repository\ApiNotificationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApiNotificationRepository::class)
 */
class ApiNotification
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
    private $deviceToken;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="apiNotifications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="object")
     */
    private $notificationPreferences = [
        "unfollow_self" => false,
        "unfollow_other" => true,
        "follow_self" => false,
        "follow_other" => true,
        "scheduled_update" => false
    ];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $deviceUniqueId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeviceToken(): ?string
    {
        return $this->deviceToken;
    }

    public function setDeviceToken(string $deviceToken): self
    {
        $this->deviceToken = $deviceToken;

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

    public function getNotificationPreferences()
    {
        return $this->notificationPreferences;
    }

    public function setNotificationPreferences($notificationPreferences): self
    {
        $this->notificationPreferences = $notificationPreferences;

        return $this;
    }

    public function getDeviceUniqueId(): ?string
    {
        return $this->deviceUniqueId;
    }

    public function setDeviceUniqueId(string $deviceUniqueId): self
    {
        $this->deviceUniqueId = $deviceUniqueId;

        return $this;
    }
}
