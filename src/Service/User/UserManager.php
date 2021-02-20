<?php

namespace App\Service\User;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserManager
{
    /** @var EncoderFactoryInterface */
    private $encoderFactory;

    /** @var EntityManagerInterface */
    private $em;

    /** @var UserRepository */
    private $userRepository;

    public function __construct(EncoderFactoryInterface $encoderFactory, EntityManagerInterface $em)
    {
        $this->encoderFactory = $encoderFactory;
        $this->em = $em;
        $this->userRepository = $em->getRepository(User::class);
    }

    public function createUser(): User
    {
        return new User();
    }

    public function findUserBy(array $criteria): ?User
    {
        return $this->userRepository->findOneBy($criteria);
    }

    public function findUserByUsername($username): ?User
    {
        return $this->findUserBy(['username' => $username]);
    }

    public function updateUser(User $user, $flush = true)
    {
        $this->updatePassword($user);
        $this->em->persist($user);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function updatePassword(User $user)
    {
        $plainPassword = $user->getPlainPassword();

        if (0 === strlen($plainPassword)) {
            return;
        }

        $encoder = $this->encoderFactory->getEncoder($user);
        $hashedPassword = $encoder->encodePassword($plainPassword, $user->getSalt());
        $user->setPassword($hashedPassword);
        $user->eraseCredentials();
    }
}