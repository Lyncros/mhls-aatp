ALTER TABLE `aatp`.`Projects` 
ADD COLUMN `SpecDocLink` VARCHAR(255) NULL  AFTER `DeletedIPAddress` , 
ADD COLUMN `ConnectRequestIDLink` VARCHAR(255) NULL  AFTER `SpecDocLink` ;

