<?php

namespace JMS\JobQueueBundle\Tests\Functional\TestBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SuccessfulCommand extends Command
{
    protected static $defaultName = 'jms-job-queue:successful-cmd';
    protected function configure()
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return Command::SUCCESS;
    }
}
