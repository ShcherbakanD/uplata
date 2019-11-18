<?php

declare(strict_types=1);

namespace App\Repository\Topic;



interface TopicRepositoryInterface
{
    public function add(array $topic): void;
}
