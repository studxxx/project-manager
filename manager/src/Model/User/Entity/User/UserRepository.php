<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM;

class UserRepository
{
    /** @var EntityManagerInterface */
    private $em;
    /** @var ORM\EntityRepository */
    private $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(User::class);
    }

    /**
     * @param Id $id
     * @return User|object
     * @throws EntityNotFoundException
     */
    public function get(Id $id): User
    {
        if (!$user = $this->repo->find($id->getValue())) {
            throw new EntityNotFoundException('User is not found.');
        }
        return  $user;
    }

    /**
     * @param Email $email
     * @return User|object
     * @throws EntityNotFoundException
     */
    public function getByEmail(Email $email): User
    {
        if (!$user = $this->repo->findOneBy(['email' => $email->getValue()])) {
            throw new EntityNotFoundException('User is not found.');
        }
        return $user;
    }

    /**
     * @param string $token
     * @return User|object|null
     */
    public function findByResetToken(string $token): ?User
    {
        return $this->repo->findOneBy(['resetToken.token' => $token]);
    }

    /**
     * @param string $token
     * @return User|object|null
     */
    public function findByConfirmToken(string $token): ?User
    {
        return $this->repo->findOneBy(['confirmToken' => $token]);
    }

    /**
     * @param string $network
     * @param string $identity
     * @return bool
     * @throws ORM\NonUniqueResultException
     */
    public function hasByNetworkIdentity(string $network, string $identity): bool
    {
        return $this->repo->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->innerJoin('t.networks', 'n')
                ->andWhere('n.network = :network AND n.identity = :identity')
                ->setParameter(':network', $network)
                ->setParameter(':identity', $identity)
                ->getQuery()
                ->getSingleScalarResult() > 0;
    }

    /**
     * @param Email $email
     * @return bool
     * @throws ORM\NonUniqueResultException
     */
    public function hasByEmail(Email $email): bool
    {
        return $this->repo->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->andWhere('t.email = :email')
            ->setParameter(':email', $email->getValue())
            ->getQuery()
            ->getSingleScalarResult() > 0;
    }

    public function add(User $user): void
    {
        $this->em->persist($user);
    }
}
