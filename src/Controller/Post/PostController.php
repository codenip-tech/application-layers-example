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
use Symfony\Component\Routing\Annotation\Route;

#[Route('/posts')]
class PostController extends AbstractController
{
    #[Route('', name: 'posts_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();

        return $this->json($posts);
    }

    #[Route('', name: 'posts_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $data = \json_decode($request->getContent());

        $post = new Post($data->title, $data->content, $userRepository->find($data->creator));

        $entityManager->persist($post);
        $entityManager->flush();

        return $this->json($post->toArray(), Response::HTTP_CREATED);
    }
}