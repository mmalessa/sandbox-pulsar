<?php

declare(strict_types=1);

namespace App\Pulsar;

use Pulsar\Message;

class PulsarTestHandler implements PulsarHandlerInterface
{
    public function Handle(Message $message)
    {
//        var_export($message->getProperties());
        echo sprintf('Got message 【%s】messageID[%s] topic[%s] publishTime[%s] redeliveryCount[%d]',
                $message->getPayload(),
                $message->getMessageId(),
                $message->getTopic(),
                $message->getPublishTime(),
                $message->getRedeliveryCount()
            ) . "\n";

        $payload = $message->getPayload();
        $payloadArray = json_decode($payload, true);
        $success = $payloadArray['success'];

        if (!$success) {
            throw new \Exception('Got message failed');
        }


    }
}
