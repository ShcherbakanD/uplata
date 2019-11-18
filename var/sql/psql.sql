create table integration_comments
(
    id serial not null
        constraint integration_comments_pk
            primary key,
    author VARCHAR(50) default null,
    value VARCHAR(1050) default null,
    created_at VARCHAR(25) not null,
    integration_name varchar(50) not null
);

create table integration_topics
(
    id serial not null
        constraint integration_topic_pk
            primary key,
    name varchar(255) default null,
    author VARCHAR(50) default null,
    value VARCHAR(2000) default null,
    created_at VARCHAR(25) not null,
    integration_name VARCHAR(50) not null
);