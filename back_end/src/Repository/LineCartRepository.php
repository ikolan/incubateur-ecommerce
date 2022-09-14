<?php

namespace App\Repository;

use App\Entity\LineCart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LineCart|null find($id, $lockMode = null, $lockVersion = null)
 * @method LineCart|null findOneBy(array $criteria, array $orderBy = null)
 * @method LineCart[]    findAll()
 * @method LineCart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LineCartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LineCart::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(LineCart $entity, bool $flush = true): void
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
    public function remove(LineCart $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findCartByUserAndProduct($product, $user)
    {
        return $this->createQueryBuilder('l')
            ->join('l.product', 'p')
            ->join('l.cartUser', 'u')
            ->andWhere('p.reference = :reference')
            ->setParameter('reference', $product->getReference())
            ->andWhere('u.id = :user')
            ->setParameter('user', $user->getId())
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return LineCart[] Returns an array of LineCart objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LineCart
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
