<?php

namespace App\Repository;

use App\Entity\ToDoList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ToDoList>
 *
 * @method ToDoList|null find($id, $lockMode = null, $lockVersion = null)
 * @method ToDoList|null findOneBy(array $criteria, array $orderBy = null)
 * @method ToDoList[]    findAll()
 * @method ToDoList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ToDoListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ToDoList::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ToDoList $entity, bool $flush = false): void
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
    public function remove(ToDoList $entity, bool $flush = false): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    public function findToDOListWithTaskCount(): array
    {
    $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT  to_do_list.id, to_do_list.name, COUNT(task.id)
                FROM to_do_list
                JOIN task ON  task.list_id = to_do_list.id
                GROUP BY to_do_list.id';
                $stmt = $conn->prepare($sql);
        $sqlResult = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $sqlResult->fetchAllAssociative();
    }

    public function findToDOListWithValidTask(): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT  to_do_list.id, to_do_list.name, COUNT(task.id) AS tasknb
                FROM to_do_list
                JOIN task ON  task.list_id = to_do_list.id
                WHERE task.state=1
                GROUP BY to_do_list.id';
        $stmt = $conn->prepare($sql);
        $sqlResult = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $sqlResult->fetchAllAssociative();
    }

//    public function findOneBySomeField($value): ?ToDoList
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
