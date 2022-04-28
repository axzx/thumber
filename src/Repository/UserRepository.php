<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;

/** @see User */
class UserRepository
{
    public function __construct(private readonly ManagerRegistry $registry)
    {
    }

    /**
     * @return iterable<User>
     */
    public function findAllWithPhotoIterable(): iterable
    {
        /** @var EntityRepository<User> $repo */
        $repo = $this->getRepository();
        $qb = $repo->createQueryBuilder('u');
        $qb->andWhere('u.photo is not null');

        return $qb->getQuery()->toIterable();
    }

    public function save(User $entity, bool $flush = true): void
    {
        $this->registry->getManager()->persist($entity);
        if ($flush) {
            $this->registry->getManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = true): void
    {
        $this->registry->getManager()->remove($entity);
        if ($flush) {
            $this->registry->getManager()->flush();
        }
    }

    /**
     * @return ObjectRepository<User>
     */
    private function getRepository(): ObjectRepository
    {
        return $this->registry->getManager()->getRepository(User::class);
    }
}
