<?php

declare(strict_types=1);

namespace App\Exception\Http;

class BadRequestHttpException extends \DomainException
{
    public static function create(string $message): self
    {
        return new self($message);
    }
}