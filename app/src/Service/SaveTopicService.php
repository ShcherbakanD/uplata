<?php
declare(strict_types=1);

namespace App\Service;


use App\Repository\Topic\TopicRepositoryInterface;

class SaveTopicService
{
    /** @var TopicRepositoryInterface */
    private $topicRepository;

    /**
     * SaveTopicService constructor.
     * @param TopicRepositoryInterface $topicRepository
     */
    public function __construct(
        TopicRepositoryInterface $topicRepository
    )
    {
        $this->topicRepository = $topicRepository;
    }

    /**
     * @param array $data
     */
    public function handle(array $data)
    {
        $this->topicRepository->add($data);
    }
}