<?php

namespace App\Repository;

use App\Entity\DeliveryInitiateTimeRules;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InitiateTimeRules>
 *
 * @method InitiateTimeRules|null find($id, $lockMode = null, $lockVersion = null)
 * @method InitiateTimeRules|null findOneBy(array $criteria, array $orderBy = null)
 * @method InitiateTimeRules[]    findAll()
 * @method InitiateTimeRules[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InitiateTimeRuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InitiateTimeRules::class);
    }

//    /**
//     * @return DeliveryInitiateTimeRules[] Returns an array of DeliveryInitiateTimeRules objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DeliveryInitiateTimeRules
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
