<?php

declare(strict_types=1);

namespace App\Service\Post;

use App\Entity\Post;
use App\Messenger\Message\PostCreated;
use App\Repository\PostRepository;
use Symfony\Component\Messenger\MessageBusInterface;

class PostService
{
    public function __construct(
        private readonly PostRepository $repository,
        private readonly MessageBusInterface $bus
    )
    {
    }

    public function getAllPosts(): array
    {
        return $this->repository->all();
    }

    public function findPostById(string $id): Post
    {
        return $this->repository->findOneByIdOrFail($id);
    }

    public function createPost(string $author, string $title, string $content): Post
    {
        $post = Post::create($author, $title, $content);

        $this->repository->save($post);

        $this->bus->dispatch(new PostCreated($post->author()));

        return $post;
    }
}