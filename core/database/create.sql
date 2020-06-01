-- drop database ospedale;
create database IF NOT EXISTS  ospedale; 
use heroku_aea42162b6df481;
-- use ospedale; 

create table IF NOT EXISTS pazienti (
  id int(6) not null auto_increment primary key,
  nome varchar(100) not null,
  cognome varchar (100) not null
) engine = innoDB;

create table IF NOT EXISTS farmaci (
  id int(6) not null auto_increment primary key,
  nome varchar(100) not null,
  qrcode varchar(100) null default "",
  barcode varchar(100) null default ""
) engine = innoDB;

create table IF NOT EXISTS reparti(
  id int(6) not null auto_increment primary key,
  nome varchar(50) not null
)engine = innoDB;

create table IF NOT EXISTS medici (
  id int(6) not null auto_increment primary key,
  nome varchar(100) not null,
  ruolo enum("dottore","infermiere"),
  email varchar(150) not null unique,
  pass  varchar (255) not null,
  id_reparto int(6) null default null,
  CONSTRAINT FK_medico_reparto FOREIGN KEY (id_reparto)
  REFERENCES reparti(id) 
  ON DELETE SET NULL
) engine = innoDB;

CREATE TABLE IF NOT EXISTS prescrizioni (
  id int(6) not null auto_increment primary key,
  id_farmaco int(6) not null,
  id_paziente int(6) not null,
  id_medico int(6) not null,
  dose_assunzione varchar(100) null default "",
  tempi_assunzione varchar(100) null default "",
  note varchar(100) null default "",
  qta float(3,2) not null default 1,
  data_inserimento DATETIME default now(),

  CONSTRAINT FK_prescrizione_farmaco FOREIGN KEY (id_farmaco)
  REFERENCES farmaci(id),

  CONSTRAINT FK_prescrizione_paziente FOREIGN KEY (id_paziente)
  REFERENCES pazienti(id) on delete cascade on update cascade,

  CONSTRAINT FK_prescrizione_medico FOREIGN KEY (id_medico)
  REFERENCES medici(id)

) engine = innoDB;

CREATE TABLE IF NOT EXISTS magazzino (
  id int(6) not null auto_increment primary key,
  id_reparto int(6) not null,
  id_farmaco int(6) not null,
  qta DOUBLE(10,4) null default 0,
  max_qta DOUBLE(10,4) null default 0, 
  CONSTRAINT FK_magazzino_reparto FOREIGN KEY (id_reparto)
  REFERENCES reparti(id),

  CONSTRAINT FK_magazzino_farmaco FOREIGN KEY (id_farmaco)
  REFERENCES farmaci(id) 

) engine = innoDB;