<?php
declare(strict_types=1);

namespace App\Integrations;


use App\Integrations\Exception\IntegrationException;
use App\Model\Comment;
use App\Model\Topic;

class ForumOdUa extends AbstractIntegration
{
    /**
     * @return array
     * @throws IntegrationException
     */
    public function clientConfig(): array
    {
        if (!$this->integrationConfig[self::AUTH][self::AUTH_LOGIN]) {
            throw new IntegrationException('Credentials invalid: login required.');
        }

        if (!$this->integrationConfig[self::AUTH][self::AUTH_PASSWORD]) {
            throw  new IntegrationException('Credentials invalid: password required');
        }

        return [
            'base_uri' => $this->integrationConfig[self::URL][self::URL_BASE],
            'headers' => [
                'do' => 'login'
            ],
            'form_params' => [
                'vb_login_username' => $this->integrationConfig[self::AUTH][self::AUTH_LOGIN],
                'vb_login_password' => '',
                'vb_login_password_hint' => '(unable to decode value)',
                'cookieuser' => 1,
                's' => '',
                'securitytoken' => 'guest',
                'do' => 'login',
                'vb_login_md5password' => md5($this->integrationConfig[self::AUTH][self::AUTH_PASSWORD]),
                'vb_login_md5password_utf' => md5($this->integrationConfig[self::AUTH][self::AUTH_PASSWORD])
            ],
        ];
    }

    /**
     * @return string
     */
    public function getHeaders()
    {
        return $this->getData($this->integrationConfig[self::URL][self::URL_LOGIN])->getHeaders();
    }

    /**
     * @param $url
     * @param $page
     * @return string
     */
    public function processData($url, $page)
    {
        $headers = $this->getHeaders();
        $data = $this->getData($url,
            [
                'headers' => $headers,
                'query' => [
                    't' => $this->integrationConfig[self::URL][self::URL_TOPIC_ID],
                    'page' => $page
                ]
            ])->getBody()->getContents();
        return $this->createDom($data);
    }

    /**
     * @return Topic
     */
    public function getTopic(): Topic
    {
        $page = 1;
        $dom = $this->processData($this->integrationConfig[self::URL][self::URL_TOPIC], $page);
        $userBlock = $dom->query("//div[@class='userinfo']//strong[text()]");
        $user = $userBlock->item(0)->nodeValue;

        $topicNameBlock = $dom->query("//h2[node()]");
        $topicName = $topicNameBlock->item(0)->nodeValue;

        $topicValueBlock = $dom->query("//blockquote[node()]");
        $topicValue = $topicValueBlock->item(0)->nodeValue;

        $dateBlock = $dom->query("//div[2]/div[7]/ol/li[*]/div[1]/span[1]/span");
        $dateBlockValue = $dateBlock->item(0)->nodeValue;

        return new Topic(trim($topicName), $user, trim($topicValue), $this->integrationConfig[self::INTEGRATION_NAME], $this->getDate($dateBlockValue));
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getComments(): array
    {
        $comments = [];
        $pageCounter = 1;
        while ($pageCounter <= $this->integrationConfig['PAGES']) {
            $dom = $this->processData($this->integrationConfig[self::URL][self::URL_TOPIC], $pageCounter);
            $comments = array_merge($comments, $this->parseComments($dom));
            $pageCounter++;
        }
        return $comments;
    }

    /**
     * @param $dom
     * @return array
     * @throws \Exception
     */
    private function parseComments($dom): array
    {
        $userBlock = $dom->query("//div[@class='userinfo']//strong[text()]");
        $topicValueBlock = $dom->query("//ol[@id='posts']//li[contains(@id, 'post')]//blockquote[@class='postcontent restore '][text()]");
        $date = $dom->query("//div[2]/div[7]/ol/li[*]/div[1]/span[1]/span");

        $i = count($userBlock);
        $counter = 0;
        $comments = [];
        while ($counter <= $i) {
            if ($userBlock->item($counter) == null) {
                $counter++;
                continue;
            }
            $user = $userBlock->item($counter)->nodeValue;
            $topicValue = $topicValueBlock->item($counter)->nodeValue;
            $dateValue = $date->item($counter)->nodeValue;
            $comments[] = new Comment($user, $topicValue, $this->integrationConfig[self::INTEGRATION_NAME], $this->getDate($dateValue));
            $counter++;
        }

        return $comments;
    }

    /**
     * @param $date
     * @return string
     */
    private function getDate(string $date): string
    {
        $explodedPart = explode(" ", $date);
        $explodedPart[0] = implode('-', array_reverse(explode('.', $explodedPart[0])));
        $timestamp = strtotime(implode(' ', $explodedPart));
        return date('Y-m-d H:i:s', $timestamp);
    }

    public function execute()
    {
        $this->process();
    }
}
