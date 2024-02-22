<?php

namespace App\Repository;

use App\Entity\Provider;
use App\Entity\TimeRule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TimeRule>
 *
 * @method TimeRule|null find($id, $lockMode = null, $lockVersion = null)
 * @method TimeRule|null findOneBy(array $criteria, array $orderBy = null)
 * @method TimeRule[]    findAll()
 * @method TimeRule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimeRuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TimeRule::class);
    }

    public function getRuleByProviderAndCountry(Provider $provider, string $country): ?TimeRule
    {
        return $this->findOneBy(['provider' => $provider, 'country' => $country]);
    }

    public function getDefaultRuleByProvider(Provider $provider): ?TimeRule
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.provider = :provider')
            ->andWhere('p.country = :country')
            ->setParameter('provider', $provider)
            ->setParameter('country', '')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
