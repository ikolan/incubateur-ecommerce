<?php

namespace App\Repository;

use App\Entity\Address;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Address|null find($id, $lockMode = null, $lockVersion = null)
 * @method Address|null findOneBy(array $criteria, array $orderBy = null)
 * @method Address[]    findAll()
 * @method Address[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Address::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Address $entity, bool $flush = true): void
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
    public function remove(Address $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * If $entity already exist in the database, return this one.
     *
     * @return Address|null
     */
    public function identicalTo(Address $entity)
    {
        $identicals = $this->createQueryBuilder('a')
            ->andWhere('a.title = :title')
            ->andWhere('a.number = :number')
            ->andWhere('a.road = :road')
            ->andWhere('a.zipcode = :zipcode')
            ->andWhere('a.city = :city')
            ->andWhere('a.phone = :phone')
            ->setParameter('title', $entity->getTitle())
            ->setParameter('number', $entity->getNumber())
            ->setParameter('road', $entity->getRoad())
            ->setParameter('zipcode', $entity->getZipcode())
            ->setParameter('city', $entity->getCity())
            ->setParameter('phone', $entity->getPhone())
            ->getQuery()->getResult();

        return empty($identicals) ? null : $identicals[0];
    }

    // /**
    //  * @return Address[] Returns an array of Address objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Address
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
