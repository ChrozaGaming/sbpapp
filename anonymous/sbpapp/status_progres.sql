create table status_progres
(
    id         int auto_increment
        primary key,
    kategori   varchar(50) not null,
    persentase int         not null,
    warna      varchar(50) not null
);

