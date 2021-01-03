<?php

namespace App\Controller;

use App\Entity\ApiNotification;
use App\Entity\AutomatedUpdate;
use App\Entity\User;
use App\Handler\ApiExportHandler;
use App\Handler\ApiNotificationHandler;
use App\Model\TwitterModel;
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
    public function info(Request  $request, ApiExportHandler $apiExportHandler)
    {
        $user = $this->getUser();
        $twitterUser = $user->getTwitterUser();
        $setupCompleted = $user->getSetupCompleted();

        $automatedUpdate = null;
        $apiNotification = null;

        $entityManager = $this->getDoctrine()->getManager();

        if($setupCompleted) {
            $deviceToken = $request->query->get("device_token");
            $deviceUniqueId = $request->query->get("device_unique_id");

            if ($deviceToken != null && $deviceUniqueId != null)
                $apiNotification = $this->getApiNotification($deviceToken, $deviceUniqueId, $user, false);

            $automatedUpdate = $entityManager->getRepository(AutomatedUpdate::class)->findOneBy(["user"=>$user->getId()]);
        }

        $followerLimit = $this->getParameter("follower_limit");

        return $this->json([
            'setup_completed' => $setupCompleted,
            'above_follower_limit' => $user->getAboveFollowerLimit(),
            'follower_limit' => intval($followerLimit),
            'twitter_user' => $twitterUser ? $apiExportHandler->exportTwitterUser($twitterUser) : null,
            'notification_settings' => $apiNotification ? $apiExportHandler->exportApiNotification($apiNotification) : null,
            'automated_update' => $automatedUpdate ? $apiExportHandler->exportAutomatedUpdate($automatedUpdate) : null
        ]);
    }

    /**
     * @Route("/setup", name="setup", methods={"POST"})
     */
    public function setup(Request $request, ApiExportHandler $apiExportHandler, TwitterModel $twitterModel)
    {
        $user = $this->getUser();
        $twitterUser = $user->getTwitterUser();
        $setupCompleted = $user->getSetupCompleted();
        $entityManager = $this->getDoctrine()->getManager();
        $automatedUpdate = null;
        $apiNotification = null;

        $deviceToken = $request->request->get("device_token");
        $deviceUniqueId = $request->request->get("device_unique_id");

        if(!$setupCompleted) {
            if($deviceToken == null)
                return $this->json(["error"=>"no device token provided"],500);

            if($deviceUniqueId == null)
                return $this->json(["error"=>"no device unique id provided"],500);

            $interval = $request->request->get("interval", "h1");

            switch($interval) {
                case "n": case "h1": case "h12": case "d1": case "w1": break;
                default: $interval = "h1";
            }

            $notificationPreferences = $request->request->has("notification_settings") ? json_decode($request->request->get("notification_settings"), true) : [];

            $apiNotification = $this->getApiNotification($deviceToken, $deviceUniqueId, $user, true, $notificationPreferences);

            $twitterModel->fetchUserData($user,true,$interval);
            $automatedUpdate = $entityManager->getRepository(AutomatedUpdate::class)->findOneBy(["user"=>$user->getId()]);

            $user->setSetupCompleted(true);
            $entityManager->persist($user);
        } else {
            $automatedUpdate = $entityManager->getRepository(AutomatedUpdate::class)->findOneBy(["user"=>$user->getId()]);
            $notificationPreferences = $request->request->has("notification_settings") ? json_decode($request->request->get("notification_settings"), true) : [];

            if ($deviceToken != null && $deviceUniqueId != null)
                $apiNotification = $this->getApiNotification($deviceToken, $deviceUniqueId, $user, true, $notificationPreferences);

            $currentInterval = $automatedUpdate->getUpdateInterval();

            if($request->request->get("interval", $currentInterval) != $currentInterval) {
                $interval = $request->request->get("interval", $currentInterval);

                switch ($interval) {
                    case "n": case "h1": case "h12": case "d1": case "w1": break;
                    default: $interval = "h1";
                }

                $automatedUpdate->updateIntervalWithNextUpdate($interval);

                $entityManager->persist($automatedUpdate);
            }
        }

        $entityManager->flush();

        $followerLimit = $this->getParameter("follower_limit");

        return $this->json([
            'setup_completed' => $user->getSetupCompleted(),
            'above_follower_limit' => $user->getAboveFollowerLimit(),
            'follower_limit' => intval($followerLimit),
            'twitter_user' => $twitterUser ? $apiExportHandler->exportTwitterUser($twitterUser) : null,
            'notification_settings' => $apiNotification ? $apiExportHandler->exportApiNotification($apiNotification) : null,
            'automated_update' => $automatedUpdate ? $apiExportHandler->exportAutomatedUpdate($automatedUpdate) : null
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

    private function getApiNotification(string $deviceToken, string $deviceUniqueId, User $user, bool $createIfNull = true, $updateData = null) {
        $entityManager = $this->getDoctrine()->getManager();

        $apiNotification = $entityManager->getRepository(ApiNotification::class)->findOneBy([
            "user" => $user,
            "deviceUniqueId" => $deviceUniqueId
        ]);

        $persistUpdate = false;

        if($apiNotification == null && $createIfNull) {
            $apiNotification = new ApiNotification();
            $apiNotification->setDeviceToken($deviceToken);
            $apiNotification->setDeviceUniqueId($deviceUniqueId);
            $apiNotification->setUser($user);

            $persistUpdate = true;
        } else if($apiNotification != null && $apiNotification->getDeviceToken() != $deviceToken) {
            $apiNotification->setDeviceToken($deviceToken);

            $persistUpdate = true;
        }

        if($apiNotification != null && $updateData != null) {
            $currentPreferences = $apiNotification->getNotificationPreferences();

            $updatedPreferences = [];

            foreach($currentPreferences as $key=>$currentValue) {
                $updatedPreferences[$key] = $updateData[$key] ?? $currentValue;
            }

            $apiNotification->setNotificationPreferences($updatedPreferences);
            $persistUpdate = true;
        }

        if($persistUpdate) {
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
