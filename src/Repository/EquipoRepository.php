<?php

namespace App\Repository;

use App\Entity\Equipo;
use App\Model\Equipo\EquipoRepositoryCriteria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Equipo>
 *
 * @method Equipo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Equipo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Equipo[]    findAll()
 * @method Equipo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Equipo::class);
    }

    public function save(Equipo $entity, bool $flush = false): Equipo
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
        return $entity;
    }


    public function reload(Equipo $entity): Equipo
    {
        $this->getEntityManager()->refresh($entity);
        return $entity;
    }

    public function remove(Equipo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByCriteria(EquipoRepositoryCriteria $criteria): array
    {
        $queryBuilder = $this->createQueryBuilder('b')
            ->orderBy('b.nombre', 'DESC');

            if ($criteria->clubId !== null) {
                $queryBuilder
                    ->andWhere(':clubId MEMBER OF b.club')
                    ->setParameter('clubId', $criteria->clubId);
            }
    
            if ($criteria->nombre !== null) {
                $queryBuilder
                    ->andWhere('b.nombre LIKE :nombre')
                    ->setParameter('nombre', "%{$criteria->nombre}%");
            }
    
            if ($criteria->fecha_baja !== null) {
                $queryBuilder
                    ->andWhere('b.fechaBaja isNot NULL  ');
            } else {
    
                $queryBuilder
                    ->andWhere('b.fechaBaja is NULL');
    
            }
    
            if ($criteria->codequipo !== null) {
                $queryBuilder
                    ->andWhere('b.codigoAstfut = :codigoAstfut')
                    ->setParameter('codigoAstfut', "{$criteria->codequipo}");
            }                    

        $queryBuilder->setMaxResults($criteria->itemsPerPage);
        $queryBuilder->setFirstResult(($criteria->page - 1) * $criteria->itemsPerPage);

        $paginator = new Paginator($queryBuilder->getQuery());

        return [
            'total' => \count($paginator),
            'itemsPerPage' => $criteria->itemsPerPage,
            'page' => $criteria->page,
            'data' => iterator_to_array($paginator->getIterator())
        ];
    }    
//    /**
//     * @return Equipo[] Returns an array of Equipo objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Equipo
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
