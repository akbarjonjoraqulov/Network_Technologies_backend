<?php

declare(strict_types=1);

namespace App\Component\User;

use App\Entity\User;
use DateTimeZone;
use Symfony\Component\Clock\DatePoint;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactory
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function create(
        string $givenName,
        string $familyName,
        string $password,
        string $email,
    ): User {
        $user = new User();

        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);

        $user->setGivenName($givenName);
        $user->setFamilyName($familyName);
        $user->setPassword($hashedPassword);
        $user->setEmail($email);
        $user->setCreatedAt(new DatePoint(timezone: new DateTimeZone('Asia/Tashkent')));

        return $user;
    }
}
