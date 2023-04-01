<?php

namespace App\Repository;

use App\Entity\Survey;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Survey>
 *
 * @method Survey|null find($id, $lockMode = null, $lockVersion = null)
 * @method Survey|null findOneBy(array $criteria, array $orderBy = null)
 * @method Survey[]    findAll()
 * @method Survey[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SurveyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Survey::class);
    }

    public function save(Survey $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Survey $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function activateStats()
    {
        return $this->createQueryBuilder('s')
            ->select('r.reponse, COUNT(r.id)')
            ->innerJoin(Reponse::class, 'r', 'WITH', 's.clientResponse=r.id')
            ->innerJoin(Question::class, 'q', 'WITH', 'q.id=r.question AND q.activate=:activate')
            ->groupBy('s.clientResponse')
            ->setParameter('activate', 1)
            ->getQuery()
            ->getResult();
    }

    public function oldStats($value)
    {
        return $this->createQueryBuilder('s')
            ->select('r.reponse, q.question, COUNT(r.id)')
            ->innerJoin(Reponse::class, 'r', 'WITH', 's.clientResponse=r.id')
            ->innerJoin(Question::class, 'q', 'WITH', 'q.id=r.question AND q.id=:question')
            ->groupBy('s.clientResponse')
            ->setParameter('question', $value)
            ->getQuery()
            ->getResult();
    }


//    /**
//     * @return Survey[] Returns an array of Survey objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Survey
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
