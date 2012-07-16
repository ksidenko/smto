#drop database smto;
#create database smto;
#USE smto_new;

DROP TABLE IF EXISTS `machine`;
CREATE TABLE `machine` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `template_id` int(10) DEFAULT NULL,
  `name` varchar(512) NOT NULL,
  `code` varchar(512) NOT NULL,
  `ip` varchar(32) NOT NULL,
  `port` INT(10) NOT NULL DEFAULT 900,
  `local_port` INT(10) NOT NULL DEFAULT 0,
  `pwd` VARCHAR(512) NOT NULL DEFAULT '',
  `mac` varchar(16) NOT NULL,
  `work_type` enum('amplitude','average') NOT NULL,
  `time_idle_run` int(10) NOT NULL DEFAULT '0' COMMENT 'Время холостого хода, сек',
  `rec_type` enum('real','template') NOT NULL DEFAULT 'real',
  PRIMARY KEY (`id`),
  KEY `code` (`code`(255)),
  KEY `mac` (`mac`),
  KEY `mac_rec_type` (`mac`, `rec_type`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
ROW_FORMAT=DEFAULT;

TRUNCATE `machine`;
# machine tamplates
INSERT INTO `machine` (id, name, code, ip, mac, work_type, rec_type) VALUES
        (1, 'Шаблон №1', 'Template #1', '10.128.132.', '', 'amplitude', 'template')
;

INSERT INTO `machine` (id, template_id, name, code, ip, port, pwd, mac, work_type) VALUES
        (2, 1, '', '', '10.128.132.103', 900, 'PxZt0003', '7E4AE2C2875E', 'amplitude')
;


DROP TABLE IF EXISTS `machine_event`;
CREATE TABLE IF NOT EXISTS `machine_event` (
        `id` INT(10) NOT NULL,
        `code` VARCHAR(512) NOT NULL,
        `name` VARCHAR(512) NOT NULL DEFAULT '',
        `descr` TEXT NOT NULL DEFAULT '',
        `color` VARCHAR(15) NOT NULL DEFAULT '',       
        PRIMARY KEY (`id`),
        INDEX `code` (`code`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
ROW_FORMAT=DEFAULT
;

truncate machine_event;
INSERT INTO `machine_event` (`id`, `code`, `name`, `descr`, `color`) VALUES
(1, 'crash',    		  'Авария',   'Требуется диагностика', '#2E8C58'),
(2, 'no_instrument',   'Нет инструмента', 'Отсутствует инструмент', '#AEE8EC'),
(3, 'no_gaz', 			  'Нет газа', 'Отсутствует газ', '#754B24'),
(4, 'no_detail', 		  'Нет наряда', 'Отсутствует наряд', '#D670D6'),
(5, 'no_programm', 	  'Нет программы', 'Отсутствует программа', '#C0C0C0'),
(6, 'no_provision',	  'Нет заготовки', 'Нет материала', '#CFAE40'),
(7, 'wait_crane',      'Ожидание крана', 'Требуется кран', '#FF8474'),
(8, 'no_equipment',    'Нет оснастки', 'Требуется оснастка', '#CF8440'),
(9, 'setup_workblank', 'Установка заготовки', '', '#4069E2'),
(10, 'PZV', 'ПЗВ', 'Подготовительно-заключительное время', '')
;



DROP TABLE IF EXISTS `fkey`;
CREATE TABLE `fkey` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `number` int(10) DEFAULT NULL,
  `machine_id` int(10) NOT NULL,
  `machine_event_id` int(10) DEFAULT NULL,
  `code` varchar(128) NOT NULL,
  `name` text NOT NULL,
  `color` varchar(128) NOT NULL DEFAULT '',
  `type` enum('work','valid','not_valid') NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `descr` varchar(1024) NOT NULL DEFAULT '',
  `rec_type` enum('real','template') NOT NULL DEFAULT 'real',
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`machine_id`,`number`),
  KEY `machine_id` (`machine_id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
ROW_FORMAT=DEFAULT;

TRUNCATE `fkey`;
INSERT INTO `fkey` (`id`, `number`, `machine_id`, `machine_event_id`, `code`, `color`, `type`, `status`, `rec_type`) VALUES
        (1, 1, 1, 1, 'F-1', '#00FF00', 'work', 1, 'template'),
        (2, 2, 1, 2, 'F-2', '#00FF00', 'work', 1, 'template'),
        (3, 3, 1, 3, 'F-3', '#00FF00', 'work', 1, 'template'),
        (4, 4, 1, 4, 'F-4', '#00FF00', 'valid', 1, 'template'),
        (5, 5, 1, 5, 'F-5', '#00FF00', 'valid', 1, 'template'),
        (6, 6, 1, 6, 'F-6', '#00FF00', 'valid', 1, 'template'),
        (7, 7, 1, 7, 'F-7', '#00FF00', 'valid', 1, 'template'),
        (8, 8, 1, 8, 'F-8', '#00FF00', 'not_valid', 1, 'template'),
        (9, 9, 1, 9, 'F-9', '#00FF00', 'not_valid', 1, 'template'),
        (10, 10, 1, 10, 'F-10', '#00FF00', 'not_valid', 0, 'template'),
        (11, 11, 1, null, 'F-11', '#00FF00', 'not_valid', 0, 'template'),
        (12, 12, 1, null, 'F-12', '#00FF00', 'not_valid', 0, 'template'),
        (13, 13, 1, null, 'F-13', '#00FF00', 'not_valid', 0, 'template'),
        (14, 14, 1, null, 'F-14', '#00FF00', 'not_valid', 0, 'template'),
        (15, 15, 1, null, 'F-15', '#00FF00', 'not_valid', 0, 'template'),
        (16, 16, 1, null, 'F-16', '#00FF00', 'not_valid', 0, 'template'),                                                                                                         
       
        (17, 1, 2, 1, 'F-1', '#00FF00', 'work', 1, 'real'),
        (18, 2, 2, 2, 'F-2', '#00FF00', 'work', 1, 'real'),
        (19, 3, 2, 3, 'F-3', '#00FF00', 'work', 1, 'real'),
        (20, 4, 2, 4, 'F-4', '#00FF00', 'valid', 1, 'real'),
        (21, 5, 2, 5, 'F-5', '#00FF00', 'valid', 1, 'real'),
        (22, 6, 2, 6, 'F-6', '#00FF00', 'valid', 1, 'real'),
        (23, 7, 2, 7, 'F-7', '#00FF00', 'valid', 1, 'real'),
        (24, 8, 2, 8, 'F-8', '#00FF00', 'not_valid', 1, 'real'),
        (25, 9, 2, 9, 'F-9', '#00FF00', 'not_valid', 1, 'real'),
        (26, 10, 2, 10, 'F-10', '#00FF00', 'not_valid', 0, 'real'),
        (27, 11, 2, null, 'F-11', '#00FF00', 'not_valid', 0, 'real'),
        (28, 12, 2, null, 'F-12', '#00FF00', 'not_valid', 0, 'real'),
        (29, 13, 2, null, 'F-13', '#00FF00', 'not_valid', 0, 'real'),
        (30, 14, 2, null, 'F-14', '#00FF00', 'not_valid', 0, 'real'),
        (31, 15, 2, null, 'F-15', '#00FF00', 'not_valid', 0, 'real'),
        (32, 16, 2, null, 'F-16', '#00FF00', 'not_valid', 0, 'real')
;

DROP TABLE IF EXISTS `detector`;
CREATE TABLE `detector` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `number` int(10) DEFAULT NULL,
  `machine_id` int(10) DEFAULT NULL,
  `status` int(10) NOT NULL,
  `type` enum('digit','analog') NOT NULL,
  `rec_type` enum('real','template') NOT NULL DEFAULT 'real',
  PRIMARY KEY (`id`),
  KEY `machine_id` (`machine_id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
ROW_FORMAT=DEFAULT;

TRUNCATE `detector`;
INSERT INTO `detector` VALUES
        (1, 1, 1, 1, 'digit', 'template'),  
        (2, 2, 1, 1, 'digit', 'template'),  
        (3, 3, 1, 0, 'digit', 'template'),  
        (4, 4, 1, 0, 'digit', 'template'),             
        (5, 1, 1, 0, 'analog', 'template'),    
        (6, 2, 1, 0, 'analog', 'template'),
        (7, 3, 1, 1, 'analog', 'template'),
        (8, 4, 1, 1, 'analog', 'template'),
       
        (9,  1, 2, 1, 'digit', 'real'),  
        (10, 2, 2, 1, 'digit', 'real'),  
        (11, 3, 2, 0, 'digit', 'real'),  
        (12, 4, 2, 0, 'digit', 'real'),        
        (13, 1, 2, 1, 'analog', 'real'),  
        (14, 2, 2, 1, 'analog', 'real'),  
        (15, 3, 2, 0, 'analog', 'real'),  
        (16, 4, 2, 0, 'analog', 'real')
;

DROP TABLE IF EXISTS `amplitude`;      
CREATE TABLE `amplitude` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `number` int(10) DEFAULT NULL,
  `machine_id` int(10) DEFAULT NULL,
  `value` int(10) NOT NULL,
  `type` enum('zero','idle_run') NOT NULL,
  `rec_type` enum('real','template') NOT NULL DEFAULT 'real',
  PRIMARY KEY (`id`),
  KEY `machine_id` (`machine_id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
ROW_FORMAT=DEFAULT;

TRUNCATE `amplitude`;
INSERT INTO `amplitude` VALUES
        (1,  1, 1, 10, 'zero', 'template'),  
        (2,  2, 1, 10, 'zero', 'template'),
        (3,  3, 1, 10, 'zero', 'template'),
        (4,  4, 1, 10, 'zero', 'template'),           
        (5,  1, 1, 100, 'idle_run', 'template'),               
        (6,  2, 1, 100, 'idle_run', 'template'),
        (7,  3, 1, 100, 'idle_run', 'template'),
        (8,  4, 1, 100, 'idle_run', 'template'),
       
        (9,  1, 2, 10, 'zero', 'real'),  
        (10, 2, 2, 10, 'zero', 'real'),
        (11, 3, 2, 10, 'zero', 'real'),
        (12, 4, 2, 10, 'zero', 'real'),               
        (13, 1, 2, 100, 'idle_run', 'real'),           
        (14, 2, 2, 100, 'idle_run', 'real'),
        (15, 3, 2, 100, 'idle_run', 'real'),
        (16, 4, 2, 100, 'idle_run', 'real')    
;

DROP TABLE IF EXISTS `event_color`;
CREATE TABLE IF NOT EXISTS `event_color` (
        `id` INT(10) NOT NULL  AUTO_INCREMENT,
        `code` VARCHAR(512) NOT NULL,
        `name` VARCHAR(512) NOT NULL DEFAULT '',
        `color` VARCHAR(15) NOT NULL DEFAULT '',       
        PRIMARY KEY (`id`),
        INDEX `code` (`code`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
ROW_FORMAT=DEFAULT
;

TRUNCATE `event_color`;
INSERT INTO `event_color` (`id`, `code`, `name`, `color`) VALUES
	(1, 'time_ignored', 'Не учтено', '#B8B8B8'),
	(2, 'machine_0', 'Станок выключен', '#e8051b'),
	(3, 'machine_1', 'Станок простаивает', '#FA8071'),
	(4, 'machine_2', 'Станок на холостом ходу', '#31ab09'),
	(5, 'machine_3', 'Станок работае', '#2f940d')
;

DROP TABLE IF EXISTS `operator`;
CREATE TABLE IF NOT EXISTS `operator` (
        `id` INT(10) NOT NULL AUTO_INCREMENT,
        `c1` INT(10) UNSIGNED NOT NULL,
        `c2` INT(10) UNSIGNED NOT NULL,
        `c3` INT(10) UNSIGNED NOT NULL,
        `full_name` VARCHAR(1024) NOT NULL,
        `phone` VARCHAR(32) NOT NULL DEFAULT '',
        PRIMARY KEY (`id`),  
   UNIQUE INDEX `code` (`c1`, `c2`, `c3`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
ROW_FORMAT=DEFAULT;

INSERT INTO operator(`c1`, `c2`, `c3`, `full_name`) VALUES
        (59, 16,  48508, 'test1'),
        (11, 22,  33,    'test2'),
        (62, 129, 23484, 'test3'),
        (62, 129, 23485, 'test4')
;


CREATE TABLE IF NOT EXISTS `timetable` (
  `id` int(10) NOT NULL,
  `name` varchar(512) NOT NULL,
  `time_from` time NOT NULL,
  `time_to` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `time_from_time_to` (`time_from`,`time_to`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

TRUNCATE `timetable`;
INSERT INTO `timetable` (`id`, `name`, `time_from`, `time_to`) VALUES
        (1, 'Первая смена', '07:00:00', '15:30:00'),
        (2, 'Вторая смена', '15:30:00', '23:59:59'),
        (3, 'Третья смена', '24:00:00', '07:00:00')
        #(4, 'День', '00:00:00', '23:59:59')
;


DROP TABLE IF EXISTS `machine_data`;
CREATE TABLE IF NOT EXISTS `machine_data` (
        `id` INT(10) NOT NULL AUTO_INCREMENT,
        `number` int(10) NOT NULL,     
        `dt` datetime NOT NULL,
        `duration` int(10),            
        `mac` VARCHAR(16) NOT NULL,
        `machine_id` INT(10) NOT NULL,
        `operator_id` INT(10) DEFAULT NULL,
       
        `da_max1` int(10) NOT NULL, `da_max2` int(10) NOT NULL, `da_max3` int(10) NOT NULL, `da_max4` int(10) NOT NULL,

        `da_avg1` int(10) NOT NULL, `da_avg2` int(10) NOT NULL, `da_avg3` int(10) NOT NULL, `da_avg4` int(10) NOT NULL,

        `dd1` int(10) NOT NULL, `dd2` int(10) NOT NULL, `dd3` int(10) NOT NULL, `dd4` int(10) NOT NULL,
               
        `dd_change1` int(10) NOT NULL, `dd_change2` int(10) NOT NULL, `dd_change3` int(10) NOT NULL, `dd_change4` int(10) NOT NULL,
       
        `state` int(10) NOT NULL,

        `operator_last_fkey` int(10) NOT NULL,
        `fkey_all` int(10) NOT NULL,

        `flags` int(10) NOT NULL,                              

        PRIMARY KEY (`id`),
        UNIQUE KEY `dt_mac` (`dt`,`mac`),
        KEY `machine_id` (`machine_id`),
        KEY `operator_id` (`operator_id`),
        KEY `dt` (`dt`),
        KEY `state` (`state`),
        KEY `operator_last_fkey` (`operator_last_fkey`),
        KEY `fkey_all` (`fkey_all`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
ROW_FORMAT=DEFAULT;


#drop TABLE `machine_state`;
CREATE TABLE IF NOT EXISTS `machine_state` (
        `id` INT(10) NOT NULL, -- no AUTO_INCREMENT!!!!
        `code` VARCHAR(512) NOT NULL,
        `name` VARCHAR(512) NOT NULL DEFAULT '',       
        PRIMARY KEY (`id`),
        INDEX `code` (`code`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
ROW_FORMAT=DEFAULT
;

TRUNCATE `machine_state`;
INSERT INTO `machine_state` VALUES
        (0, 'off', 'выключен'),
        (1, 'on', 'включен'),
        (2, 'idle_run', 'холостой ход'),    
        (3, 'work', 'работает')
;


drop table if exists `param` ;
CREATE TABLE IF NOT EXISTS `param` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `key` varchar(200) NOT NULL,
  `value` text NOT NULL,
  `descr` text NOT NULL,
  `stable` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
ROW_FORMAT=DEFAULT
;

INSERT INTO `param` (`id`, `key`, `value`, `descr`, `stable`) VALUES
    (1, 'title', 'СМТО', 'Заголовок сайта', 1),
	(2, 'description', 'СМТО', 'Тэг description', 1),
	(3, 'keywords', 'СМТО', 'ключевые слова', 1),
	(4, 'machine_data_path', '/var/www/smto/protected/runtime/machine_data/', 'Путь к *.dat файлам', 1),
	(5, 'machine_config_data_path', '/var/www/smto/protected/runtime/machine_data/', 'Путь к файлам *.cfg', 1)
;

