USE smto_new;

ALTER TABLE `detector`
ADD COLUMN `max_k_value` SMALLINT DEFAULT 255,
ADD COLUMN `avg_k_value` SMALLINT DEFAULT 255  AFTER `max_k_value`
;

ALTER TABLE `machine`
ADD COLUMN `s_values` VARCHAR(128) NULL DEFAULT '1,1,1,1',
ADD COLUMN `reasons_timeout_table` VARCHAR(128) NULL DEFAULT '0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0'  AFTER `s_values` ,
/*ADD COLUMN `data_file_path` VARCHAR(256) NULL DEFAULT '/var/pw/out'  AFTER `reasons_timeout_table`,*/

ADD COLUMN `local_port` INT(10) NOT NULL DEFAULT 0 AFTER `port`
;


DROP TABLE IF EXISTS `machine_config`;
CREATE TABLE `machine_config` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `machine_id` int(10) NOT NULL,
  `condition_number` int(10) DEFAULT NULL,
  `machine_state_id` int(10) DEFAULT NULL,
  `apply_number` int(10) DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `machine_id` (`machine_id`),
  KEY `machine_state_id` (`machine_state_id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
ROW_FORMAT=DEFAULT;

INSERT INTO `param`
(
`key`,
`value`,
`descr`,
`stable`)
VALUES
(
'machine_config_data_path',
'/etc/pw/bin/config',
'Путь к файлам *.cfg',
1
);