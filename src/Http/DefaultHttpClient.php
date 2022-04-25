<?php

declare(strict_types=1);

namespace App\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class DefaultHttpClient implements HttpClient
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function get(string $url, array $options = []): ResponseInterface
    {
        try {
            return $this->client->get($url, $options);
        } catch (GuzzleException $e) {
            // handle the exception
        }
    }

    public function post(string $url, array $options = []): ResponseInterface
    {
        try {
            return $this->client->post($url, $options);
        } catch (GuzzleException $e) {
            // handle the exception
        }
    }
}