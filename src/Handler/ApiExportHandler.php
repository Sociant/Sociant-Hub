<?php


namespace App\Handler;


use App\Entity\ApiNotification;
use App\Entity\AutomatedUpdate;
use App\Entity\TwitterUser;
use Doctrine\ORM\EntityManagerInterface;

class ApiExportHandler
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function exportTwitterUser(TwitterUser $twitterUser): array
    {
        return [
            "id"                => $twitterUser->getUuid(),
            "created_at"        => date_format($twitterUser->getAccountCreatedAt(),"Y-m-d H:i:s"),
            "name"              => $twitterUser->getName(),
            "screen_name"       => $twitterUser->getScreenName(),
            "location"          => $twitterUser->getLocation(),
            "description"       => $twitterUser->getDescription(),
            "url"               => $twitterUser->getUrl(),
            "protected"         => $twitterUser->getProtected(),
            "verified"          => $twitterUser->getVerified(),
            "translator"        => $twitterUser->getTranslator(),
            "followers_count"   => $twitterUser->getFollowersCount(),
            "friends_count"     => $twitterUser->getFriendsCount(),
            "listed_count"      => $twitterUser->getListedCount(),
            "favorites_count"   => $twitterUser->getFavoritesCount(),
            "statuses_count"    => $twitterUser->getStatusesCount(),
            "profile_image_url" => $twitterUser->getProfileImageURL()
        ];
    }

    public function exportApiNotification(ApiNotification $apiNotification): array
    {
        return [
            "device_token" => $apiNotification->getDeviceToken(),
            "device_unique_id" => $apiNotification->getDeviceUniqueId(),
            "notification_preferences" => $apiNotification->getNotificationPreferences()
        ];
    }

    public function exportAutomatedUpdate(AutomatedUpdate $automatedUpdate): array
    {
        return [
            "update_interval" => $automatedUpdate->getUpdateInterval(),
            "next_update" => date_format($automatedUpdate->getNextUpdate(),"Y-m-d H:i:s"),
            "last_update" => date_format($automatedUpdate->getLastUpdate(),"Y-m-d H:i:s")
        ];
    }

}