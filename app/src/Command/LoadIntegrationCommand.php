<?php
declare(strict_types=1);

namespace App\Command;


use App\Factory\IntegrationFactory;
use App\Producer\SaveCommentProducer;
use App\Producer\SaveTopicProducer;
use App\Service\LoadIntegrationService;

class LoadIntegrationCommand extends AbstractCommand
{
    /** @var SaveTopicProducer  */
    private $saveTopicProducer;
    /** @var SaveCommentProducer */
    private $saveCommentProducer;

    /**
     * LoadIntegrationCommand constructor.
     * @param SaveTopicProducer $saveTopicProducer
     * @param SaveCommentProducer $saveCommentProducer
     */
    public function __construct(
        SaveTopicProducer $saveTopicProducer,
        SaveCommentProducer $saveCommentProducer
    )
    {
        $this->saveTopicProducer    = $saveTopicProducer;
        $this->saveCommentProducer  = $saveCommentProducer;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'app:load-integration';
    }

    public function execute(array $config): void
    {
        $service = new LoadIntegrationService(IntegrationFactory::get($config));
        $this->saveTopicProducer->execute([$service->getTopic()]);
        $this->saveCommentProducer->execute($service->getComments());
    }
}
