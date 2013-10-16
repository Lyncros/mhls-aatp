ALTER TABLE `aatp`.`ProjectsMilestones` 
ADD COLUMN `Order` INT(10) NOT NULL DEFAULT 0  AFTER `EstimatedStartDate` ;

