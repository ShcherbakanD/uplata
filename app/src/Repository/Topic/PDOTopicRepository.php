<?php

declare(strict_types=1);

namespace App\Repository\Topic;


use PDO;

class PDOTopicRepository implements TopicRepositoryInterface
{
    /** @var PDO  */
    private $pdo;

    /**
     * PDOTopicRepository constructor.
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param array $topic
     * @throws \Exception
     */
    public function add(array $topic): void
    {
        try {
            $stmt = $this->pdo->prepare('
                INSERT INTO integration_topics(name, author, value, created_at, integration_name) 
                VALUES (:name, :author, :value, :created_at, :integration_name)
            ');
            $this->pdo->beginTransaction();
            $stmt->execute($topic);
            $this->pdo->commit();
        } catch (\Exception $exception) {
            $this->pdo->rollBack();
            throw $exception;
        }
    }
}
