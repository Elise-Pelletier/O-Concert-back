<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Event $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Event $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findEventsByCriteria(int $region_id , int $genre_id )
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 
            'SELECT `event`.`name`, `event`.`image`, `event`.`price`, `event`.`link_ticketing`, `genre`.`name` as `genre_name`, `region`.`name` as `region_name`
            FROM `event`
            INNER JOIN `event_genre` ON `event`.`id` = `event_id` 
            INNER JOIN `genre` ON `event_genre`.`genre_id` = `genre`.`id`
            INNER JOIN `region` ON `event`.`region_id` = `region`.`id`
            WHERE `event_genre`.`genre_id` = :genre_id
            AND `region`.`id` = :region_id'
            ;

            $stmt = $conn->prepare($sql);

            $resultSet = $stmt->executeQuery(['genre_id' => $genre_id, 'region_id' => $region_id]);

            return $resultSet->fetchAllAssociative();
    }

    // /**
    //  * @return Event[] Returns an array of Event objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
