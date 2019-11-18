<?php
declare(strict_types=1);

namespace App\Integrations\Interfaces;


use App\Model\Topic;

interface IntegrationInterface
{
    public function clientConfig(): array;

    public function getTopic(): Topic;

    public function getComments(): array;

    public function execute();
}
