<?php

declare(strict_types=1);

namespace App\Controller\Post;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route('/posts')]
class PostController extends AbstractController
{
    #[Route('', name: 'posts_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();

        return $this->json($posts);
    }

    #[Route('/{id}', name: 'get_post_by_id', methods: ['GET'])]
    public function getPostById(Post $post): Response
    {
        return $this->json($post->toArray());
    }

    #[Route('', name: 'posts_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $data = \json_decode($request->getContent());

        if (!isset($data->title) || !isset($data->content) || !isset($data->creator)) {
            return $this->json([
                'class' => BadRequestHttpException::class,
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => 'Missing required parameters',
            ], Response::HTTP_BAD_REQUEST);
        }

        $post = new Post(Uuid::v4()->toRfc4122(), $data->title, $data->content, $userRepository->find($data->creator));

        $entityManager->persist($post);
        $entityManager->flush();

        return $this->json($post->toArray(), Response::HTTP_CREATED);
    }
}