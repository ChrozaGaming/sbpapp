create table invoices
(
    id           int auto_increment
        primary key,
    invoice_date date                                     not null,
    is_paid      tinyint(1)     default 0                 null,
    created_at   timestamp      default CURRENT_TIMESTAMP null,
    client_id    int unsigned                             not null,
    amount_due   decimal(10, 2)                           not null,
    amount_paid  decimal(10, 2) default 0.00              not null,
    due_date     date                                     not null,
    notes        text                                     null
);

