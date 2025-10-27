/* Tabla de las recetas */
CREATE TABLE `kitchentag`.`recetas` (`id` INT NOT NULL AUTO_INCREMENT , `receta` VARCHAR(50) NOT NULL COMMENT 'Nombre de la receta' , `instrucciones` VARCHAR(300) NOT NULL , `produce` INT NOT NULL COMMENT 'Que elaborado produce' , `cantidad_producida` INT NOT NULL , `tipo_cantidad` INT NOT NULL , PRIMARY KEY (`id`), UNIQUE (`receta`)) ENGINE = InnoDB;
ALTER TABLE `recetas` ADD INDEX(`produce`);
