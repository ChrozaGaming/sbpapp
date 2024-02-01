create table biaya
(
    id      int unsigned auto_increment
        primary key,
    jumlah  decimal(10, 2) not null,
    tanggal date           not null
)
    engine = InnoDB;

