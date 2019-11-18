<?php

declare(strict_types=1);

namespace App\Repository\Comment;


interface CommentRepositoryInterface
{
    public function add(array $comment): void;
}
