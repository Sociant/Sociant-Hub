<?php

namespace App\Command;

use App\Model\SpotifyModel;
use App\Model\TwitterModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateUserCommand extends Command
{

    private $entityManager;
    private $twitterModel;
    private $spotifyModel;

    public function __construct(EntityManagerInterface $entityManager, TwitterModel $twitterModel, SpotifyModel $spotifyModel)
    {
        $this->entityManager = $entityManager;
        $this->twitterModel = $twitterModel;
        $this->spotifyModel = $spotifyModel;
    
        parent::__construct();
    }

    protected static $defaultName = 'app:update-user';

    protected function configure()
    {
        $this
            ->setDescription('Updates all users.')
            ->setHelp('This command updates all users which haven\'t updated their data within 1 hour...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $automatedUpdates = $this->entityManager->createQuery(
            "select a,u from App\Entity\AutomatedUpdate a
            left join a.user u
            where (u.uuid is not null and a.nextUpdate <= :now
            and a.lastUpdate < :checkTwitter) or
            (u.spotifyID is not null and a.nextUpdate <= :now
            and a.lastUpdate < :checkSpotify)"
        )
        ->setParameter("now",new \DateTime())
        ->setParameter("checkTwitter",new \DateTime("-59 minutes"))
        ->setParameter("checkSpotify",new \DateTime("-14 minutes"))
        ->getResult();

        $now = new \DateTime();

        if(sizeof($automatedUpdates) > 0) {
            $output->writeln("Updating ".sizeof($automatedUpdates)." spotify and twitter users");

            foreach($automatedUpdates as $update) {
                $user = $update->getUser();

                switch($user->getType()) {
                    case "twitter": $this->twitterModel->fetchUserData($user); break;
                    case "spotify": $this->spotifyModel->updateRecentlyPlayed($user,50); break;
                }
            }

            $output->writeln("Finished updating ".sizeof($automatedUpdates)." users after ".(new \DateTime())->diff($now)->s." seconds");
        } else $output->writeln("No updates required");

        return 0;
    }
}