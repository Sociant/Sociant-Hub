<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $uuid;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $twitterUserScreenName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $twitterUserOauthToken;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $twitterUserOauthTokenSecret;

    /**
     * @ORM\OneToMany(targetEntity=UserRelation::class, mappedBy="user", orphanRemoval=true)
     */
    private $userRelations;

    /**
     * @ORM\OneToMany(targetEntity=UserAction::class, mappedBy="user", orphanRemoval=true)
     */
    private $userActions;

    /**
     * @ORM\ManyToOne(targetEntity=TwitterUser::class)
     */
    private $twitterUser;

    /**
     * @ORM\OneToMany(targetEntity=UserStatistic::class, mappedBy="user", orphanRemoval=true)
     */
    private $statistics;

    /**
     * @ORM\OneToOne(targetEntity=UserAnalytics::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $analytics;

    /**
     * @ORM\OneToOne(targetEntity=AutomatedUpdate::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $automatedUpdate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $setupCompleted = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $apiToken;

    /**
     * @ORM\OneToMany(targetEntity=ApiNotification::class, mappedBy="user", orphanRemoval=true)
     */
    private $apiNotifications;

    /**
     * @ORM\Column(type="boolean")
     */
    private $aboveFollowerLimit = false;

    public function __construct()
    {
        $this->userRelations = new ArrayCollection();
        $this->userActions = new ArrayCollection();
        $this->statistics = new ArrayCollection();
        $this->apiNotifications = new ArrayCollection();
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->uuid;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword()
    {
        // not needed for apps that do not check user passwords
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed for apps that do not check user passwords
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getTwitterUserScreenName(): ?string
    {
        return $this->twitterUserScreenName;
    }

    public function setTwitterUserScreenName(string $twitterUserScreenName): self
    {
        $this->twitterUserScreenName = $twitterUserScreenName;

        return $this;
    }

    public function getTwitterUserOauthToken(): ?string
    {
        return $this->twitterUserOauthToken;
    }

    public function setTwitterUserOauthToken(string $twitterUserOauthToken): self
    {
        $this->twitterUserOauthToken = $twitterUserOauthToken;

        return $this;
    }

    public function getTwitterUserOauthTokenSecret(): ?string
    {
        return $this->twitterUserOauthTokenSecret;
    }

    public function setTwitterUserOauthTokenSecret(string $twitterUserOauthTokenSecret): self
    {
        $this->twitterUserOauthTokenSecret = $twitterUserOauthTokenSecret;

        return $this;
    }

    /**
     * @return Collection|UserRelation[]
     */
    public function getUserRelations(): Collection
    {
        return $this->userRelations;
    }

    public function addUserRelation(UserRelation $userRelation): self
    {
        if (!$this->userRelations->contains($userRelation)) {
            $this->userRelations[] = $userRelation;
            $userRelation->setUser($this);
        }

        return $this;
    }

    public function removeUserRelation(UserRelation $userRelation): self
    {
        if ($this->userRelations->contains($userRelation)) {
            $this->userRelations->removeElement($userRelation);
            // set the owning side to null (unless already changed)
            if ($userRelation->getUser() === $this) {
                $userRelation->setUser(null);
            }
        }

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
            $userAction->setUser($this);
        }

        return $this;
    }

    public function removeUserAction(UserAction $userAction): self
    {
        if ($this->userActions->contains($userAction)) {
            $this->userActions->removeElement($userAction);
            // set the owning side to null (unless already changed)
            if ($userAction->getUser() === $this) {
                $userAction->setUser(null);
            }
        }

        return $this;
    }

    public function getTwitterUser(): ?TwitterUser
    {
        return $this->twitterUser;
    }

    public function setTwitterUser(?TwitterUser $twitterUser): self
    {
        $this->twitterUser = $twitterUser;

        return $this;
    }

    /**
     * @return Collection|UserStatistic[]
     */
    public function getStatistics(): Collection
    {
        return $this->statistics;
    }

    public function addStatistic(UserStatistic $statistic): self
    {
        if (!$this->statistics->contains($statistic)) {
            $this->statistics[] = $statistic;
            $statistic->setUser($this);
        }

        return $this;
    }

    public function removeStatistic(UserStatistic $statistic): self
    {
        if ($this->statistics->contains($statistic)) {
            $this->statistics->removeElement($statistic);
            // set the owning side to null (unless already changed)
            if ($statistic->getUser() === $this) {
                $statistic->setUser(null);
            }
        }

        return $this;
    }

    public function getAnalytics(): ?UserAnalytics
    {
        return $this->analytics;
    }

    public function setAnalytics(UserAnalytics $analytics): self
    {
        $this->analytics = $analytics;

        // set the owning side of the relation if necessary
        if ($analytics->getUser() !== $this) {
            $analytics->setUser($this);
        }

        return $this;
    }

    public function getAutomatedUpdate(): ?AutomatedUpdate
    {
        return $this->automatedUpdate;
    }

    public function setAutomatedUpdate(AutomatedUpdate $automatedUpdate): self
    {
        $this->automatedUpdate = $automatedUpdate;

        // set the owning side of the relation if necessary
        if ($automatedUpdate->getUser() !== $this) {
            $automatedUpdate->setUser($this);
        }

        return $this;
    }

    public function getSetupCompleted(): ?bool
    {
        return $this->setupCompleted;
    }

    public function setSetupCompleted(bool $setupCompleted): self
    {
        $this->setupCompleted = $setupCompleted;

        return $this;
    }

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(?string $apiToken): self
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    /**
     * @return Collection|ApiNotification[]
     */
    public function getApiNotifications(): Collection
    {
        return $this->apiNotifications;
    }

    public function addApiNotification(ApiNotification $apiNotification): self
    {
        if (!$this->apiNotifications->contains($apiNotification)) {
            $this->apiNotifications[] = $apiNotification;
            $apiNotification->setUser($this);
        }

        return $this;
    }

    public function removeApiNotification(ApiNotification $apiNotification): self
    {
        if ($this->apiNotifications->contains($apiNotification)) {
            $this->apiNotifications->removeElement($apiNotification);
            // set the owning side to null (unless already changed)
            if ($apiNotification->getUser() === $this) {
                $apiNotification->setUser(null);
            }
        }

        return $this;
    }

    public function getAboveFollowerLimit(): ?bool
    {
        return $this->aboveFollowerLimit;
    }

    public function setAboveFollowerLimit(bool $aboveFollowerLimit): self
    {
        $this->aboveFollowerLimit = $aboveFollowerLimit;

        return $this;
    }
}
