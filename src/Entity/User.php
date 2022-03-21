<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'U_user_email', columns: ['email'])]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', columnDefinition: 'CHAR(36) NOT NULL')]
    private string $id;

    #[ORM\Column(type: 'string', length: 50)]
    private string $email;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $createdOn;

    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: Post::class, orphanRemoval: true)]
    private Collection $posts;

    public function __construct(string $id, string $email)
    {
        $this->id = $id;
        $this->setEmail($email);
        $this->createdOn = new \DateTime();
        $this->posts = new ArrayCollection();
    }

    public function id(): string
    {
        return $this->id;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \LogicException(\sprintf('[%s] is not a valid email', $email));
        }

        $this->email = $email;
    }

    public function getCreatedOn(): \DateTimeInterface
    {
        return $this->createdOn;
    }

    /**
     * @return Collection<Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): void
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setCreator($this);
        }
    }

    public function removePost(Post $post): void
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'createdOn' => $this->createdOn->format(\DateTimeInterface::RFC3339),
            'posts' => array_map(function (Post $post): string {
                return $post->id();
            }, $this->posts->toArray())
        ];
    }
}
