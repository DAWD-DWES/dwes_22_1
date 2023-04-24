SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


CREATE DATABASE IF NOT EXISTS hangman DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE hangman;


DROP TABLE IF EXISTS usuarios;
CREATE TABLE usuarios (
  id int(11) NOT NULL,
  nombre varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  clave varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  email varchar(60) COLLATE utf8_spanish_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;



INSERT INTO usuarios (id, nombre, clave, email) VALUES
(1, 'pepe', '123456', 'pepe@gmail.com');


ALTER TABLE usuarios
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY nombre (nombre),
  ADD UNIQUE KEY email (email);


ALTER TABLE usuarios
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;


