<?php

declare(strict_types=1);

namespace App\Http;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class RequestTransformer
{
    public function transform(Request $request): void
    {
        $data = \json_decode($request->getContent(), true);

        if (!\is_null($data)) {
            $request->request = new ParameterBag($data);
        }
    }
}