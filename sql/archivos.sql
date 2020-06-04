-- Archivos


SELECT * FROM tbl_archivos ORDER BY id DESC LIMIT 100;

-- DROP TABLE tbl_archivos;
CREATE TABLE `tbl_archivos` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`uu_id` VARCHAR(150) DEFAULT NULL,
	
	`id_documento` INT NULL,
	`url` TEXT DEFAULT NULL,
	`ruta_fisica` TEXT DEFAULT NULL,
	`tipo` VARCHAR(150) DEFAULT NULL,
	`peso` VARCHAR(150) DEFAULT NULL,
	`token` VARCHAR(150) DEFAULT NULL,

	`extension` VARCHAR(150) DEFAULT NULL,
	`tipo_documento` VARCHAR(150) DEFAULT NULL,
	`nombre_archivo` VARCHAR(150) DEFAULT NULL,
	`nombre_fisico` VARCHAR(150) DEFAULT NULL,

	`id_usuario` INT DEFAULT NULL,
	`usuario` VARCHAR(150) DEFAULT NULL,

	`deleted_at` timestamp NULL DEFAULT NULL,
	`created_at` timestamp NULL DEFAULT NULL,
	`updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=UTF8MB4_UNICODE_CI;
