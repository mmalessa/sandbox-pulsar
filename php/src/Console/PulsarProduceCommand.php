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
        $operations = [
            [ "walletId" => 101, "change" => 100, "success" => true ],
            [ "walletId" => 101, "change" => -10, "success" => false ],
//            [ "walletId" => 102, "change" => 200, "success" => true ],
//            [ "walletId" => 103, "change" => 50, "success" => true ],
            [ "walletId" => 101, "change" => 60, "success" => true ],
            [ "walletId" => 101, "change" => -100, "success" => true ],
//            [ "walletId" => 103, "change" => 51, "success" => true ],
//            [ "walletId" => 103, "change" => 52, "success" => true ],
            [ "walletId" => 101, "change" => 160, "success" => true ],
//            [ "walletId" => 102, "change" => -5, "success" => true ],
            [ "walletId" => 101, "change" => 11, "success" => true ],
            [ "walletId" => 101, "change" => 12, "success" => true ],
            [ "walletId" => 101, "change" => 13, "success" => true ],
            [ "walletId" => 101, "change" => 14, "success" => true ],
        ];

        foreach ($operations as $operation) {
            $message = new PulsarMessage(
                payload: json_encode($operation),
                key: (string) $operation['walletId'],
            );
            $this->pulsarProducer->publish($message);
        }

        return Command::SUCCESS;
    }
}
