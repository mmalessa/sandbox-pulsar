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

    public function __destruct()
    {
        $this->producer->close();
    }

    public function publish(PulsarMessage $message)
    {
        printf(
            "Publish: [%s] with key [%s]\n",
            $message->payload,
            $message->key,
        );
        $this->producer->send(
            payload: $message->payload,
            options: [
                MessageOptions::KEY => $message->key,
                MessageOptions::PROPERTIES => ['ms' => microtime(true)]
            ],
        );
    }
}
