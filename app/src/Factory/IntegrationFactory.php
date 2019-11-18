<?php
declare(strict_types=1);

namespace App\Factory;


use App\Factory\Exception\IntegrationFactoryException;
use App\Integrations\Interfaces\IntegrationInterface;

class IntegrationFactory
{
    const INTEGRATION = 'INTEGRATION';

    /**
     * @param array $integrationConfig
     * @return IntegrationInterface
     * @throws IntegrationFactoryException
     */
    static function get(array $integrationConfig): IntegrationInterface
    {
        if (!in_array(IntegrationInterface::class, class_implements($integrationConfig[self::INTEGRATION]))) {
            throw new IntegrationFactoryException();
        }
        return new $integrationConfig[self::INTEGRATION]($integrationConfig);
    }
}
