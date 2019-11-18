<?php

use App\Command\LoadIntegrationCommand;
use App\Consumer\SaveCommentConsumer;
use App\Consumer\SaveTopicConsumer;
use App\Producer\SaveCommentProducer;
use App\Producer\SaveTopicProducer;
use App\Repository\Comment\CommentRepositoryInterface;
use App\Repository\Comment\PDOCommentRepository;
use App\Repository\Topic\PDOTopicRepository;
use App\Repository\Topic\TopicRepositoryInterface;
use App\Service\SaveCommentService;
use App\Service\SaveTopicService;

return [
    'all' => [

        #Commands
        LoadIntegrationCommand::class => [
            LoadIntegrationCommand::class => [
                SaveTopicProducer::class,
                SaveCommentProducer::class
            ]
        ],
        SaveTopicProducer::class => [
            SaveTopicProducer::class => [

            ]
        ],
        SaveCommentProducer::class => [
            SaveCommentProducer::class => [

            ]
        ],
        SaveTopicConsumer::class => [
            SaveTopicConsumer::class => [
                SaveTopicService::class
            ]
        ],
        SaveCommentConsumer::class => [
            SaveCommentConsumer::class => [
                SaveCommentService::class
            ]
        ],
        SaveCommentService::class => [
            SaveCommentService::class => [
                CommentRepositoryInterface::class
            ]
        ],
        SaveTopicService::class => [
            SaveTopicService::class => [
                TopicRepositoryInterface::class
            ]
        ],
        TopicRepositoryInterface::class => [
            PDOTopicRepository::class => [
                PDO::class
            ]
        ],
        CommentRepositoryInterface::class => [
            PDOCommentRepository::class => [
                PDO::class
            ]
        ]
    ],
    'singleton' => [
        PDO::class => new PDO(
            sprintf(
                'pgsql:host=%s;port=%s;dbname=%s;user=%s;password=%s',
                getenv('DB_HOST'),
                getenv('DB_PORT'),
                getenv('DB_NAME'),
                getenv('DB_USER'),
                getenv('DB_PASS')
            )
        )
    ]
];
