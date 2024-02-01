create table pegawai_absensi
(
    id      int auto_increment
        primary key,
    nama    varchar(100)                 not null,
    tanggal date                         not null,
    status  enum ('Pending', 'Approved') not null
)
    engine = InnoDB;

