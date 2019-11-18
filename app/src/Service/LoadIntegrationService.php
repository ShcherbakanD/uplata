<?php
declare(strict_types=1);

namespace App\Service;


use App\Integrations\Interfaces\IntegrationInterface;
use App\Model\Topic;

class LoadIntegrationService
{
    /** @var IntegrationInterface  */
    private $integration;

    /**
     * LoadIntegrationService constructor.
     * @param IntegrationInterface $integration
     */
    public function __construct(IntegrationInterface $integration)
    {
        $this->integration = $integration;
    }

    /**
     * @return Topic
     */
    public function getTopic(): Topic
    {
        return $this->integration->getTopic();
    }

    /**
     * @return array
     */
    public function getComments(): array
    {
        return $this->integration->getComments();
    }
}
