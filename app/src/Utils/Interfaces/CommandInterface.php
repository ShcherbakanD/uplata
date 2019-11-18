<?php

declare(strict_types=1);

namespace App\Utils\Interfaces;


interface CommandInterface
{
    /**
     * @param array $args
     */
    public function execute(array $args): void;

    /**
     * @return string
     */
    public function getName(): string;
}