<?php


namespace App\Handler;


use App\Entity\ApiNotification;
use App\Entity\AutomatedUpdate;
use App\Entity\TwitterUser;
use App\Entity\UserAction;
use App\Entity\UserAnalytics;
use Doctrine\ORM\EntityManagerInterface;

class ApiExportHandler
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function exportTwitterUser(TwitterUser $twitterUser, $slim = false): array
    {
        if($slim)
            return [
                "id"                => $twitterUser->getUuid(),
                "name"              => $twitterUser->getName(),
                "screen_name"       => $twitterUser->getScreenName(),
                "protected"         => $twitterUser->getProtected(),
                "verified"          => $twitterUser->getVerified(),
                "translator"        => $twitterUser->getTranslator(),
                "profile_image_url" => $twitterUser->getProfileImageURL()
            ];
        else
            return [
                "id"                => $twitterUser->getUuid(),
                "created_at"        => $twitterUser->getAccountCreatedAt(),
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
            "next_update" => $automatedUpdate->getNextUpdate()->getTimestamp(),
            "last_update" => $automatedUpdate->getLastUpdate()->getTimestamp()
        ];
    }

    public function exportUserAction(UserAction $userAction, $slimTwitterUser = false, $skipTwitterUser = false): array
    {
        return [
            'id' => $userAction->getId(),
            'timestamp' => $userAction->getTimestamp()->getTimestamp(),
            'uuid' => $userAction->getUuid(),
            'twitter_user' => $skipTwitterUser ? null : $this->exportTwitterUser($userAction->getTwitterUser(),$slimTwitterUser),
            'action' => $userAction->getAction()
        ];
    }

    public function exportUserAnalytics(UserAnalytics $userAnalytics, $slimTwitterUser = false): array {
        return [
            "updated" => $userAnalytics->getUpdated(),
            "verified_followers" => $userAnalytics->getVerifiedFollowers(),
            "protected_followers" => $userAnalytics->getProtectedFollowers(),
            "most_statuses" => $userAnalytics->getMostStatuses() ? $this->exportTwitterUser($userAnalytics->getMostStatuses(), $slimTwitterUser) : null,
            "most_followers" => $userAnalytics->getMostFollowers() ? $this->exportTwitterUser($userAnalytics->getMostFollowers(), $slimTwitterUser) : null,
            "oldest_account" => $userAnalytics->getOldestAccount() ? $this->exportTwitterUser($userAnalytics->getOldestAccount(), $slimTwitterUser) : null,
            "status_count" => $userAnalytics->getStatusCount(),
            "favorite_count" => $userAnalytics->getFavoriteCount()
        ];
    }

    public function exportUserStatistics(TwitterUser $twitterUser): array {
        return [
            "updated" => $twitterUser->getCreated(),
            "followers_count" => $twitterUser->getFollowersCount(),
            "friends_count" => $twitterUser->getFriendsCount(),
            "listed_count" => $twitterUser->getListedCount(),
            "statuses_count" => $twitterUser->getStatusesCount(),
            "favorites_count" => $twitterUser->getFavoritesCount(),
            "created_at" => $twitterUser->getAccountCreatedAt()
        ];
    }

}