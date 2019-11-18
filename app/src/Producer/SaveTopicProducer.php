<?php
declare(strict_types=1);

namespace App\Producer;


use PhpAmqpLib\Message\AMQPMessage;

class SaveTopicProducer extends AbstractProducer
{
    /**
     * @param array $topic
     */
    public function execute(array $topic)
    {
        $this->make(new AMQPMessage(current($topic)->getJson(), ['delivery_mode' => 2]));
        $this->close();
    }

    /**
     * @return string
     */
    public function getQueueName()
    {
        return 'save_topic';
    }
}