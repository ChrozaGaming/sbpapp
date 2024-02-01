create table employees
(
    employee_id int unsigned auto_increment
        primary key,
    first_name  varchar(100)                               not null,
    last_name   varchar(100)                               not null,
    email       varchar(191)                               not null,
    position    varchar(100)                               null,
    department  varchar(100)                               null,
    start_date  date                                       null,
    created_at  timestamp    default CURRENT_TIMESTAMP     null,
    name        varchar(100)                               not null,
    avatar_url  varchar(255) default 'assets/img/user.jpg' null,
    constraint email
        unique (email)
);

