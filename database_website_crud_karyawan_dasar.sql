CREATE DATABASE crud_app;
USE crud_app;

CREATE TABLE users (
    nomor_induk VARCHAR(20) PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15) NOT NULL
);

CREATE TABLE karyawan (
    nomor_induk VARCHAR(20) PRIMARY KEY,
    nama_karyawan VARCHAR(250) NOT NULL,
    jenis_kelamin VARCHAR(20) NOT NULL,
    email VARCHAR(250) NOT NULL,
    no_telepon INT NOT NULL,
    jabatan VARCHAR(250) NOT NULL
);
alter table karyawan add foto_karyawan varchar(255) not null after nama_karyawan;
alter table karyawan add alamat varchar(255) not null;
alter table karyawan add tanggal_lahir date;
alter table karyawan add tanggal_bergabung date;
alter table karyawan add gaji decimal(10,2) not null;
alter table karyawan add status_karyawan enum('Tetap', 'Kontrak', 'Magang') not null;
select * from karyawan;