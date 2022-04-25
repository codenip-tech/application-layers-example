<?php

declare(strict_types=1);

namespace App\Http\DTO;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class CreatePostRequest implements RequestDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    public readonly ?string $author;

    #[Assert\NotBlank]
    public readonly ?string $title;

    #[Assert\NotBlank]
    public readonly ?string $content;

    public function __construct(Request $request)
    {
        $this->author = $request->request->get('author');
        $this->title = $request->request->get('title');
        $this->content = $request->request->get('content');
    }
}