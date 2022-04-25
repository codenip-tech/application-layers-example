<?php

declare(strict_types=1);

namespace App\Controller\Post;

use App\Controller\ApiController;
use App\Entity\Post;
use App\Exception\Http\BadRequestHttpException;
use App\Http\DTO\CreatePostRequest;
use App\Http\DTO\PostIdRequest;
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

    public function getPostById(PostIdRequest $request): Response
    {
        $post = $this->service->findPostById($request->id);

        return $this->createResponse($post->toArray());
    }

    public function create(CreatePostRequest $request): Response
    {
        $post = $this->service->createPost($request->author, $request->title, $request->content);

        return $this->createResponse($post->toArray(), Response::HTTP_CREATED);
    }
}