<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route('/users')]
class UserController extends AbstractController
{
    #[Route('', name: 'user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->json(array_map(function (User $user): array {
            return $user->toArray();
        }, $users));
    }

    #[Route('/{id}', name: 'user_get_by_id', methods: ['GET'])]
    public function getById(User $user): Response
    {
        return $this->json($user->toArray());
    }

    #[Route('', name: 'user_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager,): Response
    {
        try {
            $data = \json_decode($request->getContent());

            $user = new User(Uuid::v4()->toRfc4122(), $data->email);

            $entityManager->persist($user);
            $entityManager->flush();
        } catch (\Exception $e) {
            if ($e instanceof UniqueConstraintViolationException) {
                return $this->json(
                    [
                        'class' => ConflictHttpException::class,
                        'code' => Response::HTTP_CONFLICT,
                        'message' => \sprintf('Email %s already registered', $user->email())
                    ],
                    Response::HTTP_CONFLICT);
            }

            if ($e instanceof \LogicException) {
                return $this->json([
                    'class' => BadRequestHttpException::class,
                    'code' => Response::HTTP_BAD_REQUEST,
                    'message' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->json([
                'class' => \get_class($e),
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => \sprintf('Server error. Message: %s', $e->getMessage())
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json($user->toArray());
    }
}