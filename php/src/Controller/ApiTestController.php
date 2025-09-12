<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiTestController
{
    #[Route('/api-test')]
    public function index(): Response
    {
        $timeStamp = microtime(true);

        $response = [
            'status' => Response::HTTP_OK,
            'timestamp' => $timeStamp,
            'hostname' => $_SERVER['HOSTNAME'],
        ];
        return new JsonResponse($response);
    }
}
