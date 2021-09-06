<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Factory;
use App\Entity\Gift;
use App\Entity\Receiver;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

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
     * @param Request $request
     * @return Gift[]
     */
    public function filter(Request $request): array
    {
        $dql = $this->createQueryBuilder('g');
        if ($request->get('code')) {
            $dql->andWhere('g.code = :code')
                ->setParameter('code', $request->get('code'));
        }
        if ($request->get('factory')) {
            $dql->andWhere('g.factory = :factory')
                ->setParameter('factory', $request->get('factory'));
        }
        if ($request->get('receiver')) {
            $dql->join(Receiver::class, 'r', 'WITH', 'g.receiver = r.id');
            $dql->andWhere('(r.lastName LIKE :receiver OR r.firstName LIKE :receiver)')
                ->setParameter('receiver', '%' . $request->get('receiver') . '%');
        }
        if ($request->get('price-min')) {
            $dql->andWhere('g.price >= :priceMin')
                ->setParameter('priceMin', $request->get('price-min'));
        }
        if ($request->get('price-max')) {
            $dql->andWhere('g.price <= :priceMax')
                ->setParameter('priceMax', $request->get('price-max'));
        }

        return $dql->getQuery()
            ->getResult();
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
