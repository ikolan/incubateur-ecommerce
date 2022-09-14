<?php

namespace App\Repository;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Product $entity, bool $flush = true): void
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
    public function remove(Product $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findByIdOrReference($data)
    {
        return is_numeric($data) ? $this->findOneBy(['id' => $data]) : $this->findOneBy(['reference' => $data]);
    }

    public function findProducts($query, $page = 1, $search = '')
    {
        $firstIndex = ($page - 1) * 30;

        $result = $this->createQueryBuilder('p')
            ->leftJoin('p.tags', 't')
            ->leftJoin('p.category', 'c')
            ->andWhere('p.isDeleted = false')
        ;

        if ((is_array($query) || $query instanceof \Countable ? count($query) : 0) > 0) {
            foreach ($query as $key => $value) {
                switch ($key) {
                    case 'stock':
                        $result->andWhere('p.stock > :stock')
                            ->setParameter('stock', $value);
                        break;
                    case 'name':
                    case 'tags':
                        $result
                            ->andWhere($result->expr()->orX(
                                $result->expr()->like('LOWER(p.name)', '?1'),
                                $result->expr()->like('LOWER(t.label)', '?2')
                            ))
                            ->setParameter('1', '%'.strtolower($value).'%')
                            ->setParameter('2', '%'.strtolower($value).'%');
                        break;
                    case 'price':
                        $result
                            ->andWhere(sprintf('p.price >= %s', explode('-', $value)[0]))
                            ->andWhere(sprintf('p.price <= %s', explode('-', $value)[1]));
                        break;
                    case 'category':
                        $result
                            ->andWhere('c.label LIKE :category')
                            ->setParameter('category', $value);
                        break;
                }
            }
        }

        // Search terms
        if ('' !== $search) {
            $result->andWhere('p.name LIKE :search')
                ->orWhere('p.reference LIKE :search')
                ->orWhere('p.description LIKE :search')
                ->orWhere('p.detailedDescription LIKE :search')
                ->setParameter('search', '%'.$search.'%');
        }

        $criteria = Criteria::create()
            ->setFirstResult($firstIndex)
            ->setMaxResults(30)
        ;
        $result->addCriteria($criteria);

        return new Paginator(new DoctrinePaginator($result));
    }

    public function getPriceSlice(): array
    {
        $min = $this->createQueryBuilder('p')->where('p.isDeleted = false')->orderBy('p.price', 'ASC')->getQuery()->getResult()[0]->getPrice();
        $max = $this->createQueryBuilder('p')->where('p.isDeleted = false')->orderBy('p.price', 'DESC')->getQuery()->getResult()[0]->getPrice();

        return [$min, $max];
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
