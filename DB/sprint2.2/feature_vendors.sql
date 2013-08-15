ALTER TABLE `Vendors` 
ADD COLUMN `MainContact` varchar(255) DEFAULT NULL AFTER `Name` , 
ADD COLUMN `Email` varchar(255) DEFAULT NULL AFTER `MainContact` , 
ADD COLUMN `Phone` varchar(255) DEFAULT NULL AFTER `Email`;