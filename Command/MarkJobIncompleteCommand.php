<?php

namespace JMS\JobQueueBundle\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use JMS\JobQueueBundle\Entity\Job;
use JMS\JobQueueBundle\Entity\Repository\JobManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[\Symfony\Component\Console\Attribute\AsCommand(
    name: 'jms-job-queue:mark-incomplete',
    description: 'Internal command (do not use). It marks jobs as incomplete.',
)]
class MarkJobIncompleteCommand extends Command
{
    public function __construct(private readonly ManagerRegistry $registry, private readonly JobManager $jobManager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('job-id', InputArgument::REQUIRED, 'The ID of the Job.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var EntityManager $em */
        $em = $this->registry->getManagerForClass(Job::class);

        /** @var Job|null $job */
        $job = $em->createQuery("SELECT j FROM " . Job::class . " j WHERE j.id = :id")
            ->setParameter('id', $input->getArgument('job-id'))
            ->getOneOrNullResult();

        if ($job === null) {
            $output->writeln('<error>Job was not found.</error>');

            return 1;
        }

        $this->jobManager->closeJob($job, Job::STATE_INCOMPLETE);

        return Command::SUCCESS;
    }
}
