USE smto_new;

ALTER TABLE `machine`
  ADD COLUMN `main_detector_digit` INT(10) NOT NULL DEFAULT 0,
  ADD COLUMN `main_detector_analog` INT(10) NOT NULL DEFAULT 0
;

update event_color set color = '#d3e01d' where id = 4;