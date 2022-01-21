<?php

namespace App\Entity;

use App\Repository\TwitterUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TwitterUserRepository::class)
 */
class TwitterUser
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
    private $uuid;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $screenName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $location;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="boolean")
     */
    private $protected;

    /**
     * @ORM\Column(type="integer")
     */
    private $followersCount;

    /**
     * @ORM\Column(type="integer")
     */
    private $friendsCount;

    /**
     * @ORM\Column(type="integer")
     */
    private $listedCount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $accountCreatedAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $favoritesCount;

    /**
     * @ORM\Column(type="boolean")
     */
    private $verified;

    /**
     * @ORM\Column(type="integer")
     */
    private $statusesCount;

    /**
     * @ORM\Column(type="boolean")
     */
    private $translator;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $profileImageURL;

    /**
     * @ORM\OneToMany(targetEntity=UserAction::class, mappedBy="twitterUser")
     */
    private $userActions;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lastUpdated;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hasNftAvatar = false;

    public function __construct()
    {
        $this->userActions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getScreenName(): ?string
    {
        return $this->screenName;
    }

    public function setScreenName(string $screenName): self
    {
        $this->screenName = $screenName;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getProtected(): ?bool
    {
        return $this->protected;
    }

    public function setProtected(bool $protected): self
    {
        $this->protected = $protected;

        return $this;
    }

    public function getFollowersCount(): ?int
    {
        return $this->followersCount;
    }

    public function setFollowersCount(int $followersCount): self
    {
        $this->followersCount = $followersCount;

        return $this;
    }

    public function getFriendsCount(): ?int
    {
        return $this->friendsCount;
    }

    public function setFriendsCount(int $friendsCount): self
    {
        $this->friendsCount = $friendsCount;

        return $this;
    }

    public function getListedCount(): ?int
    {
        return $this->listedCount;
    }

    public function setListedCount(int $listedCount): self
    {
        $this->listedCount = $listedCount;

        return $this;
    }

    public function getAccountCreatedAt(): ?\DateTimeInterface
    {
        return $this->accountCreatedAt;
    }

    public function setAccountCreatedAt(\DateTimeInterface $accountCreatedAt): self
    {
        $this->accountCreatedAt = $accountCreatedAt;

        return $this;
    }

    public function getFavoritesCount(): ?int
    {
        return $this->favoritesCount;
    }

    public function setFavoritesCount(int $favoritesCount): self
    {
        $this->favoritesCount = $favoritesCount;

        return $this;
    }

    public function getVerified(): ?bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): self
    {
        $this->verified = $verified;

        return $this;
    }

    public function getStatusesCount(): ?int
    {
        return $this->statusesCount;
    }

    public function setStatusesCount(int $statusesCount): self
    {
        $this->statusesCount = $statusesCount;

        return $this;
    }

    public function getTranslator(): ?bool
    {
        return $this->translator;
    }

    public function setTranslator(bool $translator): self
    {
        $this->translator = $translator;

        return $this;
    }

    public function getProfileImageURL(): ?string
    {
        return $this->profileImageURL;
    }

    public function setProfileImageURL(string $profileImageURL): self
    {
        $this->profileImageURL = $profileImageURL;

        return $this;
    }

    /**
     * @return Collection|UserAction[]
     */
    public function getUserActions(): Collection
    {
        return $this->userActions;
    }

    public function addUserAction(UserAction $userAction): self
    {
        if (!$this->userActions->contains($userAction)) {
            $this->userActions[] = $userAction;
            $userAction->setTwitterUser($this);
        }

        return $this;
    }

    public function removeUserAction(UserAction $userAction): self
    {
        if ($this->userActions->contains($userAction)) {
            $this->userActions->removeElement($userAction);
            // set the owning side to null (unless already changed)
            if ($userAction->getTwitterUser() === $this) {
                $userAction->setTwitterUser(null);
            }
        }

        return $this;
    }

    public function getAge() {
        $difference = (new \DateTime())->diff($this->getAccountCreatedAt());
        return $difference;
    }

    public function getLastUpdated(): ?\DateTimeInterface
    {
        return $this->lastUpdated;
    }

    public function setLastUpdated(\DateTimeInterface $lastUpdated): self
    {
        $this->lastUpdated = $lastUpdated;

        return $this;
    }

    public function getHasNftAvatar(): ?bool
    {
        return $this->hasNftAvatar;
    }

    public function setHasNftAvatar(bool $hasNftAvatar): self
    {
        $this->hasNftAvatar = $hasNftAvatar;

        return $this;
    }
}
