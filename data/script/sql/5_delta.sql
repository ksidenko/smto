USE smto_new;

DROP TABLE IF EXISTS `machine_group`;
CREATE TABLE IF NOT EXISTS `machine_group` (
        `id` INT(10) NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(512) NOT NULL DEFAULT '',
        PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
ROW_FORMAT=DEFAULT
;

truncate machine_group;
INSERT INTO `machine_group` (`id`, `name`) VALUES
(1, '')
;


DROP TABLE IF EXISTS `machine_2_group`;
CREATE TABLE IF NOT EXISTS `machine_2_group` (
        `id` INT(10) NOT NULL AUTO_INCREMENT,
        `machine_id` INT(10) NOT NULL,
        `group_id` INT(10) NOT NULL,
        PRIMARY KEY (`id`),
        KEY `machine_id_group_id` (`machine_id`, `group_id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
ROW_FORMAT=DEFAULT
;
truncate machine_2_group;
insert into machine_2_group(machine_id, group_id)
  select id, 1 from machine order by id
;
