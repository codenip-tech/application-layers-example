<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiController
{
    public function createResponse($data, int $code = Response::HTTP_OK): Response
    {
        return new JsonResponse($data, $code);
    }
}