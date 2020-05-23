<?php

namespace App\Command;

use App\Model\TwitterModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateUserCommand extends Command
{

    private $entityManager;
    private $twitterModel;

    public function __construct(EntityManagerInterface $entityManager, TwitterModel $twitterModel)
    {
        $this->entityManager = $entityManager;
        $this->twitterModel = $twitterModel;
    
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
            "select a from App\Entity\AutomatedUpdate a
            where a.nextUpdate <= :now
            and a.lastUpdate < :check"
        )
        ->setParameter("now",new \DateTime())
        ->setParameter("check",new \DateTime("-59 minutes"))
        ->getResult();

        $now = new \DateTime();

        if(sizeof($automatedUpdates) > 0) {
            $output->writeln("Updating ".sizeof($automatedUpdates)." users");

            foreach($automatedUpdates as $update) {
                $this->twitterModel->fetchUserData($update->getUser());
            }

            $output->writeln("Finished updating ".sizeof($automatedUpdates)." users after ".(new \DateTime())->diff($now)->s." seconds");
        } else $output->writeln("No updates required");

        return 0;
    }
}