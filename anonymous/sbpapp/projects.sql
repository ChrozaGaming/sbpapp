create table projects
(
    id         int unsigned auto_increment
        primary key,
    name       varchar(255) not null,
    start_date date         null,
    end_date   date         null,
    status     varchar(30)  not null
);

