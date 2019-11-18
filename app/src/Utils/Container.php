<?php

declare(strict_types=1);

namespace App\Utils;


use App\Utils\Exceptions\ContainerIdNotFoundException;
use Exception;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;

class Container implements ContainerInterface
{
    /** @var string */
    private const ALL = "all";

    /** @var string */
    private const SINGLETON = "singleton";

    /** @var array */
    private $dep;

    /**
     * Container constructor.
     * @param string $dep
     * @throws Exception
     */
    public function __construct(string $dep)
    {
        if (!file_exists($dep)) {
            throw new Exception('Dependencies file not found');
        }
        $this->dep = require $dep;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has($id)
    {
        if (!array_key_exists($id, $this->dep)) {
            return false;
        }
        return true;
    }

    /**
     * @param string $id
     * @return mixed|object
     * @throws ContainerIdNotFoundException
     * @throws ReflectionException
     */
    public function get($id)
    {
        if (!array_key_exists($id, $this->dep[self::ALL])) {
            if (!array_key_exists($id, $this->dep[self::SINGLETON])) {
                throw new ContainerIdNotFoundException('Container id not found');
            }
        }
        return $this->resolve($id);
    }

    /**
     * @param $id
     * @return object
     * @throws ReflectionException
     */
    private function resolve($id)
    {
        $args = [];
        $implementation = array_key_first($this->dep[self::ALL][$id]);
        foreach ($this->dep[self::ALL][$id][$implementation] as $arg) {
            if (array_key_exists($arg, $this->dep[self::ALL])) {
                $args[] = $this->resolve($arg);
            } elseif (array_key_exists($arg, $this->dep[self::SINGLETON])) {
                $args[] = $this->dep[self::SINGLETON][$arg];
            } else {
                throw new Exception('Undefined dependencies');
            }
        }
        $ref = new ReflectionClass($implementation);
        return $ref->newInstanceArgs($args);
    }
}