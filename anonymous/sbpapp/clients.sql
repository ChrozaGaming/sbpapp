create table clients
(
    client_id   int unsigned auto_increment
        primary key,
    client_name varchar(255)                         not null,
    created_at  timestamp  default CURRENT_TIMESTAMP null,
    email       varchar(255)                         not null,
    status      tinyint(1) default 1                 not null
);

