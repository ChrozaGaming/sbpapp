create table earnings (
    id int unsigned auto_increment primary key,
    amount decimal(10, 2) not null,
    record_date date not null
) engine = InnoDB;