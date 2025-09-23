<?php

namespace App\Pulsar;

class PulsarMessage
{
    public function __construct(
        public readonly string $payload,
        public readonly string $key,
    )
    {
    }
}
