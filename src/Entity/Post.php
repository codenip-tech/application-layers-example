<?php

namespace App\Entity;

use Symfony\Component\Uid\Uuid;

class Post
{
    private function __construct(
        private readonly string $id,
        private readonly string $author,
        private string $title,
        private string $content,
        private readonly \DateTimeInterface $createdOn
    )
    {
    }

    public static function create(string $author, string $title, string $content): self
    {
        return new self(
            Uuid::v4()->toRfc4122(),
            $author,
            $title,
            $content,
            new \DateTimeImmutable()
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function author(): string
    {
        return $this->author;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function createdOn(): ?\DateTimeInterface
    {
        return $this->createdOn;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'author' => $this->author,
            'title' => $this->title,
            'content' => $this->content,
            'createdOn' => $this->createdOn->format(\DateTimeInterface::RFC3339),
        ];
    }
}
