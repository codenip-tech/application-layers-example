<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\Index(columns: ['title'], name: 'IDX_post_title')]
class Post
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', columnDefinition: 'CHAR(36) NOT NULL')]
    private string $id;

    #[ORM\Column(type: 'string', length: 50)]
    private string $author;

    #[ORM\Column(type: 'string', length: 50)]
    private string $title;

    #[ORM\Column(type: 'string', length: 255)]
    private string $content;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $createdOn;

    public function __construct(string $author, string $title, string $content)
    {
        $this->id = Uuid::v4()->toRfc4122();
        $this->author = $author;
        $this->title = $title;
        $this->content = $content;
        $this->createdOn = new \DateTime();
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
