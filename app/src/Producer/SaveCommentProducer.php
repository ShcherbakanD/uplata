<?php
declare(strict_types=1);

namespace App\Producer;


use App\Model\Comment;
use PhpAmqpLib\Message\AMQPMessage;

class SaveCommentProducer extends AbstractProducer
{
    /**
     * @param array $comments
     */
    public function execute(array $comments)
    {
        /** @var Comment $comment */
        foreach ($comments as $comment) {
            $this->make(new AMQPMessage($comment->getJson()), ['delivery_mode' => 2]);
        }
        $this->close();
    }

    /**
     * @return string
     */
    public function getQueueName()
    {
        return 'save_comment';
    }
}