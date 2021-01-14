<?php


namespace App\Handler;


use App\Entity\ApiNotification;
use App\Entity\User;
use App\Entity\UserAction;
use Doctrine\ORM\EntityManagerInterface;
use Kreait\Firebase\Messaging;

class ApiNotificationHandler
{

    private $entityManager;
    private $messaging;
    private $apiExportHandler;

    public function __construct(EntityManagerInterface $entityManager, Messaging $messaging, ApiExportHandler $apiExportHandler)
    {
        $this->entityManager = $entityManager;
        $this->messaging = $messaging;
        $this->apiExportHandler = $apiExportHandler;
    }

    private function filterRelevantActions($preferences, array $userActions) {
        $output = [];
        foreach($userActions as $userAction) {
            $type = $userAction->getAction();
            if($preferences[$type] ?? false) {
                if(isset($output[$type])) $output[$type][] = $userAction;
                else $output[$type] = [$userAction];
            }
        }

        return $output;
    }

    private function getSingleActionMessages(UserAction $userAction) {
        $twitterUser = $userAction->getTwitterUser();
        $data = [
            "user_action" => json_encode($this->apiExportHandler->exportUserAction($userAction, true))
        ];

        switch($userAction->getAction()) {
            case "follow_self":
                return [
                    "notification" => [ "title" => 'You followed @' . $twitterUser->getScreenName() ],
                    "data" => $data
                ];
            case "unfollow_self":
                return [
                    "notification" => [ "title" => 'You unfollowed @' . $twitterUser->getScreenName() ],
                    "data" => $data
                ];
            case "follow_other":
                return [
                    "notification" => [ "title" => 'New Follower', 'body' => $twitterUser->getName() . ' (@' . $twitterUser->getScreenName() . ') followed you' ],
                    "data" => $data
                ];
            case "unfollow_other":
                return [
                    "notification" => [ "title" => 'New Unfollower', 'body' => $twitterUser->getName() . ' (@' . $twitterUser->getScreenName() . ') unfollowed you' ],
                    "data" => $data
                ];
        }

        return null;
    }

    private function getMultipleActionMessages($type, array $userActions) {
        $userNames = [];
        $printLimit = 2;

        foreach($userActions as $userAction) {
            $twitterUser = $userAction->getTwitterUser();

            if(sizeof($userNames) < $printLimit)
                $userNames[] = '@' . $twitterUser->getScreenName();
        }

        $message = join(', ', $userNames);
        if(sizeof($userActions) > $printLimit) {
            $overflow = (sizeof($userActions) - $printLimit);
            $message .= ' and ' . $overflow. ' other user' . ($overflow > 1 ? 's' : '');
        }

        switch($type) {
            case "follow_self":
                return [
                    "notification" => [
                        "title" => 'You followed ' . sizeof($userActions) . " users",
                        "body" => $message
                    ],
                    "data" => []
                ];
            case "unfollow_self":
                return [
                    "notification" => [
                        "title" => 'You unfollowed ' . sizeof($userActions) . " users",
                        "body" => $message
                    ],
                    "data" => []
                ];
            case "follow_other":
                return [
                    "notification" => [
                        "title" => sizeof($userActions) . " new followers",
                        "body" => $message . " are now following you"
                    ],
                    "data" => []
                ];
            case "unfollow_other":
                return [
                    "notification" => [
                        "title" => sizeof($userActions) . " new unfollowers",
                        "body" => $message . " are no longer following you"
                    ],
                    "data" => []
                ];
        }

        return null;
    }

    public function sendActivitiesNotificationToUser(User $user, array $userActions) {
        if(sizeof($userActions) == 1) $this->sendActivityNotificationToUser($user, $userActions[0]);
        else {
            foreach($user->getApiNotifications() as $apiNotification) {
                $preferences = $apiNotification->getNotificationPreferences();

                $filteredActions = $this->filterRelevantActions($preferences, $userActions);

                if(sizeof($filteredActions) == 1) {
                    $type = array_keys($filteredActions)[0];

                    if(sizeof($filteredActions[$type]) == 1) {
                        $actionMessage = $this->getSingleActionMessages($filteredActions[$type][0]);

                        $this->sendNotification($apiNotification, $type, $actionMessage["notification"], $actionMessage["data"]);
                    } else {
                        $actionMessage = $this->getMultipleActionMessages($type, $filteredActions[$type]);

                        $this->sendNotification($apiNotification, $type, $actionMessage["notification"], $actionMessage["data"]);
                    }
                } else if(sizeof($filteredActions) > 1) {
                    $title = "Several Activities";
                    $type = array_keys($filteredActions)[0];

                    $activities = [];

                    if(isset($filteredActions["follow_self"])) {
                        $count = sizeof($filteredActions["follow_self"]);
                        $activities[] = 'You followed ' . $count . ' user' . ($count > 1 ? 's' : '');
                    }

                    if(isset($filteredActions["unfollow_self"])) {
                        $count = sizeof($filteredActions["unfollow_self"]);
                        $activities[] = 'You unfollowed ' . $count . ' user' . ($count > 1 ? 's' : '');
                    }

                    if(isset($filteredActions["follow_other"])) {
                        $count = sizeof($filteredActions["follow_other"]);
                        $activities[] = $count . ' user' . ($count > 1 ? 's are' : ' is') . ' now following you';
                    }

                    if(isset($filteredActions["unfollow_other"])) {
                        $count = sizeof($filteredActions["unfollow_other"]);
                        $activities[] = $count . ' user' . ($count > 1 ? 's are' : ' is') . ' no longer following you';
                    }

                    $message = join(', ', $activities);

                    $this->sendNotification($apiNotification, $type, [ 'title' => $title, 'body' => $message ], []);
                }
            }
        }
    }

    public function sendActivityNotificationToUser(User $user, UserAction $userAction) {
        $actionMessage = $this->getSingleActionMessages($userAction);
        $this->sendNotificationToUser($user, $userAction->getAction(), $actionMessage["notification"], $actionMessage["data"]);
    }

    public function sendNotificationToUser(User $user, string $type, array $notification, array $data) {
        $deviceTokens = [];

        foreach($user->getApiNotifications() as $apiNotification) {
            $preferences = $apiNotification->getNotificationPreferences();
            $canSend = $type == "testing" ? true : ($canSend = $preferences[$type] ?? false);
            if($canSend) $deviceTokens[] = $apiNotification->getDeviceToken();
        }

        if(sizeof($deviceTokens) == 0) return;

        $message = Messaging\CloudMessage::fromArray([
            "notification" => $notification,
            "data" => $data
        ])->withAndroidConfig(
            Messaging\AndroidConfig::fromArray([
                "collapse_key" => "sociant-hub"
            ])
        );;

        $this->messaging->sendMulticast($message, $deviceTokens);
    }

    public function sendNotification(ApiNotification $apiNotification, string $type, array $notification, array $data) {
        $preferences = $apiNotification->getNotificationPreferences();
        $canSend = $type == "testing" ? true : ($canSend = $preferences[$type] ?? false);
        if(!$canSend) return;

        $message = Messaging\CloudMessage::fromArray([
            "token" => $apiNotification->getDeviceToken(),
            "notification" => $notification,
            "data" => $data
        ])->withAndroidConfig(
            Messaging\AndroidConfig::fromArray([
                "collapse_key" => "sociant-hub"
            ])
        );

        $this->messaging->send($message);
    }

}