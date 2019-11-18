<?php

declare(strict_types=1);

namespace App\Command;


use App\Utils\Interfaces\CommandInterface;

abstract class AbstractCommand implements CommandInterface
{
    /**
     * @param array $args
     */
    abstract public function execute(array $args): void;

    /**
     * @return string
     */
    abstract public function getName(): string;
}