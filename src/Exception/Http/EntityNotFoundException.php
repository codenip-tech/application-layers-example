<?php

declare(strict_types=1);

namespace App\Exception\Http;

class EntityNotFoundException extends \DomainException
{
    public static function createFromIdAndClass(string $id, string $class): self
    {
        return new self(\sprintf('Entity with id [%s] for class [%s] not found', $id, $class));
    }
}