create table sales_overview
(
    id           int auto_increment
        primary key,
    year         year           not null,
    totalSales   decimal(10, 2) not null,
    totalRevenue decimal(10, 2) not null
);

