<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller\Post;

use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostControllerTest extends WebTestCase
{
    use ReloadDatabaseTrait;

    private const ENDPOINT = '/posts';

    public function testCreatePost(): void
    {
        $payload = [
            'author' => 'Peter',
            'title' => 'Title',
            'content' => 'Some text',
        ];

        $client = static::createClient();

        $client->request(Request::METHOD_POST, self::ENDPOINT, [], [], [], json_encode($payload));

        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        self::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        self::assertEquals($payload['author'], $responseData['author']);
        self::assertEquals($payload['title'], $responseData['title']);
        self::assertEquals($payload['content'], $responseData['content']);
    }

    public function testGetPosts(): void
    {
        $payload = [
            'author' => 'Peter',
            'title' => 'Title',
            'content' => 'Some text',
            'creator' => '72b1c6b5-967b-4793-8695-f06c8e9e559f',
        ];

        $client = static::createClient();

        $client->request(Request::METHOD_POST, self::ENDPOINT, [], [], [], json_encode($payload));

        $client->request(Request::METHOD_GET, self::ENDPOINT);

        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertCount(1, $responseData);
    }

    public function testGetPostById(): void
    {
        $payload = [
            'author' => 'Peter',
            'title' => 'Title',
            'content' => 'Some text',
            'creator' => '72b1c6b5-967b-4793-8695-f06c8e9e559f',
        ];

        $client = static::createClient();

        $client->request(Request::METHOD_POST, self::ENDPOINT, [], [], [], json_encode($payload));

        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        $postId = $responseData['id'];

        $client->request(Request::METHOD_GET, \sprintf('%s/%s', self::ENDPOINT, $postId));

        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertEquals($payload['author'], $responseData['author']);
        self::assertEquals($payload['title'], $responseData['title']);
        self::assertEquals($payload['content'], $responseData['content']);
    }
}