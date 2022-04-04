<?php

declare(strict_types=1);

namespace App\Http\Response;

class ApiResponse
{
    public function __construct(
        private readonly mixed $data,
        private readonly int $code
    )
    {
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function getCode(): int
    {
        return $this->code;
    }
}