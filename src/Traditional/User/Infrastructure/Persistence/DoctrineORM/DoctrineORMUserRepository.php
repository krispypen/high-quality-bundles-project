<?php

namespace Traditional\User\Infrastructure\Persistence\DoctrineORM;

use Doctrine\Common\Persistence\ManagerRegistry;
use Traditional\User\Domain\Model\User;
use Traditional\User\Domain\Model\UserRepository;

class DoctrineORMUserRepository implements UserRepository
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->doctrine = $managerRegistry;
    }

    public function add(User $user)
    {
        $this->doctrine->getManager()->persist($user);
    }

    public function all()
    {
        return $this->doctrine->getManager()->getRepository(User::class)->findAll();
    }
}