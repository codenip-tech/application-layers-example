<?php

namespace App\Repository;

use App\Entity\Post;
use App\Exception\Http\EntityNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrinePostRepository implements PostRepository
{
    private ManagerRegistry $registry;
    private ServiceEntityRepository $repository;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
        $this->repository = new ServiceEntityRepository($registry, Post::class);
    }

    public function all(): array
    {
        return $this->repository->findAll();
    }

    public function findOneByIdOrFail(string $id): Post
    {
        if (null === $post = $this->repository->find($id)) {
            throw EntityNotFoundException::createFromIdAndClass($id, Post::class);
        }

        return $post;
    }

    public function save(Post $post): void
    {
        $this->registry->getManager()->persist($post);
        $this->registry->getManager()->flush();
    }
}
