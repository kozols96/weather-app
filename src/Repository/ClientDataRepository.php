<?php

namespace App\Repository;

use App\Entity\ClientData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClientData>
 *
 * @method ClientData|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientData|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientData[]    findAll()
 * @method ClientData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientData::class);
    }

    public function save(ClientData $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ClientData $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param string $ipAddress
     * @return ClientData|null
     * @throws NonUniqueResultException
     */
    public function findOneByIpAddress(string $ipAddress): ?ClientData
    {
        return $this->createQueryBuilder('c')
            ->where('c.ip_address = :ipAddress')
            ->setParameter('ipAddress', $ipAddress)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
