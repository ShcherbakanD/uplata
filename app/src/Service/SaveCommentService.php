<?php
declare(strict_types=1);

namespace App\Service;


use App\Repository\Comment\CommentRepositoryInterface;

class SaveCommentService
{
    /** @var CommentRepositoryInterface */
    private $commentRepository;

    /**
     * SaveCommentService constructor.
     * @param CommentRepositoryInterface $commentRepository
     */
    public function __construct(
        CommentRepositoryInterface $commentRepository
    )
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * @param array $comment
     */
    public function handle(array $comment)
    {
        $this->commentRepository->add($comment);
    }
}