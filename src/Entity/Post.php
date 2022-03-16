<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\Index(columns: ['creator_id'], name: 'IDX_post_creator')]
#[ORM\Index(columns: ['title'], name: 'IDX_post_title')]
class Post
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', columnDefinition: 'CHAR(36) NOT NULL')]
    private string $id;

    #[ORM\Column(type: 'string', length: 50)]
    private string $title;

    #[ORM\Column(type: 'string', length: 255)]
    private string $content;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private User $creator;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $createdOn;

    public function __construct(string $title, string $content, User $creator)
    {
        $this->id = Uuid::v4()->toRfc4122();
        $this->title = $title;
        $this->content = $content;
        $this->creator = $creator;
        $this->createdOn = new \DateTime();
    }

    public function id(): string
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(User $creator): ?User
    {
        return $this->creator = $creator;
    }

    public function getCreatedOn(): ?\DateTimeInterface
    {
        return $this->createdOn;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->content,
            'creatorId' => $this->creator->id(),
            'createdOn' => $this->createdOn->format(\DateTimeInterface::RFC3339),
        ];
    }
}
