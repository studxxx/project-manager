<?php

declare(strict_types=1);

namespace App\Model\Comment\Entity\Comment;

use App\Model\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class CommentRepository
{
    /** @var EntityRepository */
    private $repository;
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(Comment::class);
    }

    public function get(Id $id): Comment
    {
        if (!$comment = $this->repository->find($id->getValue())) {
            throw new EntityNotFoundException('Comment is not found.');
        }
        return $comment;
    }

    public function add(Comment $comment): void
    {
        $this->em->persist($comment);
    }

    public function remove(Comment $comment): void
    {
        $this->em->remove($comment);
    }
}
