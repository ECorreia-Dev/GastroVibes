drop database bd_gastrovibes;
create database bd_gastrovibes;
use bd_gastrovibes;

-- usuario
create table tbl_usuario(
id int auto_increment unique primary key,
usuario varchar(10) not null,
email varchar(45) not null,
nome varchar(45)not null,
senha varchar(45)not null,
foto varchar(100)not null default'img/usuario_default.png',
data_nascimento date not null
)ENGINE = InnoDB;

-- publicacoes
create table tbl_publicacoes(
  id int auto_increment primary key not null unique,
  foto varchar(100) not null default'default-150x150.png',
  nome varchar(80) not null,
  conteudo text,
  status enum('ativo','inativo') not null,
  -- atr. like removido, tornou-se tbl separada.
  usuario_id int not null,
  foreign key (usuario_id) references tbl_usuario(id)
)ENGINE = INNODB;

-- favoritas
create table tbl_favoritas(
  id int auto_increment primary key not null,
  publicacoes_id int,
  foreign key (publicacoes_id) references tbl_publicacoes(id),
  usuario_id int,
  foreign key (usuario_id) references tbl_usuario(id)
)ENGINE = INNODB;

-- like
create table tbl_like(
  id int auto_increment primary key not null,
  usuario_id int not null,
  foreign key (usuario_id) references tbl_usuario(id),
  publicacao_id int not null,
  foreign key (publicacao_id) references tbl_publicacoes(id),
  constraint uq_cons unique (usuario_id, publicacao_id)
)ENGINE = INNODB;

-- seguindo
create table tbl_seguindo(
  id int auto_increment primary key not null,
  -- quem é seguido
  usuario_id int,
  foreign key (usuario_id) references tbl_usuario(id),
  -- quem segue
  seguidor_id int,
  foreign key (seguidor_id) references tbl_usuario(id),
  constraint uq_cons unique (usuario_id, seguidor_id)
);

create view ver_likes
as select count(*) as quant_likes, publicacao_id from tbl_like 
group by publicacao_id;

create view ver_seguidores as
-- r = relacionamento, u = usuario (seguido), s = seguidor (quem segue)
select
  r.*,
  u.nome as seguido,
  s.nome as seguidor,
  u.id as uid
from tbl_seguindo r
join tbl_usuario u on u.id = r.usuario_id
join tbl_usuario s on s.id = r.seguidor_id;

create view ver_publicacoes as
select
  p.*,
  u.foto as ufoto,
  u.nome as unome
from tbl_publicacoes p
join tbl_usuario u on u.id = p.usuario_id;

create view ver_favoritas as
select
  p.*,
  u.id as uid
from tbl_favoritas f
join tbl_publicacoes p on p.id = f.publicacoes_id
join tbl_usuario u on u.id = f.usuario_id;

insert into tbl_usuario (id, usuario, email, nome, senha, foto, data_nascimento) values
(null, "admin", "admin@gastrovibes.com", "Administrador", "gastrovibes", "usuario_default.png", "2000/01/01"),
(null, "sure", "sure@gastrovibes.com", "Lucas Vinícius", "designer", "usuario_default.png", "2000/01/01");

insert into tbl_publicacoes (id, foto, nome, conteudo, usuario_id) values
(null, 'usuario_default.png', 'Teste 1', 'Lorem Ipsum', 1),
(null, 'usuario_default.png', 'Teste 2', 'Lorem Ipsum', 1),
(null, 'usuario_default.png', 'Teste 3', 'Lorem Ipsum', 2);
(null, 'usuario_default.png', 'Teste 4', 'Lorem Ipsum', 2);

insert into tbl_favoritas (id, publicacoes_id, usuario_id) values
(null, 1, 1),
(null, 2, 2);

insert into tbl_like (id, usuario_id, publicacao_id) values
(null, 1, 1),
(null, 1, 2),
(null, 1, 3),
(null, 1, 4),
(null, 2, 1);

select * from tbl_usuario;
select * from tbl_publicacoes;
select * from tbl_favoritas;
