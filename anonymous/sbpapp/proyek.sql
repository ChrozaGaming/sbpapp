create table proyek
(
    id              int auto_increment
        primary key,
    nama_proyek     varchar(255)                        not null,
    status          enum ('selesai', 'belum_selesai')   not null,
    tanggal_mulai   date                                not null,
    tanggal_selesai date                                null,
    created_at      timestamp default CURRENT_TIMESTAMP null
);

