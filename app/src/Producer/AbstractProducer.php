<?php


namespace App\Producer;


use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

abstract class AbstractProducer
{
    private $channel;

    public function __construct()
    {
        $this->createConnection($this->getQueueName());
    }

    public function createConnection(string $queueName)
    {
        $connection = new AMQPStreamConnection(
            getenv('RABBIT_MQ_HOST'),
            getenv('RABBIT_MQ_PORT'),
            getenv('RABBIT_MQ_USER'),
            getenv('RABBIT_MQ_PASS')
        );

        $this->channel = $connection->channel();

        $this->channel->queue_declare(
            $queue = $queueName,
            $passive = false,
            $durable = true,
            $exclusive = false,
            $auto_delete = false,
            $nowait = false,
            $arguments = null,
            $ticket = null
        );
    }

    abstract function getQueueName();

    abstract function execute(array $message);

    public function make(AMQPMessage $message)
    {
        try {
            $this->channel->basic_publish($message, '', $this->getQueueName());
        } catch (\Exception $exception) {

        }
    }

    public function close()
    {
        $this->channel->close();
    }
}
