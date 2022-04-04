<?php

declare(strict_types=1);

namespace App\Controller\Post;

use App\Controller\ApiController;
use App\Entity\Post;
use App\Exception\Http\BadRequestHttpException;
use App\Service\Post\PostService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostController extends ApiController
{
    public function __construct(private readonly PostService $service)
    {
    }

    public function index(): Response
    {
        $posts = $this->service->getAllPosts();

        return $this->createResponse(\array_map(function (Post $post): array {
            return $post->toArray();
        }, $posts));
    }

    public function getPostById(string $id): Response
    {
        $post = $this->service->findPostById($id);

        return $this->createResponse($post->toArray());
    }

    public function create(Request $request): Response
    {
        $data = \json_decode($request->getContent());

        if (!isset($data->author) || !isset($data->title) || !isset($data->content)) {
            throw BadRequestHttpException::create('Missing required parameters');
        }

        $post = $this->service->createPost($data->author, $data->title, $data->content);

        return $this->createResponse($post->toArray(), Response::HTTP_CREATED);
    }
}