<?php
declare(strict_types=1);

namespace App\Integrations;


use App\Integrations\Interfaces\IntegrationInterface;
use App\Model\Topic;
use DOMXPath;
use GuzzleHttp\Client;

abstract class AbstractIntegration implements IntegrationInterface
{
    const INTEGRATION_NAME = 'NAME';

    // AUTH KEYS
    const AUTH = 'AUTH';
    const AUTH_LOGIN = 'LOGIN';
    const AUTH_PASSWORD = 'PASSWORD';

    // URL KEYS
    const URL = 'URL';
    const URL_BASE = 'URL_BASE';
    const URL_LOGIN = 'URL_LOGIN';
    const URL_TOPIC = 'URL_TOPIC';
    const URL_TOPIC_ID = 'URL_TOPIC_ID';

    protected $client;
    /** @var DOMXPath */
    protected $data;

    protected $topic;

    protected $comments;

    protected $integrationConfig;

    protected $page = 1;

    public function __construct(array $integrationConfig)
    {
        $this->integrationConfig = $integrationConfig;
        $this->client = new Client($this->clientConfig());
    }

    abstract function clientConfig(): array;

    abstract function getComments(): array;

    abstract function getTopic(): Topic;

    abstract function processData($url, $page);

    public function getData(string $url, array $params = [])
    {
        return $this->client->get($url, $params);
    }

    public function createDom(string $data)
    {
        // FIX XPATH ERRORS
        // TODO
        $dom = new \DOMDocument();
        $dom->recover = true;
        $dom->strictErrorChecking = false;
        $dom->loadHTML($data);
        return new \DOMXPath($dom);
    }

    public function setTopic(Topic $topic)
    {
        $this->topic = $topic;
    }

    public function setComments(array $comments)
    {
        $this->comments = $comments;
    }

    public function process()
    {
        $dom = @$this->createDom($this->processData($this->integrationConfig[self::URL][self::URL_TOPIC], $this->page));
        $this->setTopic($this->getTopic($dom));
        $this->setComments($this->getComments($dom));
    }
}
