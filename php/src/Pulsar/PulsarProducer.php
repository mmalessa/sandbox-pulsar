<?php

namespace App\Pulsar;

use Pulsar\Compression\Compression;
use Pulsar\MessageOptions;
use Pulsar\Producer;
use Pulsar\ProducerOptions;

class PulsarProducer
{
    private $producer;

    public function __construct(string $uri, string $topic)
    {
        $options = new ProducerOptions();
        $options->setConnectTimeout(3);
        $options->setTopic($topic);
        $options->setCompression(Compression::ZLIB);
        $this->producer = new Producer($uri, $options);
        $this->producer->connect();
    }

    public function publish(PulsarMessage $message)
    {
        $this->producer->send(
            payload: $message->payload,
            options: [
                MessageOptions::KEY => rand(0,100),
                MessageOptions::PROPERTIES => ['ms' => microtime(true), 'prop_key' => rand(0,100)]
            ],
        );
    }
}
