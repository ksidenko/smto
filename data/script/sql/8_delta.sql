ALTER TABLE `smto`.`machine`
ADD INDEX `rec_type_name` (`rec_type` ASC, `name` ASC) ;

ALTER TABLE `smto`.`machine`
ADD INDEX `rec_type` (`rec_type` ASC);

ALTER TABLE `smto`.`operator`
  ADD COLUMN `create_dt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `phone` ,
  ADD COLUMN `delete_dt` TIMESTAMP NULL DEFAULT NULL  AFTER `create_dt`
, ADD INDEX `name` (`full_name` ASC) ;

truncate table operator;

INSERT INTO `smto`.`param`
  (`id`,`key`,`value`,`descr`,`stable`)
  VALUES (7, 'operator_data_path', '/var/storage/operators.csv', 'Путь к выгрузке с операторами',1);