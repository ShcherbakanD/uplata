<?php

declare(strict_types=1);

namespace App\Model;


class Comment
{
    /** @var string  */
    private $author;
    /** @var string  */
    private $value;
    /** @var string */
    private $created_at;
    /** @var string */
    private $integration_name;

    /**
     * Comment constructor.
     * @param string $author
     * @param string $value
     * @param string $integrationName
     * @param string $created_at
     */
    public function __construct(string $author, string $value, string $integrationName, string $created_at)
    {
        $this->author                       = $author;
        $this->value                        = $value;
        $this->created_at                   = $created_at;
        $this->integration_name             = $integrationName;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getIntegrationName(): string
    {
        return $this->integration_name;
    }

    public function getJson(): string
    {
        return json_encode([
            'author' => $this->author,
            'value' => $this->value,
            'created_at' => $this->created_at,
            'integration_name' => $this->integration_name,
        ], JSON_UNESCAPED_UNICODE);
    }
}