create table daily_leaves
(
    id           int auto_increment
        primary key,
    date         date not null,
    leaves_taken int  not null,
    total_leaves int  not null
);

