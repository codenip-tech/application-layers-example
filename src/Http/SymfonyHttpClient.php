<?php

declare(strict_types=1);

namespace App\Http;

use Psr\Http\Message\ResponseInterface;

class SymfonyHttpClient implements HttpClient
{
    public function get(string $url, array $options = []): ResponseInterface
    {
        // TODO: Implement get() method.
    }

    public function post(string $url, array $options = []): ResponseInterface
    {
        // TODO: Implement post() method.
    }
}