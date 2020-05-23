<?php

namespace App\Model;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Entity\AutomatedUpdate;
use App\Entity\TwitterUser;
use App\Entity\User;
use App\Entity\UserAction;
use App\Entity\UserAnalytics;
use App\Entity\UserRelation;
use App\Entity\UserStatistic;
use Doctrine\ORM\EntityManagerInterface;

class TwitterModel
{

    private $entityManager;
    private $apiKey;
    private $apiSecretKey;

    public function __construct(EntityManagerInterface $entityManager, $apiKey, $apiSecretKey)
    {
        $this->entityManager = $entityManager;
        $this->apiKey = $apiKey;
        $this->apiSecretKey = $apiSecretKey;
    }

    public function createConnection(User $user): TwitterOAuth
    {
        return new TwitterOAuth(
            $this->apiKey,
            $this->apiSecretKey,
            $user->getTwitterUserOauthToken(),
            $user->getTwitterUserOauthTokenSecret()
        );
    }

    public function verifyCredentials(User $user)
    {
        $connection = $this->createConnection($user);

        $userData = $connection->get("account/verify_credentials");
        $twitterUser = $this->createTwitterUserFromArray((array) $userData);

        $user->setTwitterUser($twitterUser);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $twitterUser;
    }

    public function fetchUserData(User $user, $firstUpdate = false, $updateInterval = "h1")
    {
        $connection = $this->createConnection($user);

        $repository = $this->entityManager->getRepository(UserRelation::class);
        $followerUserRelation = $repository->findOneBy(["user" => $user->getId(), "type" => "follower"]) ?? new UserRelation();
        $followingUserRelation = $repository->findOneBy(["user" => $user->getId(), "type" => "following"]) ?? new UserRelation();

        $userStats = $this->entityManager->createQuery("select s from App\Entity\UserStatistic s where s.date = CURRENT_DATE() and s.user = :user")->setParameter("user", $user->getId())->getOneOrNullResult() ?? new UserStatistic();

        $this->verifyCredentials($user);

        $userStats->setUser($user);
        $userStats->setDate(new \DateTime());
        $userStats->setFollowerCount($user->getTwitterUser()->getFollowersCount());
        $userStats->setFollowingCount($user->getTwitterUser()->getFriendsCount());

        $this->entityManager->persist($userStats);

        $lastUpdate = $user->getAutomatedUpdate() ? $user->getAutomatedUpdate()->getLastUpdate() : null;

        if($lastUpdate == null || $lastUpdate < new \DateTime("-50 minutes")) {

            $followerIDs = $this->fetchFollowers($user, $connection);
            $existingData = is_null($followerUserRelation->getData()) ? [] : json_decode($followerUserRelation->getData());

            if (!$firstUpdate) $this->compareLists($existingData, $followerIDs, "follower", $user);

            $followerUserRelation->setType("follower");
            $followerUserRelation->setUpdated(new \DateTime());
            $followerUserRelation->setUser($user);
            $followerUserRelation->setData(json_encode($followerIDs));
            $followerUserRelation->setCount(sizeof($followerIDs));

            $this->entityManager->persist($followerUserRelation);

            $followingIDs = $this->fetchFollowing($user, $connection);
            $existingData = is_null($followingUserRelation->getData()) ? [] : json_decode($followingUserRelation->getData());

            if (!$firstUpdate) $this->compareLists($existingData, $followingIDs, "following", $user);

            $followingUserRelation->setType("following");
            $followingUserRelation->setUpdated(new \DateTime());
            $followingUserRelation->setUser($user);
            $followingUserRelation->setData(json_encode($followingIDs));
            $followingUserRelation->setCount(sizeof($followingIDs));

            $this->entityManager->persist($followingUserRelation);

            $this->updateAnalytics($user);

            $automatedUpdate = $user->getAutomatedUpdate();

            if($automatedUpdate == null) {
                $automatedUpdate = new AutomatedUpdate();
                $automatedUpdate->setUser($user);
                $automatedUpdate->setUpdateInterval($updateInterval);
            }

            $automatedUpdate->setLastUpdate(new \DateTime());
            
            $nextUpdate = null;

            switch($automatedUpdate->getUpdateInterval()) {
                case "h1": $nextUpdate = new \DateTime("+1 hours"); break;
                case "h12": $nextUpdate = new \DateTime("+12 hours"); break;
                case "d1": $nextUpdate = new \DateTime("+1 days"); break;
                case "w1": $nextUpdate = new \DateTime("+7 days"); break;
            }

            $automatedUpdate->setNextUpdate($nextUpdate);

            $this->entityManager->persist($automatedUpdate);
        }

        $this->entityManager->flush();
    }



    public function compareLists($oldList = [], $newList = [], $action, User $user)
    {
        $ids = [];

        foreach (array_diff($oldList, $newList) as $id)
            $ids["$id"] = [
                "id" => $id,
                "action" => $action == "following" ? "unfollow_self" : "unfollow_other",
                "twitterUser" => null
            ];

        foreach (array_diff($newList, $oldList) as $id)
            $ids["$id"] = [
                "id" => $id,
                "action" => $action == "following" ? "follow_self" : "follow_other",
                "twitterUser" => null
            ];

        $chunks = array_chunk($ids, 100);

        foreach ($chunks as $chunk) {
            $userIds = [];

            foreach ($chunk as $item) {
                array_push($userIds, $item["id"]);
            }

            $cachedResult = $this->entityManager->getRepository(TwitterUser::class)->findBy([
                "uuid" => $userIds
            ]);

            foreach ($cachedResult as $twitterUser) {
                if (isset($ids[$twitterUser->getUuid()]))
                    $ids[$twitterUser->getUuid()]["twitterUser"] = $twitterUser;
            }
        }

        $date = new \DateTime();

        foreach ($ids as $id => $data) {
            $userAction = new UserAction();
            $userAction->setUser($user);
            $userAction->setTimestamp($date);
            $userAction->setUuid($id);
            $userAction->setTwitterUser($data["twitterUser"]);
            $userAction->setAction($data["action"]);

            $this->entityManager->persist($userAction);
        }
    }

    public function fetchFollowers(User $user, ?TwitterOAuth $connection)
    {
        if (is_null($connection)) $connection = $this->createConnection($user);

        $nextCursor = -1;
        $loops = 0;
        $ids = [];

        while ($nextCursor != 0 && $loops < 15) {
            $result = (array) $connection->get("followers/ids", [
                "user_id" => $user->getUuid(),
                "count" => 5000,
                "cursor" => $nextCursor
            ]);

            $ids = array_merge($ids, $result["ids"]);

            $loops++;
            $nextCursor = $result["next_cursor"];
        }

        $this->updateTwitterUsersFromIds($ids, $connection);

        return $ids;
    }

    public function fetchFollowing(User $user, ?TwitterOAuth $connection)
    {
        if (is_null($connection)) $connection = $this->createConnection($user);

        $nextCursor = -1;
        $loops = 0;
        $ids = [];

        while ($nextCursor != 0 && $loops < 15) {
            $result = (array) $connection->get("friends/ids", [
                "user_id" => $user->getUuid(),
                "count" => 5000,
                "cursor" => $nextCursor
            ]);

            $ids = array_merge($ids, $result["ids"]);

            $loops++;
            $nextCursor = $result["next_cursor"];
        }

        $this->updateTwitterUsersFromIds($ids, $connection);

        return $ids;
    }

    public function updateTwitterUsersFromIds($ids, TwitterOAuth $connection)
    {
        $chunks = array_chunk($ids, 100);

        foreach ($chunks as $chunk) {
            $needUpdate = [];

            foreach ($chunk as $id)
                $needUpdate[$id] = null;

            $cachedResult = $this->entityManager->getRepository(TwitterUser::class)->findBy([
                "uuid" => $chunk
            ]);

            foreach ($cachedResult as $twitterUser) {
                if ($twitterUser->getCreated() < new \DateTime("- 1 days"))
                    $needUpdate[$twitterUser->getUuid()] = $twitterUser;
                else
                    unset($needUpdate[$twitterUser->getUuid()]);
            }

            $gluedIDs = implode(",", array_keys($needUpdate));

            $result = (array) $connection->get("users/lookup", [
                "user_id" => $gluedIDs
            ]);

            foreach ($result as $rawUser) {
                $rawUser = (array) $rawUser;
                if (isset($rawUser["id"])) $this->createTwitterUserFromArray($rawUser, true, $needUpdate[$rawUser["id"]], false);
            }

            $this->entityManager->flush();
        }
    }

    public function createTwitterUserFromArray($user, $skipDatabase = false, ?TwitterUser $twitterUser = null, $instantFlush = true): TwitterUser
    {

        if ($skipDatabase) $twitterUser = $twitterUser ?? new TwitterUser();
        else $twitterUser = $this->entityManager->getRepository(TwitterUser::class)->findOneBy(["uuid" => $user["id_str"]]) ?? new TwitterUser();

        $twitterUser->setUuid($user["id_str"]);
        $twitterUser->setCreated(new \DateTime());
        $twitterUser->setName($user["name"]);
        $twitterUser->setScreenName($user["screen_name"]);
        $twitterUser->setLocation($user["location"]);
        $twitterUser->setDescription($user["description"]);
        $twitterUser->setUrl($user["url"]);
        $twitterUser->setProtected($user["protected"]);
        $twitterUser->setFollowersCount($user["followers_count"]);
        $twitterUser->setFriendsCount($user["friends_count"]);
        $twitterUser->setListedCount($user["listed_count"]);
        $twitterUser->setAccountCreatedAt(new \DateTime($user["created_at"]));
        $twitterUser->setFavoritesCount($user["favourites_count"]);
        $twitterUser->setVerified($user["verified"]);
        $twitterUser->setStatusesCount($user["statuses_count"]);
        $twitterUser->setTranslator($user["is_translator"]);
        $twitterUser->setProfileImageURL($user["profile_image_url_https"]);

        $this->entityManager->persist($twitterUser);
        if ($instantFlush) $this->entityManager->flush();

        return $twitterUser;
    }

    public function getTotalHistory(User $user, $range = "month", $simplyfied = false)
    {
        $query = "";

        switch ($range) {
            case "month": {
                    $result = $this->entityManager->createQuery(
                        "select max(s.id) as id, MONTH(s.date) as sMonth, YEAR(s.date) as sYear from App\Entity\UserStatistic s
                    index by s.id
                    where s.user = :user
                    and s.date >= :past
                    and s.date <= :future
                    group by sMonth, sYear"
                    )->setParameter(":user", $user->getId())
                    ->setParameter(":past", new \DateTime("-12 months"))
                    ->setParameter(":future", new \DateTime("+12 months"))
                        ->getArrayResult();

                    $ids = [];

                    foreach ($result as $item)
                        array_push($ids, $item["id"]);

                    $query = $this->entityManager->createQuery(
                        "select s.date, s.followerCount, s.followingCount, MONTH(s.date) as sMonth, YEAR(s.date) as sYear from App\Entity\UserStatistic s
                    where s.user = :user
                    and s.id in (:list)
                    order by s.date"
                    )->setParameter(":user", $user->getId())
                        ->setParameter(":list", $ids);
                }
                break;
            case "day": {
                    $result = $this->entityManager->createQuery(
                        "select max(s.id) as id, DAY(s.date) as sDay, MONTH(s.date) as sMonth, YEAR(s.date) as sYear from App\Entity\UserStatistic s
                    index by s.id
                    where s.user = :user
                    and s.date >= :past
                    and s.date <= :future
                    group by sDay, sMonth, sYear"
                    )->setParameter(":user", $user->getId())
                    ->setParameter(":past", new \DateTime("-1 month"))
                    ->setParameter(":future", new \DateTime("+1 month"))
                        ->getArrayResult();

                    $ids = [];

                    foreach ($result as $item)
                        array_push($ids, $item["id"]);

                    $query = $this->entityManager->createQuery(
                        "select s.date, s.followerCount, s.followingCount, MONTH(s.date) as sMonth, YEAR(s.date) as sYear from App\Entity\UserStatistic s
                    where s.user = :user
                    and s.id in (:list)
                    order by s.date"
                    )->setParameter(":user", $user->getId())
                        ->setParameter(":list", $ids);
                }
                break;
            case "hour": {
                    $result = $this->entityManager->createQuery(
                        "select max(s.id) as id, HOUR(s.date) as sHour, DAY(s.date) as sDay, MONTH(s.date) as sMonth, YEAR(s.date) as sYear from App\Entity\UserStatistic s
                    index by s.id
                    where s.user = :user
                    and s.date >= :past
                    and s.date <= :future
                    group by sHour, sDay, sMonth, sYear"
                    )->setParameter(":user", $user->getId())
                    ->setParameter(":past", new \DateTime("-2 day"))
                    ->setParameter(":future", new \DateTime("+2 day"))
                        ->getArrayResult();

                    $ids = [];

                    foreach ($result as $item)
                        array_push($ids, $item["id"]);

                    $query = $this->entityManager->createQuery(
                        "select s.date, s.followerCount, s.followingCount from App\Entity\UserStatistic s
                    where s.user = :user
                    and s.id in (:list)
                    order by s.date"
                    )->setParameter(":user", $user->getId())
                        ->setParameter(":list", $ids);
                }
        }

        if (!$simplyfied)
            return $query->getArrayResult();
        else {
            $output = [];

            foreach ($query->getArrayResult() as $row) {
                array_push($output, [
                    "date" => date_timestamp_get($row["date"]),
                    "followerCount" => $row["followerCount"],
                    "followingCount" => $row["followingCount"]
                ]);
            }

            return $output;
        }
    }

    public function updateAnalytics(User $user)
    {
        $followerUserRelation = $this->entityManager->getRepository(UserRelation::class)->findOneBy(["user" => $user->getId(), "type" => "follower"]);
        $userAnalytics = $user->getAnalytics() ?? new UserAnalytics();

        $verifiedFollowers = 0;
        $protectedFollowers = 0;
        $mostStatuses = null;
        $mostFollowers = null;
        $oldestAccount = null;
        $statusCount = 0;
        $favoriteCount = 0;

        if ($followerUserRelation && $userAnalytics->getUpdated() < new \DateTime("-1 hour")) {
            $chunks = array_chunk(json_decode($followerUserRelation->getData()), 100);

            foreach ($chunks as $chunk) {
                $needUpdate = [];

                foreach ($chunk as $id)
                    $needUpdate[$id] = null;

                $cachedResult = $this->entityManager->getRepository(TwitterUser::class)->findBy([
                    "uuid" => $chunk
                ]);

                foreach ($cachedResult as $twitterUser) {
                    if ($twitterUser->getVerified()) $verifiedFollowers++;
                    if ($twitterUser->getProtected()) $protectedFollowers++;
                    if ($mostStatuses == null || $mostStatuses->getStatusesCount() < $twitterUser->getStatusesCount())
                        $mostStatuses = $twitterUser;
                    if ($mostFollowers == null || $mostFollowers->getFollowersCount() < $twitterUser->getFollowersCount())
                        $mostFollowers = $twitterUser;
                    if ($oldestAccount == null || $oldestAccount->getAccountCreatedAt() > $twitterUser->getAccountCreatedAt())
                        $oldestAccount = $twitterUser;
                        $statusCount += $twitterUser->getStatusesCount();
                        $favoriteCount += $twitterUser->getFavoritesCount();
                }
            }

            $userAnalytics->setVerifiedFollowers($verifiedFollowers);
            $userAnalytics->setProtectedFollowers($protectedFollowers);
            $userAnalytics->setMostStatuses($mostStatuses);
            $userAnalytics->setMostFollowers($mostFollowers);
            $userAnalytics->setOldestAccount($oldestAccount);
            $userAnalytics->setStatusCount($statusCount);
            $userAnalytics->setFavoriteCount($favoriteCount);
            $userAnalytics->setUpdated(new \DateTime());
            $userAnalytics->setUser($user);
            
            $this->entityManager->persist($userAnalytics);
            $this->entityManager->flush();
        }
    }
}
