<?php

declare(strict_types=1);

namespace App\Pulsar;

use Pulsar\Consumer;
use Pulsar\ConsumerOptions;
use Pulsar\SubscriptionType;

class PulsarConsumer
{
    private Consumer $consumer;

    public function __construct(
        private readonly string $uri,
        private readonly string $topic,
        private readonly string $subscriptionName,
    )
    {
        $options = new ConsumerOptions();
        $options->setConnectTimeout(3);
        $options->setTopic($this->topic);
        $options->setSubscription($this->subscriptionName);
        $options->setSubscriptionType(SubscriptionType::Key_Shared);


        $options->setNackRedeliveryDelay(10);

        $this->consumer = new Consumer($this->uri, $options);
        $this->consumer->connect();
    }

    public function __destruct()
    {
        $this->consumer->close();
    }

    public function Consume(PulsarHandlerInterface $handler):void
    {
        echo "Consume\n";
        $seq = 0;
        while (true) {
            $message = $this->consumer->receive();
            echo ++$seq . "\t";
            try {
                $handler->handle($message);
                $this->consumer->ack($message);
            } catch (\Exception $e) {
                echo "ERROR: ".$e->getMessage()."\n";
                $this->consumer->nack($message);
            }
        }
    }
}
