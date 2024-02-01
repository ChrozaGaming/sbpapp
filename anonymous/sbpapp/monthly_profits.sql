create table monthly_profits
(
    id                       int auto_increment
        primary key,
    month_year               date                                not null,
    profit                   decimal(10, 2)                      not null,
    previous_month_profit    decimal(10, 2)                      not null,
    profit_change_percentage decimal(5, 2)                       not null,
    created_at               timestamp default CURRENT_TIMESTAMP null
);

