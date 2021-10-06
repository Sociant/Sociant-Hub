<?php

namespace App\Controller;

use App\Entity\ApiNotification;
use App\Entity\AutomatedUpdate;
use App\Entity\TwitterUser;
use App\Entity\User;
use App\Entity\UserAction;
use App\Entity\UserStatistic;
use App\Handler\ApiExportHandler;
use App\Handler\ApiNotificationHandler;
use App\Model\TwitterModel;
use DateTime;
use Kreait\Firebase\Messaging;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ApiController extends AbstractController
{

    #[Route('/info', name: 'info')]
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

    #[Route('/setup', name: 'setup', methods: ['POST'])]
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

    #[Route('/notification-settings', name: 'get_notification_settings', methods: ['POST'])]
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

    #[Route('/notification-settings/remove', name: 'remove_notification_settings', methods: ['POST'])]
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

    #[Route('/update-interval', name: 'update_interval', methods: ['POST'])]
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

    #[Route('/manual-update', name: 'manual_update', methods: ['PUT'])]
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

    #[Route('/history/{type}', name: 'history')]
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

    #[Route('/activities', name: 'activities')]
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

    #[Route('/user-activities/{id}', name: 'user_activities')]
    public function userActivities(ApiExportHandler $apiExportHandler, $id)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $activities = $entityManager->createQuery("select a from App\Entity\UserAction a where a.user = :user and a.uuid = :uuid order by a.timestamp desc")
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

    #[Route('/home', name: 'home')]
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
            'can_update' => $automatedUpdate ? $automatedUpdate->getLastUpdate() < new \DateTime("-59 minutes") : null,
            "type" => $type,
            "history" => $history,
            "activities" => $output,
            'twitter_user' => $twitterUser ? $apiExportHandler->exportTwitterUser($twitterUser) : null,
            'notification_settings' => $apiNotification ? $apiExportHandler->exportApiNotification($apiNotification) : null,
            'automated_update' => $automatedUpdate ? $apiExportHandler->exportAutomatedUpdate($automatedUpdate) : null
        ]);
    }

    #[Route('/statistics', name: 'statistics')]
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

    #[Route('/users/{type}', name: 'users')]
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

    #[Route('/users/get/{uuid}', name: 'users_get')]
    public function usersGet(Request $request, TwitterModel $twitterModel, ApiExportHandler $apiExportHandler, $uuid)
    {
        $slimTwitterUser = $request->query->getBoolean('slim', true);

        $user = $this->getUser();

        /** @var TwitterUser|null $result */
        $result = $twitterModel->getUserByUuid($uuid, $user);

        if($result) return $this->json($apiExportHandler->exportTwitterUser($result, $slimTwitterUser));

        return $this->json(["error"=>"unknown user"],404);
    }

    #[Route('/users/get/{uuid}/relation', name: 'users_get_relation')]
    public function usersGetRelation(Request $request, TwitterModel $twitterModel, ApiExportHandler $apiExportHandler, $uuid)
    {
        $user = $this->getUser();

        $result = $twitterModel->getUserRelation($uuid, $user);

        if($result) return $this->json($result);

        return $this->json(["error"=>"unknown error"],404);
    }

    #[Route('/download/activities', name: 'download_activities')]
    public function downloadActivities(Request $request, ApiExportHandler $apiExportHandler)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        $activities = $entityManager->getRepository(UserAction::class)
            ->findAllActivitiesByUser($user);

        $format = 'csv';

        if($request->query->has('format')) {
            switch($request->query->get('format')) {
                case 'json': $format = 'json'; break;
            }
        }

        $data = [];

        foreach($activities as $activity) {
            $data[] = $apiExportHandler->exportUserAction($activity, true);
        }

        if($format === 'csv') {
            $rows = [
                implode(',', [ 'date formatted'. 'date timestamp', 'uuid', 'name', 'screen name', 'action' ])
            ];
    
            foreach($data as $entry) {
                $rows[] = implode(',', [
                    date_format(new DateTime('@' . $entry['timestamp']), 'H:i m/d/Y'),
                    $entry['timestamp'],
                    $entry['uuid'],
                    $entry['twitter_user']['name'],
                    $entry['twitter_user']['screen_name'],
                    $entry['action']
                ]);
            }
    
            $content = implode("\n", $rows);
    
            $response = new Response($content);
            $response->headers->set('Content-Encoding', 'UTF-8');
            $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
            $response->headers->set('Content-Disposition', 'attachment; filename=sociant_hub_activities.csv');
    
            return $response;
        } else if($format === 'json') {
            $output = [];

            foreach($data as $entry) {
                $output[] = [
                    'date_formatted' => date_format(new DateTime('@' . $entry['timestamp']), 'H:i m/d/Y'),
                    'date_timestamp' => $entry['timestamp'],
                    'uuid' => $entry['uuid'],
                    'name' => $entry['twitter_user']['name'],
                    'screen_name' => $entry['twitter_user']['screen_name'],
                    'action' => $entry['action']
                ];
            }
        
            $response = new Response(json_encode($output));
            $response->headers->set('Content-Encoding', 'UTF-8');
            $response->headers->set('Content-Type', 'application/json; charset=UTF-8');
            $response->headers->set('Content-Disposition', 'attachment; filename=sociant_hub_activities.json');
    
            return $response;
        }
    }

    #[Route('/download/history/{period}', name: 'download_history')]
    public function downloadHistory(Request $request, $period)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        $data = $entityManager->getRepository(UserStatistic::class)
            ->findAllStatisticsForUser($user);

        $dateFormat = 'H:i m/d/Y';


        switch($period) {
            case 'year': {
                $history = [];
                $listedYears = [];

                foreach($data as $entry) {
                    $date = $entry['date'];
                    $year = $date->format('y');

                    if(!in_array($year, $listedYears)) {
                        $history[] = $entry;
                        $listedYears[] = $year;
                    }
                }

                $dateFormat = 'Y';
            }; break;
            case 'month': {
                $history = [];
                $listedMonths = [];

                foreach($data as $entry) {
                    $date = $entry['date'];
                    $year = $date->format('y');
                    $month = $date->format('m');

                    $timestamp = "$year-$month";

                    if(!in_array($timestamp, $listedMonths)) {
                        $history[] = $entry;
                        $listedMonths[] = $timestamp;
                    }
                }

                $dateFormat = 'm/Y';
            }; break;
            case 'day': {
                $history = [];
                $listedDates = [];

                foreach($data as $entry) {
                    $date = $entry['date'];
                    $year = $date->format('y');
                    $month = $date->format('m');
                    $day = $date->format('d');

                    $timestamp = "$year-$month-$day";

                    if(!in_array($timestamp, $listedDates)) {
                        $history[] = $entry;
                        $listedDates[] = $timestamp;
                    }
                }

                $dateFormat = 'm/d/Y';
            }; break;
            default: $history = $data; $period = 'all';
        }

        $format = 'csv';

        if($request->query->has('format')) {
            switch($request->query->get('format')) {
                case 'json': $format = 'json'; break;
            }
        }

        if($format === 'csv') {
            $rows = [
                implode(',', [ 'date formatted'. 'date timestamp', 'follower count', 'following count' ])
            ];
    
            foreach($history as $entry) {
                $rows[] = implode(',', [
                    date_format($entry['date'], $dateFormat),
                    $entry['date']->getTimestamp(),
                    $entry['followerCount'],
                    $entry['followingCount']
                ]);
            }
    
            $content = implode("\n", $rows);
    
            $response = new Response($content);
            $response->headers->set('Content-Encoding', 'UTF-8');
            $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
            $response->headers->set('Content-Disposition', 'attachment; filename=sociant_hub_activities.csv');
    
            return $response;
        } else if($format === 'json') {
            $output = [];

            foreach($history as $entry) {
                $output[] = [
                    'date_formatted' => date_format($entry['date'], $dateFormat),
                    'date_timestamp' => $entry['date']->getTimestamp(),
                    'follower_count' => $entry['followerCount'],
                    'following_count' => $entry['followingCount']
                ];
            }
        
            $response = new Response(json_encode($output));
            $response->headers->set('Content-Encoding', 'UTF-8');
            $response->headers->set('Content-Type', 'application/json; charset=UTF-8');
            $response->headers->set('Content-Disposition', 'attachment; filename=sociant_hub_history_' . $period . '.json');
    
            return $response;
        }
    }

    #[Route('/download/additional', name: 'download_additional')]
    public function downloadAdditional(Request $request, ApiExportHandler $apiExportHandler)
    {
        $user = $this->getUser();
        $twitterUser = $user->getTwitterUser();
        $setupCompleted = $user->getSetupCompleted();
        $entityManager = $this->getDoctrine()->getManager();
        $automatedUpdate = $entityManager->getRepository(AutomatedUpdate::class)->findOneBy(["user"=>$user->getId()]);
        $analytics = $user->getAnalytics();

        $apiNotifications = [];

        $data = $entityManager->getRepository(ApiNotification::class)->findBy([
            'user' => $user
        ]);

        foreach($data as $entry)
            $apiNotifications[] = $apiExportHandler->exportApiNotification($entry);

        $output = [
            'uuid' => $user->getUuid(),
            'screen_name' => $user->getTwitterUserScreenName(),
            'setup_completed' => $setupCompleted,
            'above_follower_limit' => $user->getAboveFollowerLimit(),
            'twitter_user' => $twitterUser ? $apiExportHandler->exportTwitterUser($twitterUser) : null,
            'notification_settings' => $apiNotifications,
            'automated_update' => $automatedUpdate ? $apiExportHandler->exportAutomatedUpdate($automatedUpdate) : null,
            "analytics" => ($analytics && !$user->getAboveFollowerLimit()) ? $apiExportHandler->exportUserAnalytics($analytics, true) : null
        ];
    
        $response = new Response(json_encode($output));
        $response->headers->set('Content-Encoding', 'UTF-8');
        $response->headers->set('Content-Type', 'application/json; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename=sociant_hub_activities.json');
    
        return $response;
    }
}
