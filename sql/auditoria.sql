-- Auditoria

SELECT * FROM tbl_auditoria ORDER BY id DESC LIMIT 100;


-- DROP tbl_auditoria;
CREATE TABLE `tbl_auditoria` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`uu_id` VARCHAR(150) DEFAULT NULL,
	
	`id_usuario` INT NULL,
	`usuario` VARCHAR(150) DEFAULT NULL,

	`modulo` VARCHAR(150) DEFAULT NULL,
	`documento` VARCHAR(150) DEFAULT NULL,
	`id_documento` INT DEFAULT NULL,
	
	`glosa` TEXT DEFAULT NULL,
	`json` TEXT DEFAULT NULL,

	`deleted_at` timestamp NULL DEFAULT NULL,
	`created_at` timestamp NULL DEFAULT NULL,
	`updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=UTF8MB4_UNICODE_CI;

