#drop database smto;
#create database smto;
USE smto_new;

DROP TABLE IF EXISTS `machine`;
CREATE TABLE IF NOT EXISTS `machine` (
        `id` INT(10) NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(512) NOT NULL,
        `code` VARCHAR(512) NOT NULL,
        `ip` VARCHAR(32) NOT NULL,
        `mac` VARCHAR(16) NOT NULL,
        `work_type` ENUM('amplitude','average') NOT NULL,
        `time_idle_run` INT(10) NOT NULL DEFAULT 0 comment 'Время холостого хода, сек',
   	  `rec_type` ENUM('real','template') NOT NULL DEFAULT 'real',
        PRIMARY KEY (`id`),  
   INDEX `code` (`code`),      
   INDEX `mac` (`mac`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
ROW_FORMAT=DEFAULT;

TRUNCATE `machine`;
INSERT INTO `machine` VALUES
        (1, 'название', 'код', '192.168.0.', 'AABBCCDDEE', 'amplitude', 5, 'template'),
        (2, 'test machine', 'test_machine', '192.168.0.1', '00BD3B330571', 'amplitude', 5, 'real')     
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
(0, 'working',  		  'Работает', '', '#FF776F'),
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
CREATE TABLE IF NOT EXISTS `fkey` (
        `id` INT(10) NOT NULL AUTO_INCREMENT,
        `number` INT(10),      
        `machine_id` INT(10) NOT NULL,
        `machine_event_id` INT(10), -- machine_event_id may be null !  
        `code` VARCHAR(128) NOT NULL,
        `name` TEXT NOT NULL default '',        
        `color` VARCHAR(128) NOT NULL default '',
        `type` ENUM('work', 'valid', 'not_valid') NOT NULL,
        `status` INT NOT NULL DEFAULT 1,
   	  `rec_type` ENUM('real', 'template') NOT NULL DEFAULT 'real',
        PRIMARY KEY (`id`),  
   INDEX `machine_id` (`machine_id`),  
   UNIQUE INDEX `hash` (`machine_id`, `number`)
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
CREATE TABLE IF NOT EXISTS `detector` (
	  `id` INT(10) NOT NULL AUTO_INCREMENT,
	  `number` INT(10),
	  `machine_id` INT(10),
	  `status` INT(10) NOT NULL,
	  `type` ENUM('digit', 'analog') NOT NULL,
	  `rec_type` ENUM('real', 'template') NOT NULL DEFAULT 'real',
	PRIMARY KEY (`id`),  
   INDEX `machine_id` (`machine_id`)
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
CREATE TABLE IF NOT EXISTS `amplitude` (
		`id` INT(10) NOT NULL AUTO_INCREMENT,
	  	`number` INT(10),
	   `machine_id` INT(10),
	   `value` INT(10) NOT NULL,
	   `type` ENUM('zero', 'idle_run') NOT NULL,
	   `rec_type` ENUM('real', 'template') NOT NULL DEFAULT 'real',
   PRIMARY KEY (`id`),  
   INDEX `machine_id` (`machine_id`)
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
	(1, 'work', 'Работа', '#0ef21a'),
	(2, 'work_not_valid', 'Необоснованный простой', '#f20c33'),
	(3, 'work_valid', 'Обоснованный простой', '#3b08f5'),
    (4, 'time_ignored', 'Не учтено', '#000000'),

	(5, 'machine_off', 'Станок выключен', '#e8051b'),
	(6, 'machine_on', 'Станок включен', '#1ccc19'),
	(7, 'machine_idle_run', 'Станок на холостом ходу', '#31ab09'),
	(8, 'machine_work', 'Станок работает', '#2f940d')
;

DROP TABLE `operator`;
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

        `fkey_last` int(10) NOT NULL,
        `fkey_all` int(10) NOT NULL,

        `flags` int(10) NOT NULL,                              

       PRIMARY KEY (`id`),
       UNIQUE INDEX `dt_mac` (`dt`, `mac`),  
    	 INDEX `machine_id` (`machine_id`),
    	 INDEX `operator_id` (`operator_id`)
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
        (3, 'work', 'работает'),
;


drop table if exists `task` ;
CREATE TABLE `task` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`pid` INT(10) NULL DEFAULT NULL,
	`machine_id` INT(10) NULL DEFAULT NULL,
	`status` ENUM('start','progress', 'stop', 'end') NULL DEFAULT NULL,
	`dt_create` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	`dt_check` TIMESTAMP NULL DEFAULT NULL,
	`progress` INT(10) NULL DEFAULT NULL,
	`error` TEXT NULL,
	PRIMARY KEY (`id`),
	INDEX `machine_id` (`machine_id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
ROW_FORMAT=DEFAULT
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
	(4, 'machine_data_path', '/var/www/smto/protected/runtime/import/machine_data/', 'Путь к *.dat файлам', 1)
;

