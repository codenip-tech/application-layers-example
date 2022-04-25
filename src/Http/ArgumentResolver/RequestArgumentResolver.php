<?php

declare(strict_types=1);

namespace App\Http\ArgumentResolver;

use App\Exception\Http\BadRequestHttpException;
use App\Http\DTO\RequestDTO;
use App\Http\RequestTransformer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestArgumentResolver implements ArgumentValueResolverInterface
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly RequestTransformer $requestTransformer
    )
    {
    }

    public function supports(Request $request, ArgumentMetadata $argument)
    {
        $reflectionClass = new \ReflectionClass($argument->getType());
        if ($reflectionClass->implementsInterface(RequestDTO::class)) {
            return true;
        }

        return false;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): \Generator
    {
        $this->requestTransformer->transform($request);

        $class = $argument->getType();
        $dto = new $class($request);

        $errors = $this->validator->validate($dto);
        if (\count($errors) > 0) {
            throw BadRequestHttpException::create((string) $errors);
        }

        yield $dto;
    }
}