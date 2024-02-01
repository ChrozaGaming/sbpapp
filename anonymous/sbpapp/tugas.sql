create table tugas
(
    id                int auto_increment
        primary key,
    judul             varchar(255)                                                     not null,
    deskripsi         text                                                             null,
    status            enum ('selesai', 'belum', 'dalam_proses')                        not null,
    tanggal_penugasan date                                                             not null,
    tanggal_deadline  date                                                             null,
    created_at        timestamp default CURRENT_TIMESTAMP                              null,
    kategori          enum ('Completed', 'Inprogress', 'On Hold', 'Pending', 'Review') not null,
    jumlah            int                                                              not null
);

