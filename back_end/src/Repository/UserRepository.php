<?php

namespace App\Repository;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(User $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(User $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * Find all user by search.
     *
     * @throws QueryException
     */
    public function findAllBySearch(string $search = '', int $page = 1): Paginator
    {
        $firstIndex = ($page - 1) * 30;

        $queryBuilder = $this->createQueryBuilder('u');

        if ('' !== $search) {
            $queryBuilder->where('u.firstName LIKE :search')
                ->orWhere('u.lastName LIKE :search')
                ->orWhere('u.email LIKE :search')
                ->orWhere("CONCAT(u.firstName, ' ', u.lastName) LIKE :search")
                ->setParameter('search', '%'.$search.'%');
        }

        $criteria = Criteria::create()
            ->setFirstResult($firstIndex)
            ->setMaxResults(30);
        $queryBuilder->addCriteria($criteria);

        return new Paginator(new DoctrinePaginator($queryBuilder));
    }

    /**
     * Find user by its email address.
     *
     * @return User|null
     */
    public function findByEmail(string $email)
    {
        $queryResult = $this->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->setParameter(':email', $email)
            ->getQuery()->getResult();

        if (1 != (is_array($queryResult) || $queryResult instanceof \Countable ? count($queryResult) : 0)) {
            return null;
        } else {
            return $queryResult[0];
        }
    }

    /**
     * Find activated user by its email address.
     *
     * @return User|null
     */
    public function findActivatedByEmail(string $email)
    {
        $queryResult = $this->createQueryBuilder('u')
            ->andWhere('u.isActivated = true')
            ->andWhere('u.email = :email')
            ->setParameter(':email', $email)
            ->getQuery()->getResult();

        if (1 != (is_array($queryResult) || $queryResult instanceof \Countable ? count($queryResult) : 0)) {
            return null;
        } else {
            return $queryResult[0];
        }
    }

    public function findByIdOrEmail(string $data)
    {
        return is_numeric($data) ? $this->findOneBy(['id' => intval($data)]) : $this->findOneBy(['email' => base64_decode($data)]);
    }

    /**
     * Find a User by its password reset key.
     *
     * @return User|null
     */
    public function findByPasswordResetKey(string $resetKey)
    {
        return $this->findOneBy(['resetKey' => $resetKey, 'isActivated' => true]);
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
