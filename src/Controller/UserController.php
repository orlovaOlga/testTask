<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    #[Route('/users', name: 'user', methods: ['GET'])]
    public function index(Request $request, UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        $name = $request->query->get('name');

        $users = !empty($name)
            ? $userRepository->findByName($name)
            : $userRepository->findAll();

        $jsonData = $serializer->normalize($users, 'json', ['groups' => [User::USER_NORMALIZATION_GROUP]]);

        return new JsonResponse($jsonData, Response::HTTP_OK);
    }
}