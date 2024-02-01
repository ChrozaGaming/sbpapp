create table users
(
    id         int auto_increment
        primary key,
    email      varchar(255)                        not null,
    password   varchar(255)                        not null,
    created_at timestamp default CURRENT_TIMESTAMP null
);

