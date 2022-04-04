<?php

declare(strict_types=1);

namespace App\Service\Post;

use App\Entity\Post;
use App\Repository\PostRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class PostService
{
    public function __construct(
        private readonly PostRepository $repository,
        private readonly MailerInterface $mailer,
        private readonly LoggerInterface $logger
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

        $this->sendEmail($post->author());

        return $post;
    }

    private function sendEmail(string $author): void
    {
        $email = (new Email())
            ->from('admin@app.com')
            ->to('editors@app.com')
            ->subject('New post!')
            ->text('New post created')
            ->html('<p>Post author: ' . $author . '</p>');

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error(\sprintf('Error sending email. Message: %s', $e->getMessage()));
        }
    }
}