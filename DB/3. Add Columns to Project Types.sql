set sql_mode = 'STRICT_ALL_TABLES';
alter table `Projects` change `RequestPlant` `RequestPlant` varchar(100);

ALTER TABLE `ProjectsMilestones` ADD `EstimatedStartDate` INT;

ALTER TABLE `ProductTypes` ADD `Milestones` TEXT AFTER `Name`;

ALTER TABLE `Milestones` ADD `ToDosLists` TEXT AFTER `PlantAllocated`;

ALTER TABLE `Projeaatpcts` ADD `PlantPaid` VARCHAR(225) AFTER `RequestPlant`;
ALTER TABLE `Projects` ADD `PlantLeft` VARCHAR(225) AFTER `PlantPaid`;
ALTER TABLE `Projects` ADD `VenderUsed` VARCHAR(225) AFTER `PlantLeft`;
ALTER TABLE `Projects` ADD `DatePaid` INT(10) AFTER `VenderUsed`;
ALTER TABLE `Projects` ADD `ISBN10` VARCHAR(225) AFTER `DatePaid`;
ALTER TABLE `Projects` ADD `ISBN13` VARCHAR(225) AFTER `ISBN10`;
ALTER TABLE `Projects` ADD `CustomISBN` VARCHAR(225) AFTER `ISBN13`;


ALTER TABLE `ProjectsMilestones` ADD `AssignedTo` INT(10) AFTER `PlantAllocated`;
ALTER TABLE `ProjectsToDos` ADD `AssignedTo` INT(10) AFTER `CommentRequired`;