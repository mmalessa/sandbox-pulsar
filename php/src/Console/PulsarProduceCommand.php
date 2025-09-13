<?php

declare(strict_types=1);

namespace App\Console;

use App\Pulsar\PulsarMessage;
use App\Pulsar\PulsarProducer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:pulsar-produce', description: 'Pulsar producer')]
class PulsarProduceCommand extends Command
{
    public function __construct(
        private readonly PulsarProducer $pulsarProducer,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $message = new PulsarMessage();
        $this->pulsarProducer->publish($message);
        return Command::SUCCESS;
    }
}
