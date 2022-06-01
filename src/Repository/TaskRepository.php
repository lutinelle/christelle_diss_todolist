<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 *
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Task $entity, bool $flush = false): void
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
    public function remove(Task $entity, bool $flush = false): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    /**
     * @return Task[] Returns an array of Task objects
     */
    public function findByUrgent(): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT  task.id, task.name, task.state, to_do_list.name AS listename, to_do_list.id AS listid
                FROM task
                JOIN to_do_list ON  to_do_list.id = task.list_id
                WHERE task.urgent=1 AND task.state=0' ;
        $stmt = $conn->prepare($sql);
        $sqlResult = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $sqlResult->fetchAllAssociative();

    }
}