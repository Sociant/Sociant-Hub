<?php

namespace App\Controller;

use App\Entity\ApiNotification;
use App\Entity\User;
use App\Handler\ApiExportHandler;
use App\Handler\ApiNotificationHandler;
use Kreait\Firebase\Messaging;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
class ApiController extends AbstractController
{

    /**
     * @Route("/info", name="info")
     */
    public function info(ApiExportHandler $apiExportHandler)
    {
        $twitterUser = $this->getUser()->getTwitterUser();

        return $this->json([
            'twitter_user' => $twitterUser ? $apiExportHandler->exportTwitterUser($twitterUser) : null
        ]);
    }

    /**
     * @Route("/notification-settings", name="get_notification_settings")
     */
    public function getNotificationSettings(Request $request, ApiExportHandler $apiExportHandler)
    {
        $deviceToken = $request->query->get("device_token");
        $deviceUniqueId = $request->query->get("device_unique_id");

        if($deviceToken == null)
            return $this->json(["error"=>"no device token provided"],500);

        if($deviceUniqueId == null)
            return $this->json(["error"=>"no device unique id provided"],500);

        $user = $this->getUser();
        $apiNotification = $this->getApiNotification($deviceToken, $deviceUniqueId, $user);

        return $this->json($apiExportHandler->exportApiNotification($apiNotification));
    }

    /**
     * @Route("/notification-settings/test", name="test_notification_settings")
     */
    public function testNotificationSettings(Request $request, ApiNotificationHandler $apiNotificationHandler)
    {
        $deviceToken = $request->query->get("device_token");
        $deviceUniqueId = $request->query->get("device_unique_id");

        if($deviceToken == null)
            return $this->json(["error"=>"no device token provided"],500);

        if($deviceUniqueId == null)
            return $this->json(["error"=>"no device unique id provided"],500);

        $user = $this->getUser();
        $apiNotification = $this->getApiNotification($deviceToken, $deviceUniqueId, $user);

        $apiNotificationHandler->sendNotification(
            $apiNotification,
            "testing",
            ["title"=>"Sociant Hub Test Notification","body"=>"This is a test notification required by the user"],
            []
        );

        return $this->json([
            "result" => "request sent"
        ]);
    }

    private function getApiNotification(string $deviceToken, string $deviceUniqueId, User $user) {
        $entityManager = $this->getDoctrine()->getManager();

        $apiNotification = $entityManager->getRepository(ApiNotification::class)->findOneBy([
            "user" => $user,
            "deviceUniqueId" => $deviceUniqueId
        ]);

        if($apiNotification == null) {
            $apiNotification = new ApiNotification();
            $apiNotification->setDeviceToken($deviceToken);
            $apiNotification->setDeviceUniqueId($deviceUniqueId);
            $apiNotification->setUser($user);

            $entityManager->persist($apiNotification);
            $entityManager->flush();
        } else if($apiNotification->getDeviceToken() != $deviceToken) {
            $apiNotification->setDeviceToken($deviceToken);

            $entityManager->persist($apiNotification);
            $entityManager->flush();
        }

        return $apiNotification;
    }

    /**
     * @Route("/update/notification-settings", name="update_notification_settings")
     */
    public function updateNotificationSettings(Request $request)
    {
    }
}
