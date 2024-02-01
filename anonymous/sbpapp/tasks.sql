create table tasks
(
    task_id    int unsigned auto_increment
        primary key,
    task_name  varchar(255)                        not null,
    status_id  int unsigned                        not null,
    due_date   date                                null,
    created_at timestamp default CURRENT_TIMESTAMP null
);

create index status_id
    on tasks (status_id);

