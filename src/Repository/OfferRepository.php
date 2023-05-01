<?php

namespace App\Repository;

use App\Entity\LimitedOffer;
use App\Entity\Offer;
use App\Entity\PermanentOffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Offer>
 *
 * @method Offer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offer[]    findAll()
 * @method Offer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offer::class);
    }

    public function save(Offer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Offer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function orderedOffer(string $type = null)
    {
        if ($type == 'limited') {
            return $this->createQueryBuilder('o')
                ->innerJoin(LimitedOffer::class, 'l', 'WITH', 'o.id = l.id')
                ->orderBy('l.orderNumber', 'DESC')
                ->getQuery()
                ->getResult();
        } elseif ($type == 'permanent') {
            return $this->createQueryBuilder('o')
                ->innerJoin(PermanentOffer::class, 'p', 'WITH', 'o.id = p.id')
                ->getQuery()
                ->getResult();
        } else {
            return $this->createQueryBuilder('o')
                ->leftJoin(LimitedOffer::class, 'l', 'WITH', 'o.id = l.id')
                ->leftJoin(PermanentOffer::class, 'p', 'WITH', 'o.id = p.id')
                ->orderBy('l.orderNumber', 'DESC')
                ->getQuery()
                ->getResult();
        }
    }

    public function orderedLimitedOffer()
    {
        return $this->createQueryBuilder('o')
            ->innerJoin(LimitedOffer::class, 'l', 'WITH', 'o.id = l.id')
            ->orderBy('l.orderNumber', 'DESC')
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return Offer[] Returns an array of Offer objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Offer
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
