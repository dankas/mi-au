-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           8.0.41 - MySQL Community Server - GPL
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.6.0.6765
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para meowdb
CREATE DATABASE IF NOT EXISTS `meowdb` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `meowdb`;

-- Copiando estrutura para tabela meowdb.consulta
CREATE TABLE IF NOT EXISTS `consulta` (
  `idconsulta` int NOT NULL AUTO_INCREMENT,
  `nomevet` varchar(50) DEFAULT NULL,
  `descricao` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `pet` int DEFAULT NULL,
  `dataconsulta` date DEFAULT NULL,
  `img` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT '1',
  `datetimeregistro` datetime DEFAULT (now()),
  `tipoconsultaid` int DEFAULT NULL,
  PRIMARY KEY (`idconsulta`),
  KEY `FK_petconsulta` (`pet`),
  KEY `FK_consultatipo` (`tipoconsultaid`),
  CONSTRAINT `FK_consultatipo` FOREIGN KEY (`tipoconsultaid`) REFERENCES `tipo_consulta` (`idtipoconsulta`),
  CONSTRAINT `FK_petconsulta` FOREIGN KEY (`pet`) REFERENCES `pet` (`idpet`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela meowdb.consulta: ~4 rows (aproximadamente)
REPLACE INTO `consulta` (`idconsulta`, `nomevet`, `descricao`, `pet`, `dataconsulta`, `img`, `ativo`, `datetimeregistro`, `tipoconsultaid`) VALUES
	(1, 'Marcio', 'Checkup do ano.', 2, '2025-07-18', 'receita.png', 1, '2025-07-18 13:38:50', 1),
	(2, 'marcio', 'Atendimento', 3, '2025-07-18', 'receita2.png', 1, '2025-07-18 13:52:07', 1),
	(7, 'alberto', 'teste deupload', 2, '2025-07-01', '4975249656_d38024eafb_b-865942549.jpg', 1, '2025-07-21 16:27:30', 1),
	(8, 'alberto', 'vacina anti rabica.', 36, '2025-07-21', 'mlzhlkbq1q2c1.jpg', 1, '2025-07-21 17:11:44', 2);

-- Copiando estrutura para tabela meowdb.fotos
CREATE TABLE IF NOT EXISTS `fotos` (
  `idfoto` int NOT NULL AUTO_INCREMENT,
  `idpet` int DEFAULT '0',
  `img` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `datetimeregistro` datetime DEFAULT (now()),
  `ativo` int DEFAULT NULL,
  PRIMARY KEY (`idfoto`) USING BTREE,
  KEY `FKfotos` (`idpet`),
  CONSTRAINT `FKfotos` FOREIGN KEY (`idpet`) REFERENCES `pet` (`idpet`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela meowdb.fotos: ~0 rows (aproximadamente)
REPLACE INTO `fotos` (`idfoto`, `idpet`, `img`, `datetimeregistro`, `ativo`) VALUES
	(2, 2, 'gato-preto-balanco-GR-2238622803.jpg', '2025-07-21 20:00:59', 1),
	(7, 2, 'gato-preto-balanco-GR-2238622803.jpg', '2025-07-21 20:47:14', 0);

-- Copiando estrutura para tabela meowdb.imagens
CREATE TABLE IF NOT EXISTS `imagens` (
  `idimg` int NOT NULL AUTO_INCREMENT,
  `caminho` varchar(40) NOT NULL,
  `petid` int DEFAULT NULL,
  `tutorid` int DEFAULT NULL,
  `consultaid` int DEFAULT NULL,
  PRIMARY KEY (`idimg`),
  KEY `FKtutorimg` (`tutorid`),
  KEY `FKconsultaimg` (`consultaid`),
  KEY `FKpetimg` (`petid`),
  CONSTRAINT `FKconsultaimg` FOREIGN KEY (`consultaid`) REFERENCES `consulta` (`idconsulta`),
  CONSTRAINT `FKpetimg` FOREIGN KEY (`petid`) REFERENCES `pet` (`idpet`),
  CONSTRAINT `FKtutorimg` FOREIGN KEY (`tutorid`) REFERENCES `users` (`iduser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela meowdb.imagens: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela meowdb.pet
CREATE TABLE IF NOT EXISTS `pet` (
  `idpet` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(10) NOT NULL DEFAULT '0',
  `race` varchar(20) NOT NULL DEFAULT '0',
  `tipoid` int NOT NULL DEFAULT '0',
  `tutor` int NOT NULL DEFAULT (0),
  `bio` tinytext NOT NULL,
  `nascimento` date NOT NULL,
  `imgperfil` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT '1',
  `datetimeregistro` datetime DEFAULT (now()),
  PRIMARY KEY (`idpet`),
  KEY `FKtutor` (`tutor`),
  KEY `FKtipo` (`tipoid`) USING BTREE,
  CONSTRAINT `FKtipo` FOREIGN KEY (`tipoid`) REFERENCES `tipo` (`idtipo`),
  CONSTRAINT `FKtutor` FOREIGN KEY (`tutor`) REFERENCES `users` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela meowdb.pet: ~5 rows (aproximadamente)
REPLACE INTO `pet` (`idpet`, `nome`, `race`, `tipoid`, `tutor`, `bio`, `nascimento`, `imgperfil`, `ativo`, `datetimeregistro`) VALUES
	(1, 'Frajolaaa', 'srv', 1, 1, 'Frajola é definitivamente um gato', '2025-01-23', 'gatito.jpg', 0, '2025-06-28 10:13:48'),
	(2, 'salem', 'srv', 1, 1, 'Talvez não seja somente um gato e sim um brujo condenado a ser um gato por 100 anos.', '1832-07-05', 'salem.jpg', 1, '2025-06-28 10:13:48'),
	(3, 'pedro', 'caramelo', 2, 1, 'Pedro rouba chips, cuidado!!!!', '2022-03-23', 'caramelo1.jpg', 1, '2025-06-28 10:13:48'),
	(4, 'cookie', 'srv', 2, 1, 'Aparentemente a falta de uma orelha não é problema.', '2016-07-13', 'cao1.jpg', 1, '2025-06-28 10:13:48'),
	(36, 'CARLOS', 'SRV', 2, 1, 'UM CARAMELO AMARELO', '2025-06-24', 'caramelo.png', 1, '2025-07-13 19:51:50'),
	(39, 'tetinha', 'fiapo de manga', 2, 1, 'corre atras de carros', '2025-06-05', 'transferir (3).jpeg', 0, '2025-07-19 19:24:15'),
	(40, 'albertinho', 'srv', 2, 1, 'teste', '2025-05-01', 'transferir (1).jpeg', 0, '2025-07-21 16:18:19');

-- Copiando estrutura para tabela meowdb.tipo
CREATE TABLE IF NOT EXISTS `tipo` (
  `idtipo` int NOT NULL AUTO_INCREMENT,
  `tipo` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idtipo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela meowdb.tipo: ~2 rows (aproximadamente)
REPLACE INTO `tipo` (`idtipo`, `tipo`) VALUES
	(1, 'gato'),
	(2, 'cão');

-- Copiando estrutura para tabela meowdb.tipo_consulta
CREATE TABLE IF NOT EXISTS `tipo_consulta` (
  `idtipoconsulta` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idtipoconsulta`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela meowdb.tipo_consulta: ~0 rows (aproximadamente)
REPLACE INTO `tipo_consulta` (`idtipoconsulta`, `nome`) VALUES
	(1, 'Atendimento'),
	(2, 'Vacina');

-- Copiando estrutura para tabela meowdb.users
CREATE TABLE IF NOT EXISTS `users` (
  `iduser` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `password` varchar(10) DEFAULT NULL,
  `telefone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `imgprofile` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela meowdb.users: ~0 rows (aproximadamente)
REPLACE INTO `users` (`iduser`, `username`, `password`, `telefone`, `imgprofile`) VALUES
	(1, 'Daniel', 'teste1', '53998842323', 'users.png');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
