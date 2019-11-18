<?php
declare(strict_types=1);

namespace App\Utils;


use App\Command\LoadIntegrationCommand;
use App\Consumer\SaveCommentConsumer;
use App\Consumer\SaveTopicConsumer;
use Exception;

class Application
{
    /** @var Kernel */
    private $kernel;

    /** @var array */
    private $commands = [
        'app:load-integration',
        'app:run-consumer'
    ];

    /**
     * Application constructor.
     * @param Kernel $kernel
     */
    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @param string $command
     * @param array $arg
     * @throws Exception
     */
    public function runCommand(string $command, $arg)
    {
        if (!in_array($command, $this->commands)) {
            throw new Exception('Command not found.');
        }
        // SWITCH TO CONSOLE COMMAND
        switch ($command) {
            case 'app:load-integration':
                $this->kernel->getDI()->get(LoadIntegrationCommand::class)->execute($this->kernel->buildYamlData($this->kernel->getIntegrationConfigPath($arg)));
                break;
            case 'app:run-consumer':
                switch ($arg[0]) {
                    case 'save_topic':
                        $this->kernel->getDI()->get(SaveTopicConsumer::class)->load();
                        break;
                    case 'save_comment':
                        $this->kernel->getDI()->get(SaveCommentConsumer::class)->load();
                        break;
                }
                break;
        }
    }
}
