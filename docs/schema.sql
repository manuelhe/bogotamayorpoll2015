DROP TABLE IF EXISTS `answers`;
DROP TABLE IF EXISTS `stratif`;
DROP TABLE IF EXISTS `candidate`;
DROP TABLE IF EXISTS `location`;
DROP TABLE IF EXISTS `salary`;
DROP TABLE IF EXISTS `religion`;

CREATE TABLE `stratif` (
  `idstratif`  INT UNSIGNED NOT NULL,
  `name` VARCHAR(80),
  PRIMARY KEY (`idstratif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `stratif`
VALUES
(1,"1"),
(2,"2"),
(3,"3"),
(4,"4"),
(5,"5"),
(6,"6"),
(7,"6+"),
(8,"No tengo servicio eléctrico en mi casa / vivo debajo de un puente");

CREATE TABLE `location` (
  `idlocation`  INT UNSIGNED NOT NULL,
  `name` VARCHAR(45),
  PRIMARY KEY (`idlocation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `location`
VALUES
(1,"Amazonas"),
(2,"Antioquia"),
(3,"Arauca"),
(4,"Atlántico"),
(5,"Bogotá"),
(6,"Bolivar"),
(7,"Boyacá"),
(8,"Caldas"),
(9,"Caquetá"),
(10,"Casanare"),
(11,"Cauca"),
(12,"Cesar"),
(13,"Chocó"),
(14,"Córdoba"),
(15,"Cundinamarca"),
(16,"Guanía"),
(17,"Guaviare"),
(18,"Huila"),
(19,"La Guajira"),
(20,"Magdalena"),
(21,"Meta"),
(22,"Nariño"),
(23,"Norte de Santander"),
(24,"Putumayo"),
(25,"Quindío"),
(26,"Risaralda"),
(27,"San Andrés y Providencia"),
(28,"Santander"),
(29,"Sucre"),
(30,"Tolima"),
(31,"Valle del Cauca"),
(32,"Vaupés"),
(33,"Vichada"),
(34,"Fuera del país");

CREATE TABLE `salary` (
  `idsalary`  INT UNSIGNED NOT NULL,
  `name` VARCHAR(60),
  PRIMARY KEY (`idsalary`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `salary`
VALUES
(1,"Menos de un SMMLV"),
(2,"1 a 2 SMMLV"),
(3,"3 SMMLV"),
(4,"4 a 6 SMMLV"),
(5,"7 a 10 SMMLV"),
(6,"11 a 15 SMMLV"),
(7,"16 a 30 SMMLV"),
(8,"Yo, o mi papá, somos accionistas de Invercolsa"),
(9,"Soy freelance, no se que es recibir plata mensualmente");

CREATE TABLE `religion` (
  `idreligion`  INT UNSIGNED NOT NULL,
  `name` VARCHAR(70),
  PRIMARY KEY (`idreligion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `religion`
VALUES
(1,"Ateo"),
(2,"Bahaísmo"),
(3,"Budismo"),
(4,"Confusianismo"),
(5,"Católico, Apostólico y Romano"),
(6,"Católico de otro tipo"),
(7,"Rastafari"),
(8,"Cristiano Ortodoxo"),
(9,"Mormón"),
(10,"Anglicano"),
(11,"Luterano"),
(12,"Calvinista"),
(13,"Baptista"),
(14,"Metodista"),
(15,"Unitario"),
(16,"Universalista"),
(17,"Otra secta cristiana protestante"),
(18,"Adventista de algún tipo"),
(19,"Testigo de Jehová"),
(20,"Espiritista"),
(21,"Hinduista de algún tipo"),
(22,"Alguna práctica tribal indígena"),
(23,"Fetichista"),
(24,"Islamista"),
(25,"Yainista"),
(26,"Judío"),
(27,"Neopaganista de algún tipo (como la Wicca o el Celtismo)"),
(28,"Pastafarianista (con o sin albóndigas)"),
(29,"Satanista"),
(30,"Shinto"),
(31,"Sintoista"),
(32,"Zoroastrasista"),
(33,"Humanista Secular"),
(34,"Gnóstico"),
(35,"Agnóstico"),
(36,"Ninguna/Otra/No le importa");

CREATE TABLE `candidate` (
  `idcandidate`  INT UNSIGNED NOT NULL,
  `name` VARCHAR(60),
  PRIMARY KEY (`idcandidate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `candidate`
VALUES
(1,"Germán Vargas Lleras"),
(2,"Gustavo Petro"),
(3,"Humberto de la Calle"),
(4,"Iván Duque Márquez"),
(5,"Piedad Córdoba"),
(6,"Sergio Fajardo"),
(7,"Viviane Morales"),
(8,"En blanco/Nulo");

CREATE TABLE `answers` (
  `date` DATETIME NOT NULL,
  `age` ENUM('18-22', '23-28', '29-33', '34-40', '41-48', '49-56', '57-64', '65-100', '+100') NOT NULL,
  `gender` ENUM('Masculino', 'Femenino', 'LGBTQIAP') NOT NULL,
  `bloodtype` ENUM('A+', 'B+', 'O+', 'AB+', 'A-', 'B-', 'O-', 'AB-') NOT NULL,
  `willvote` ENUM('Si', 'No', 'No me decido/No me importa') NOT NULL,
  `politicparty` ENUM('Si', 'No') NOT NULL,
  `idstratif` INT UNSIGNED NOT NULL,
  `idlocation` INT UNSIGNED NOT NULL,
  `idsalary` INT UNSIGNED NOT NULL,
  `idreligion` INT UNSIGNED NOT NULL,
  `idcandidate` INT UNSIGNED NOT NULL,
  INDEX `age_idx` (`age` ASC),
  INDEX `gender_idx` (`gender` ASC),
  INDEX `bloodtype_idx` (`bloodtype` ASC),
  INDEX `willvote_idx` (`willvote` ASC),
  INDEX `politicparty_idx` (`politicparty` ASC),
  INDEX `idstratif_idx` (`idstratif` ASC),
  INDEX `idlocation_idx` (`idlocation` ASC),
  INDEX `idsalary_idx` (`idsalary` ASC),
  INDEX `idreligion_idx` (`idreligion` ASC),
  INDEX `idcandidate_idx` (`idcandidate` ASC),
  INDEX `age_candidate_idx` (`age` ASC, `idcandidate` ASC),
  INDEX `gender_candidate_idx` (`gender` ASC, `idcandidate` ASC),
  INDEX `bloodtype_candidate_idx` (`bloodtype` ASC, `idcandidate` ASC),
  INDEX `willvote_candidate_idx` (`willvote` ASC, `idcandidate` ASC),
  INDEX `politicparty_candidate_idx` (`politicparty` ASC, `idcandidate` ASC),
  INDEX `stratif_candidate_idx` (`idstratif` ASC, `idcandidate` ASC),
  INDEX `location_candidate_idx` (`idlocation` ASC, `idcandidate` ASC),
  INDEX `salary_candidate_idx` (`idsalary` ASC, `idcandidate` ASC),
  INDEX `religion_candidate_idx` (`idreligion` ASC, `idcandidate` ASC),
  CONSTRAINT `idstratif_ibfk` FOREIGN KEY (`idstratif`) REFERENCES `stratif` (`idstratif`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idlocation_ibfk` FOREIGN KEY (`idlocation`) REFERENCES `location` (`idlocation`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idsalary_ibfk` FOREIGN KEY (`idsalary`) REFERENCES `salary` (`idsalary`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idreligion_ibfk` FOREIGN KEY (`idreligion`) REFERENCES `religion` (`idreligion`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idcandidate_ibfk` FOREIGN KEY (`idcandidate`) REFERENCES `candidate` (`idcandidate`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
