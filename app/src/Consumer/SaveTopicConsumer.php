<?php
declare(strict_types=1);

namespace App\Consumer;


use App\Service\SaveTopicService;

class SaveTopicConsumer extends AbstractConsumer
{
    /** @var SaveTopicService  */
    private $saveTopicService;

    /**
     * SaveTopicConsumer constructor.
     * @param SaveTopicService $saveTopicService
     */
    public function __construct(
        SaveTopicService $saveTopicService
    )
    {
        $this->saveTopicService = $saveTopicService;
        $this->createConnection();
    }

    public function execute(array $data)
    {
        $this->saveTopicService->handle($data);
    }

    public function getListenQueueName()
    {
        return 'save_topic';
    }
}
