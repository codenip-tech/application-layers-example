<?php

declare(strict_types=1);

namespace App\Http;

use Psr\Http\Message\ResponseInterface;

interface HttpClient
{
    public function get(string $url, array $options = []): ResponseInterface;
    public function post(string $url, array $options = []): ResponseInterface;
}