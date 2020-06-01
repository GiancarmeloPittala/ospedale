create database IF NOT EXISTS ospedale;
use ospedale;

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
  tipo enum("dottore","infermiere"),
  email varchar(150) not null unique,
  pass  varchar (100) not null unique,
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
  REFERENCES pazienti(id),

  CONSTRAINT FK_prescrizione_medico FOREIGN KEY (id_medico)
  REFERENCES medici(id) 

) engine = innoDB;

CREATE TABLE IF NOT EXISTS magazzino (
  id int(6) not null auto_increment primary key,
  id_reparto int(6) not null,
  id_farmaco int(6) not null,
  qta float(3,2) null default 0,
  max_qta float(3,2) null default 0, 
  CONSTRAINT FK_magazzino_reparto FOREIGN KEY (id_reparto)
  REFERENCES reparti(id),

  CONSTRAINT FK_magazzino_farmaco FOREIGN KEY (id_farmaco)
  REFERENCES farmaci(id) 

) engine = innoDB;

insert into pazienti (nome, cognome)
  values("pippo","paperino");

insert into reparti (nome)
  values("Cardiochirurgia");

insert into medici (nome, tipo, id_reparto)
values ("pippo dottore", "dottore", 1), ("paperino infermiere", "infermiere", 1);


insert into farmaci (nome)
  values ("farmaco 1");

insert into magazzino (id_reparto, id_farmaco, qta, max_qta)
values(1,1, 10, 20);

insert into prescrizioni (id_farmaco, id_paziente, id_medico, dose_assunzione, tempi_assunzione, note)
  values (1,1,1,"2", "dopo i pasti", "");

select f.id, f.qta,f.dose_assunzione, f.tempi_assunzione, f.note, fa.nome as nome_farmaco, fa.barcode as barcode_farmaco, fa.qrcode as qrcode_farmaco, m.nome as nome_medico from prescrizioni f 
left join farmaci fa on f.id_farmaco = fa.id 
left join pazienti p on f.id_paziente = p.id 
left join medici m on f.id_medico = m.id;


  