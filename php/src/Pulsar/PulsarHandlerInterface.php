<?php

declare(strict_types=1);

namespace App\Pulsar;

use Pulsar\Message;

interface PulsarHandlerInterface
{
    public function Handle(Message $message);
}
