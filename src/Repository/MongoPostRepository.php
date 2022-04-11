<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Post;

class MongoPostRepository implements PostRepository
{
    public function all(): array
    {
        // TODO: Implement all() method.
    }

    public function findOneByIdOrFail(string $id): Post
    {
        // TODO: Implement findOneByIdOrFail() method.
    }

    public function save(Post $post): void
    {
        // TODO: Implement save() method.
    }
}