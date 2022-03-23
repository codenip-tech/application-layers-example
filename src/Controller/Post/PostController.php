<?php

declare(strict_types=1);

namespace App\Controller\Post;

use App\Entity\Post;
use App\Exception\Http\BadRequestHttpException;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/posts')]
class PostController extends AbstractController
{
    #[Route('', name: 'posts_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();

        return $this->json(array_map(function (Post $post): array {
            return $post->toArray();
        }, $posts));
    }

    #[Route('/{id}', name: 'get_post_by_id', methods: ['GET'])]
    public function getPostById(Post $post): Response
    {
        return $this->json($post->toArray());
    }

    #[Route('', name: 'posts_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer, LoggerInterface $logger): Response
    {
        $data = \json_decode($request->getContent());

        if (!isset($data->author) || !isset($data->title) || !isset($data->content)) {
            throw BadRequestHttpException::create('Missing required parameters');
        }

        $post = new Post($data->author, $data->title, $data->content);

        $entityManager->persist($post);
        $entityManager->flush();

        $email = (new Email())
            ->from('admin@app.com')
            ->to('editors@app.com')
            ->subject('New post!')
            ->text('New post created')
            ->html('<p>Post author: ' . $post->author() . '</p>');

        try {
            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $logger->error(\sprintf('Error sending email. Message: %s', $e->getMessage()));
        }

        return $this->json($post->toArray(), Response::HTTP_CREATED);
    }
}