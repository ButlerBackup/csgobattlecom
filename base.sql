ALTER TABLE `matches` ADD `sid` INT NOT NULL AFTER `end`;
ALTER TABLE `matches` ADD `addServDate` INT NULL AFTER `sid`;