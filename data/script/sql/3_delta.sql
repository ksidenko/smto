USE smto_new;

ALTER TABLE `machine`
  ADD COLUMN `data_fix_period` INT(10) NOT NULL DEFAULT 10  AFTER `local_port` ,
  ADD COLUMN `peak_average_period` INT(10) NOT NULL DEFAULT 1  AFTER `data_fix_period`
;
