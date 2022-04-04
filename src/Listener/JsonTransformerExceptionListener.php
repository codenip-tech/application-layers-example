<?php

declare(strict_types=1);

namespace App\Listener;

use App\Exception\Http\BadRequestHttpException;
use App\Exception\Http\EntityNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class JsonTransformerExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();

        $data = [
            'class' => \get_class($e),
            'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => $e->getMessage(),
        ];

        if (\in_array($data['class'], $this->getBadRequestException(), true)) {
            $data['code'] = Response::HTTP_BAD_REQUEST;
        }

        if ($e instanceof EntityNotFoundException) {
            $data['code'] = Response::HTTP_NOT_FOUND;
        }

        $event->setResponse($this->prepareResponse($data));
    }

    private function prepareResponse(array $data): Response
    {
        return new JsonResponse($data, $data['code']);
    }

    private function getBadRequestException(): array
    {
        return [
            BadRequestHttpException::class,
        ];
    }
}