<?php

declare(strict_types=1);

namespace App\Pulsar;

use Pulsar\Message;

class PulsarTestHandler implements PulsarHandlerInterface
{
    public function Handle(Message $message)
    {
//        var_export($message->getProperties());
        echo sprintf('Got message 【%s】 properties[%s] messageID[%s] redeliveryCount[%d]',
                $message->getPayload(),
                json_encode($message->getProperties()),
                $message->getMessageId(),
                $message->getRedeliveryCount(),
            ) . "\n";

        $payload = $message->getPayload();
        $payloadArray = json_decode($payload, true);

        $success = $payloadArray['success'] ?? true;
        if (!$success) {
            throw new \Exception('Got message failed');
        }


    }
}
