<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function searchByTitle(string $search): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT * FROM task WHERE title LIKE '%" . $search . "%'";

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery();

        return $result->fetchAllAssociative();
    }

    public function findByStatus(string $status): array
    {
        return $this->findBy(['status' => $status]);
    }
}
