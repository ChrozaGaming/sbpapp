create table tiket
(
    id                int auto_increment
        primary key,
    subjek            varchar(255)                        not null,
    status            enum ('terbuka', 'tertutup')        not null,
    tanggal_pembukaan date                                not null,
    tanggal_penutupan date                                null,
    created_at        timestamp default CURRENT_TIMESTAMP null
);

