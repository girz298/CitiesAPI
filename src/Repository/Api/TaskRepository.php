<?php

namespace App\Repository\Api;

use App\Entity\Api\Task;
use Doctrine\ORM\EntityRepository;

class TaskRepository extends EntityRepository
{
    public function getUserIdForTask(Task $task)
    {
        $result = $this
            ->createQueryBuilder('t')
            ->select('u.id')
            ->leftJoin('t.project', 'p')
            ->leftJoin('p.user', 'u')
            ->where('t.id = :id')
            ->setParameter('id', $task->getId())
            ->getQuery()
            ->getArrayResult();

        return current(current($result));
    }
}
