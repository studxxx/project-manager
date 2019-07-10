<?php

declare(strict_types=1);

namespace App\Model\Work\Entity\Projects\Project;

use App\Model\EntityNotFoundException;
use Doctrine\ORM;

class ProjectRepository
{
    /** @var ORM\EntityManagerInterface */
    private $em;
    /** @var ORM\EntityRepository */
    private $repository;

    public function __construct(ORM\EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(Project::class);
    }

    public function get(Id $id): Project
    {
        if (!$project = $this->repository->find($id->getValue())) {
            throw new EntityNotFoundException('Project is not found.');
        }
        return $project;
    }

    public function add(Project $project): void
    {
        $this->em->persist($project);
    }

    public function remove(Project $project): void
    {
        $this->em->remove($project);
    }
}
