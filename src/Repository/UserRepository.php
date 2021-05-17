<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function joindre(){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('u')
            ->from('User','u')
            ->join('Images', 'i')
            ->where('i.id = :user_id')
            ;
    }
    public function editProfil(){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('i.name as name')
            ->from('User','u')
            ->join('Images', 'i')
            ->where('i.id = :user_id')
        ;
    }

/*
public function joindreTable(){
$qb = $this->getEntityManager()->createQueryBuilder();

return $qb->select('name')
->from('User', 'u')
->join('Images', 'i', 'ON', 'u.id=i.id_user')
->where('User.id = ?')
->getQuery()
->getResult();
}

/*
    public function joindreTableImage($images)
    {

        $qb = $this->createQueryBuilder('i')
                    ->join('i.images', 'ii')
            ->join('i.participants', 'ip' )

            ->where('i.images IN (:images)')
            ->setParameter('images', $images);

return $qb->getQuery()->getResult();

      /*  $qb->setMaxResults(1);
        $query = $qb->getQuery();

    }*/

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function loadUserByUsername($username)
    {
        // TODO: Implement loadUserByUsername() method.
    }
}
