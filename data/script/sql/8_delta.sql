ALTER TABLE `smto`.`machine`
ADD INDEX `rec_type_name` (`rec_type` ASC, `name` ASC) ;

ALTER TABLE `smto`.`machine`
ADD INDEX `rec_type` (`rec_type` ASC);