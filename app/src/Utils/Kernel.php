<?php
declare(strict_types=1);

namespace App\Utils;

use Dotenv\Dotenv;
use Exception;
use Psr\Container\ContainerInterface;

class Kernel
{
    /** @var array */
    private $env;

    /** @var ContainerInterface */
    private $container;

    /**
     * Kernel constructor.
     * @param string $envPath
     * @param string $depPath
     * @throws Exception
     */
    public function __construct(string $envPath, string $depPath)
    {
        $this->env = $this->buildEnvData($envPath);
        $this->container = $this->buildContainer($depPath);
    }

    /**
     * @param string $envPath
     * @return array
     * @throws Exception
     */
    private function buildEnvData(string $envPath): array
    {
        $dotenv = Dotenv::create($envPath);
        return $dotenv->load();
    }

    /**
     * @param string $yamlPath
     * @return array
     * @throws Exception
     */
    public function buildYamlData(string $yamlPath): array
    {
        if (!file_exists($yamlPath)){
            throw new Exception('Yaml path does not exist.');
        }
        return yaml_parse(file_get_contents($yamlPath));
    }

    /**
     * @param string $depPath
     * @return ContainerInterface
     * @throws Exception
     */
    private function buildContainer(string $depPath): ContainerInterface
    {
        return new Container($depPath);
    }

    /**
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    public function getEnvParam(string $name)
    {
        if (!array_key_exists(trim($name), $this->env)) {
            throw new Exception('Undefined env param.');
        }
        return $this->env[trim($name)];
    }

    public function getIntegrationConfigPath(array $args): string
    {
        // bad
        if (!file_exists('/var/www/html/app/config/forums/' . strtolower(current($args)) . '.yaml')) {
            throw new Exception('Integration config file does not exist');
        }
        return '/var/www/html/app/config/forums/' . strtolower(current($args)) . '.yaml';
    }

    /**
     * @return ContainerInterface
     */
    public function getDI(): ContainerInterface
    {
        return $this->container;
    }
}
