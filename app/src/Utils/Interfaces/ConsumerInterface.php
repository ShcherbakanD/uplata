<?php

declare(strict_types=1);

namespace App\Utils\Interfaces;


interface ConsumerInterface
{
    public function load();

    public function execute(array $data);
}
