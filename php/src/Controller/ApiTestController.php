<?php

declare(strict_types=1);

namespace App\Controller;

use App\Pulsar\PulsarMessage;
use App\Pulsar\PulsarProducer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiTestController
{
    public function __construct(
        private readonly PulsarProducer $pulsarProducer
    ) {}

    #[Route('/api-test')]
    public function index(Request $request): Response
    {
        $timeStamp = microtime(true);

        $body = $request->getContent();

        $message = new PulsarMessage(
            payload: $body,
        );

        $this->pulsarProducer->publish($message);

        $response = [
            'status' => Response::HTTP_OK,
            'timestamp' => $timeStamp,
            'hostname' => $_SERVER['HOSTNAME'],
            'content' => json_encode($body)
        ];
        return new JsonResponse($response);
    }
}
