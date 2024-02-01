create table revenue
(
    id      int auto_increment
        primary key,
    year    date           not null,
    income  decimal(10, 2) not null,
    expense decimal(10, 2) not null
);

