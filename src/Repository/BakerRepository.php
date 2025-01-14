<?php

namespace App\Repository;

use App\Entity\Baker;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Baker>
 *
 * @method Baker|null find($id, $lockMode = null, $lockVersion = null)
 * @method Baker|null findOneBy(array $criteria, array $orderBy = null)
 * @method Baker[]    findAll()
 * @method Baker[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BakerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Baker::class);
    }

    public function add(Baker $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
            // all pictures are set to null to avoid serialization error when the user modify the pictures
            $entity->setProfilePictureFile(null);
            $entity->setProfilePicture(null);
            $entity->setLogoFile(null);
            $entity->setLogo(null);
            $entity->setSiretFile(null);
            $entity->setSiret(null);
            $entity->setDiplomaFile(null);
            $entity->setDiploma(null);
        }
    }

    public function remove(Baker $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findLikeAll(mixed $search): mixed
    {
        $order = $this->findLikeEmail($search);
        $order += $this->findLikeName($search);

        return $order;
    }

    public function findLikeEmail(mixed $email): mixed
    {
        $gem = $this->getEntityManager();
        $query = $gem->createQuery('SELECT b,u FROM App\Entity\baker b
        INNER JOIN b.user u
        WHERE u.email LIKE :email');
        $query->setParameter('email', '%' . $email . '%');

        return $query->getResult();
    }

    public function findLikeName(mixed $firstname): mixed
    {
        $gem = $this->getEntityManager();
        $query = $gem->createQuery('SELECT b,u FROM App\Entity\baker b
        INNER JOIN b.user u
        WHERE u.firstname LIKE :firstname');
        $query->setParameter('firstname', '%' . $firstname . '%');

        return $query->getResult();
    }

//    /**
//     * @return Baker[] Returns an array of Baker objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Baker
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
