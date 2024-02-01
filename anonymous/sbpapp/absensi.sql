create table absensi
(
    id      int auto_increment
        primary key,
    nama    varchar(255)                                   not null,
    tanggal date                                           not null,
    status  enum ('Pending', 'Approved') default 'Pending' null
);

