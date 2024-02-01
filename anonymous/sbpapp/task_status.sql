create table task_status
(
    status_id   int unsigned auto_increment
        primary key,
    status_name varchar(191) not null,
    constraint status_name
        unique (status_name)
);

