create table users (
    id int auto_increment primary key,
    username varchar(50) unique not null,
    email varchar(60) unique not null,
    perfil enum("Admin", "Normal", "Guest") DEFAULT ("Guest"),
    imagen varchar(150) default "img/Capibara.jpeg"
);

-- Create database basededatos;
-- create user 'nombre'@'%' identified by 'contraseña';
-- grant all on basededatos.* to 'nombre'@'%';