<?php

declare(strict_types=1);

namespace App\Repository\Comment;


use PDO;

class PDOCommentRepository implements CommentRepositoryInterface
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * PDOCommentRepository constructor.
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param array $comment
     * @throws \Exception
     */
    public function add(array $comment): void
    {
        try {
            $stmt = $this->pdo->prepare('
                INSERT INTO integration_comments(author, value, created_at, integration_name) 
                VALUES (:author, :value, :created_at, :integration_name)
            ');
            $this->pdo->beginTransaction();
            $stmt->execute($comment);
            $this->pdo->commit();
        } catch (\Exception $exception) {
            $this->pdo->rollBack();
            throw $exception;
        }
    }
}
