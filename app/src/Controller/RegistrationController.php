<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Dto\RegisterUserRequest;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\RegistrationService;
use App\Exception\EmailAlreadyRegisteredException;

final class RegistrationController extends AbstractController
{
    
#[Route('/api/auth/register', name: 'api_auth_register', methods: ['POST'])]
public function register(
    Request $request, 
    ValidatorInterface $validator,
    RegistrationService $registrationService
): JsonResponse 
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            return $this->json(
                ['error' => 'Invalid JSON body'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $registerUserRequest = new RegisterUserRequest();
        $registerUserRequest->email = $data['email'] ?? null;
        $registerUserRequest->password = $data['password'] ?? null;

        if (is_string($registerUserRequest->email)) {
            $registerUserRequest->email = trim(mb_strtolower($registerUserRequest->email));
        }

        $violations = $validator->validate($registerUserRequest);
        if (count($violations) > 0) {
            $errorMessages = [];
            foreach ($violations as $violation) {
                $errorField = $violation->getPropertyPath();
                $errorMessages[$errorField] = $violation->getMessage();
            }
            return $this->json(
                ['errors' => $errorMessages],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $registrationService->register($registerUserRequest->email, $registerUserRequest->password);
        } catch (EmailAlreadyRegisteredException $e) {
            return $this->json(
                ['error' => $e->getMessage()],
                Response::HTTP_CONFLICT
            );
        }

        return $this->json(
            ['message' => 'User registered successfully',],
            Response::HTTP_CREATED
        );
    }
}
