<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller\Post;

use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class PostControllerTest extends WebTestCase
{
    use ReloadDatabaseTrait;

    private const ENDPOINT = '/posts';

    public function testCreatePost(): void
    {
        $payload = [
            'id' => Uuid::v4()->toRfc4122(),
            'title' => 'Title',
            'content' => 'Some text',
            'creator' => '72b1c6b5-967b-4793-8695-f06c8e9e559f',
        ];

        $client = static::createClient();

        $client->request(Request::METHOD_POST, self::ENDPOINT, [], [], [], json_encode($payload));

        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        self::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        self::assertEquals($payload['title'], $responseData['title']);
        self::assertEquals($payload['content'], $responseData['content']);
        self::assertEquals($payload['creator'], $responseData['creatorId']);
    }
}