<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Post;

interface PostRepository
{
    public function all(): array;
    public function findOneByIdOrFail(string $id): Post;
    public function save(Post $post): void;
}