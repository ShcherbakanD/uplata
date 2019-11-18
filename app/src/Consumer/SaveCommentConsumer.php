<?php
declare(strict_types=1);

namespace App\Consumer;


use App\Service\SaveCommentService;

class SaveCommentConsumer extends AbstractConsumer
{
    /** @var SaveCommentService  */
    private $saveCommentService;

    /**
     * SaveCommentConsumer constructor.
     * @param SaveCommentService $saveCommentService
     */
    public function __construct(
        SaveCommentService $saveCommentService
    )
    {
        $this->saveCommentService = $saveCommentService;
        $this->createConnection();
    }

    public function execute(array $data)
    {
        $this->saveCommentService->handle($data);
    }

    public function getListenQueueName()
    {
        return 'save_comment';
    }
}
