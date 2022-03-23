<?php

declare(strict_types=1);

namespace App\Controller\Post;

use App\Entity\Post;
use App\Exception\Http\BadRequestHttpException;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class PostController
{
    public function __construct(
        private readonly PostRepository $postRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly MailerInterface $mailer,
        private readonly LoggerInterface $logger
    )
    {
    }

    public function index(): Response
    {
        $posts = $this->postRepository->findAll();

        return new JsonResponse(array_map(function (Post $post): array {
            return $post->toArray();
        }, $posts));
    }

    public function getPostById(string $id): Response
    {
        $post = $this->postRepository->find($id);

        return new JsonResponse($post->toArray());
    }

    public function create(Request $request): Response
    {
        $data = \json_decode($request->getContent());

        if (!isset($data->author) || !isset($data->title) || !isset($data->content)) {
            throw BadRequestHttpException::create('Missing required parameters');
        }

        $post = new Post($data->author, $data->title, $data->content);

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        $email = (new Email())
            ->from('admin@app.com')
            ->to('editors@app.com')
            ->subject('New post!')
            ->text('New post created')
            ->html('<p>Post author: ' . $post->author() . '</p>');

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error(\sprintf('Error sending email. Message: %s', $e->getMessage()));
        }

        return new JsonResponse($post->toArray(), Response::HTTP_CREATED);
    }
}