<?php

namespace App\Controller;

use App\Entity\ApiNotification;
use App\Entity\AutomatedUpdate;
use App\Entity\User;
use App\Entity\UserAction;
use App\Handler\ApiExportHandler;
use App\Handler\ApiNotificationHandler;
use App\Model\TwitterModel;
use Kreait\Firebase\Messaging;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/notification-settings", name="get_notification_settings", methods={"POST"})
     */
    public function getNotificationSettings(Request $request, ApiExportHandler $apiExportHandler)
    {
        $deviceToken = $request->request->get("device_token");
        $deviceUniqueId = $request->request->get("device_unique_id");

        if($deviceToken == null)
            return $this->json(["error"=>"no device token provided"],500);

        if($deviceUniqueId == null)
            return $this->json(["error"=>"no device unique id provided"],500);

        $user = $this->getUser();
        $notificationPreferences = $request->request->has("notification_settings") ? json_decode($request->request->get("notification_settings"), true) : [];

        $apiNotification = $this->getApiNotification($deviceToken, $deviceUniqueId, $user, true, $notificationPreferences);

        return $this->json($apiExportHandler->exportApiNotification($apiNotification));
    }

    /**
     * @Route("/notification-settings/remove", name="remove_notification_settings", methods={"POST"})
     */
    public function removeNotificationSettings(Request $request, ApiExportHandler $apiExportHandler)
    {
        $deviceToken = $request->request->get("device_token");
        $deviceUniqueId = $request->request->get("device_unique_id");

        if($deviceToken == null)
            return $this->json(["error"=>"no device token provided"],500);

        if($deviceUniqueId == null)
            return $this->json(["error"=>"no device unique id provided"],500);

        $user = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();

        $apiNotification = $this->getApiNotification($deviceToken, $deviceUniqueId, $user, false);

        if($apiNotification) {
            $entityManager->remove($apiNotification);
            $entityManager->flush();
        }

        return $this->json(["result" => "success"]);
    }

    /**
     * @Route("/update-interval", name="update_interval", methods={"POST"})
     */
    public function updateInterval(Request $request, ApiExportHandler $apiExportHandler)
    {
        $interval = $request->request->get("interval");

        $user = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();

        $automatedUpdate = $entityManager->getRepository(AutomatedUpdate::class)->findOneBy(["user"=>$user->getId()]);

        if($interval != null) {
            switch ($interval) {
                case "n": case "h1": case "h12": case "d1": case "w1": break;
                default: $interval = "h1";
            }

            $automatedUpdate->updateIntervalWithNextUpdate($interval);

            $entityManager->persist($automatedUpdate);

            $entityManager->flush();
        }

        return $this->json(["automated_update" => $apiExportHandler->exportAutomatedUpdate($automatedUpdate)]);
    }

    /**
     * @Route("/manual-update", name="manual_update", methods={"PUT"})
     */
    public function manualUpdate(TwitterModel $twitterModel)
    {
        $user = $this->getUser();
        $twitterModel->fetchUserData($user);

        return new Response("OK");
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
     * @Route("/history/{type}", name="history")
     */
    public function history($type, TwitterModel $twitterModel)
    {
        switch($type) {
            case "month": case "day": case "hour": break;
            default: $type = 'day';
        }

        $user = $this->getUser();

        $result = $twitterModel->getTotalHistory($user, $type, true);

        return $this->json([
            "items" => $result
        ]);
    }

    /**
     * @Route("/activities", name="activities")
     */
    public function activities(Request $request, ApiExportHandler $apiExportHandler)
    {
        $limit = $request->query->getInt('limit', 15);
        $page = $request->query->getInt('page', 0);
        $slimTwitterUser = $request->query->getBoolean('slim', true);

        if($limit < 1) $limit = 1; else if($limit > 50) $limit = 50;
        if($page < 0) $page = 0;

        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $activities = $entityManager->getRepository(UserAction::class)
            ->findActivitiesByUser($user, $limit, $page, true);

        $output = [];

        foreach($activities as $activity)
            $output[] = $apiExportHandler->exportUserAction($activity, $slimTwitterUser);

        $items = array_slice($output, 0, $limit);

        return $this->json([
            'items' => $items,
            'more_available' => sizeof($output) > $limit,
            'length' => sizeof($items),
            'page' => $page,
            'limit' => $limit,
            'slim' => $slimTwitterUser
        ]);
    }

    /**
     * @Route("/user-activities/{id}", name="user_activities")
     */
    public function userActivities(ApiExportHandler $apiExportHandler, $id)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $activities = $entityManager->createQuery("select a from App\Entity\UserAction a where a.user = :user and a.uuid = :uuid")
            ->setParameters([
                "user" => $user,
                "uuid" => $id
            ])->getResult();

        $output = [];

        foreach($activities as $activity)
            $output[] = $apiExportHandler->exportUserAction($activity, false, true);

        return $this->json([
            "items" => $output
        ]);
    }

    /**
     * @Route("/home", name="home")
     */
    public function home(TwitterModel $twitterModel, Request $request, ApiExportHandler $apiExportHandler)
    {
        $type = $request->query->get("type","day");

        switch($type) {
            case "month": case "day": case "hour": break;
            default: $type = 'day';
        }

        $user = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();

        $history = $twitterModel->getTotalHistory($user, $type, true);

        $activities = $entityManager->getRepository(UserAction::class)
            ->findActivitiesByUser($user, 15, 0, true);

        $output = [];

        foreach($activities as $activity)
            $output[] = $apiExportHandler->exportUserAction($activity, true);

        $twitterUser = $user->getTwitterUser();

        $automatedUpdate = null;
        $apiNotification = null;

        $entityManager = $this->getDoctrine()->getManager();

        $deviceToken = $request->query->get("device_token");
        $deviceUniqueId = $request->query->get("device_unique_id");

        if ($deviceToken != null && $deviceUniqueId != null)
            $apiNotification = $this->getApiNotification($deviceToken, $deviceUniqueId, $user, true);

        $automatedUpdate = $entityManager->getRepository(AutomatedUpdate::class)->findOneBy(["user"=>$user->getId()]);


        return $this->json([
            "type" => $type,
            "history" => $history,
            "activities" => $output,
            'twitter_user' => $twitterUser ? $apiExportHandler->exportTwitterUser($twitterUser) : null,
            'notification_settings' => $apiNotification ? $apiExportHandler->exportApiNotification($apiNotification) : null,
            'automated_update' => $automatedUpdate ? $apiExportHandler->exportAutomatedUpdate($automatedUpdate) : null
        ]);
    }

    /**
     * @Route("/statistics", name="statistics")
     */
    public function statistics(Request $request, ApiExportHandler $apiExportHandler)
    {
        $slimTwitterUser = $request->query->getBoolean('slim', true);

        $user = $this->getUser();
        $twitterUser = $user->getTwitterUser();
        $analytics = $user->getAnalytics();

        return $this->json([
            "statistics" => $apiExportHandler->exportUserStatistics($twitterUser),
            "analytics" => ($analytics && !$user->getAboveFollowerLimit()) ? $apiExportHandler->exportUserAnalytics($analytics, $slimTwitterUser) : null
        ]);
    }

    /**
     * @Route("/users/{type}", name="users")
     */
    public function users(Request $request, TwitterModel $twitterModel, ApiExportHandler $apiExportHandler, $type)
    {
        $limit = $request->query->getInt('limit', 15);
        $page = $request->query->getInt('page', 0);
        $slimTwitterUser = $request->query->getBoolean('slim', true);

        if($limit < 1) $limit = 1; else if($limit > 50) $limit = 50;
        if($page < 0) $page = 0;

        switch($type) {
            case "protected":case "verified": break;
            default: $type = "protected";
        }

        $user = $this->getUser();

        $result = $twitterModel->getUsersByType($user, $type, $limit, $page);

        $output = [];
        foreach($result["items"] as $twitterUser) {
            $output[] = $apiExportHandler->exportTwitterUser($twitterUser, $slimTwitterUser);
        }

        return $this->json([
            "items" => $output,
            "length" => sizeof($output),
            "type" => $type,
            "more_available" => $result["more_available"],
            "page" => $page,
            "limit" => $limit
        ]);
    }
}
