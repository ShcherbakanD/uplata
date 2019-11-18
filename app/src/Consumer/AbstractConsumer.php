<?php
declare(strict_types=1);

namespace App\Consumer;


use App\Utils\Interfaces\ConsumerInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

abstract class AbstractConsumer implements ConsumerInterface
{
    /** @var AMQPStreamConnection */
    private $channel;

    public function createConnection()
    {
        $connection = new AMQPStreamConnection(
            getenv('RABBIT_MQ_HOST'),
            getenv('RABBIT_MQ_PORT'),
            getenv('RABBIT_MQ_USER'),
            getenv('RABBIT_MQ_PASS')
        );

        $this->channel = $connection->channel();

        $callback = function ($msg) {
            $this->execute(json_decode($msg->body, true));
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        };

        $this->channel->basic_consume(
            $queue = $this->getListenQueueName(),
            $consumer_tag = '',
            $no_local = false,
            $no_ack = false,
            $exclusive = false,
            $nowait = false,
            $callback
        );

        while (count($this->channel->callbacks))
        {
            $this->channel->wait();
        }

        $this->channel->close();
        $connection->close();
    }

    abstract function getListenQueueName();

    abstract function execute(array $data);

    public function make(AMQPMessage $message)
    {
        try {
            $this->channel->basic_publish($message, '', $this->getPublishQueueName());
        } catch (\Exception $exception) {
            $this->channel->close();
        }
    }

    public function load()
    {

    }
}
