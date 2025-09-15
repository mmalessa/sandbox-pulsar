<?php

declare(strict_types=1);

namespace App\Console;

use App\Pulsar\PulsarConsumer;
use App\Pulsar\PulsarTestHandler;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:pulsar-consume', description: 'Pulsar Consume')]
class PulsarConsumeCommand extends Command
{
    public function __construct(
        private readonly PulsarConsumer $pulsarConsumer,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $handler = new PulsarTestHandler();

        $this->pulsarConsumer->consume($handler);

        return Command::SUCCESS;
    }
}
