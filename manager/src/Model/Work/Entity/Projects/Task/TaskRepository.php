<?php

declare(strict_types=1);

namespace App\Model\Work\Entity\Projects\Task;

use App\Model\EntityNotFoundException;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL;
use Doctrine\ORM\EntityManagerInterface;

class TaskRepository
{
    /** @var EntityManagerInterface */
    private $em;
    /** @var DBAL\Connection */
    private $connection;
    /** @var TaskRepository|ObjectRepository  */
    private $repository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->connection = $em->getConnection();
        $this->repository = $em->getRepository(Task::class);
    }

    public function add(Task $task): void
    {
        $this->em->persist($task);
    }

    /**
     * @return Id
     * @throws DBAL\DBALException
     */
    public function nextId(): Id
    {
        return new Id((int)$this->connection->query('SELECT nextval(\'work_projects_tasks_seq\')')->fetchColumn());
    }

    public function get(Id $id): Task
    {
        if (!$task = $this->repository->find($id->getValue())) {
            throw new EntityNotFoundException('Task is not found.');
        }

        return $task;
    }

    /**
     * @param Id $id
     * @return Task[]
     */
    public function allByParent(Id $id): array
    {
        return $this->repository->findBy(['parent' => $id->getValue()]);
    }

    public function remove(Task $task): void
    {
        $this->em->remove($task);
    }
}
