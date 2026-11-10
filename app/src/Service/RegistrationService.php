<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Repository\UserRepository;
use App\Exception\EmailAlreadyRegisteredException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;


final class RegistrationService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserRepository $userRepository,
    ) {
    }

    public function register(string $email, string $password): User
    {
        if ($this->userRepository->findOneBy(['email' => $email]) !== null) {
            throw new EmailAlreadyRegisteredException();
        }

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));

        $this->entityManager->persist($user);
        try {
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $e) {
            throw new EmailAlreadyRegisteredException();
        }

        return $user;
    }
}