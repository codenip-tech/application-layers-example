<?php

declare(strict_types=1);

namespace App\Messenger\Handler;

use App\Messenger\Message\PostCreated;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Email;

class PostCreatedHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly MailerInterface $mailer
    )
    {
    }

    public function __invoke(PostCreated $message): void
    {
        $this->logger->info(\sprintf('[MESSENGER] - Author: %s', $message->author));

        $email = (new Email())
            ->from('admin@app.com')
            ->to('editors@app.com')
            ->subject('New post!')
            ->text('New post created')
            ->html('<p>Post author: ' . $message->author . '</p>');

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error(\sprintf('Error sending email. Message: %s', $e->getMessage()));
        }
    }
}