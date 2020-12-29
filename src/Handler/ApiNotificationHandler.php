<?php


namespace App\Handler;


use App\Entity\ApiNotification;
use Doctrine\ORM\EntityManagerInterface;
use Kreait\Firebase\Messaging;

class ApiNotificationHandler
{

    private $entityManager;
    private $messaging;

    public function __construct(EntityManagerInterface $entityManager, Messaging $messaging)
    {
        $this->entityManager = $entityManager;
        $this->messaging = $messaging;
    }

    public function sendNotification(ApiNotification $apiNotification, string $type, array $notification, array $data) {
        $preferences = $apiNotification->getNotificationPreferences();
        $canSend = $type == "testing" ? true : ($canSend = $preferences[$type] ?? false);
        if(!$canSend) return;

        $message = Messaging\CloudMessage::fromArray([
            "token" => $apiNotification->getDeviceToken(),
            "notification" => $notification,
            "data" => $data
        ]);

        $this->messaging->send($message);
    }

}