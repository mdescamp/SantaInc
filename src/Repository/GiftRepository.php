<?php

namespace App\Repository;

use App\Entity\Factory;
use App\Entity\Gift;
use App\Entity\Receiver;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Gift|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gift|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gift[]    findAll()
 * @method Gift[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GiftRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gift::class);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function getCountryNumber(Factory $factory)
    {
        return $this->createQueryBuilder('g')
            ->select('COUNT(DISTINCT(r.country)) as country')
            ->join(Receiver::class, 'r', 'WITH', 'r.id = g.receiver')
            ->andWhere('g.factory = :id')
            ->setParameter('id', $factory->getId())
            ->getQuery()
            ->getSingleResult()['country'];
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function getPriceMax(Factory $factory)
    {
        return $this->createQueryBuilder('g')
                ->select('MAX(g.price) as price')
                ->andWhere('g.factory = :id')
                ->setParameter('id', $factory->getId())
                ->getQuery()
                ->getSingleResult()['price'] ?? 0;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function getPriceMin(Factory $factory)
    {
        return $this->createQueryBuilder('g')
                ->select('MIN(g.price) as price')
                ->andWhere('g.factory = :id')
                ->setParameter('id', $factory->getId())
                ->getQuery()
                ->getSingleResult()['price'] ?? 0;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function getPriceAVG(Factory $factory)
    {
        return $this->createQueryBuilder('g')
                ->select('AVG(g.price) as price')
                ->andWhere('g.factory = :id')
                ->setParameter('id', $factory->getId())
                ->getQuery()
                ->getSingleResult()['price'] ?? 0;
    }
}
