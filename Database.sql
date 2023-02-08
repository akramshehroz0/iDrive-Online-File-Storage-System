-- These are the Two Tables that we are going to use in Our Project

-- Create Users Table - Store User Information
CREATE TABLE `web`.`users` ( `ID` INT NOT NULL AUTO_INCREMENT , `User` VARCHAR(255) NOT NULL , `Email` VARCHAR(255) NOT NULL , `Pass` VARCHAR(255) NOT NULL , PRIMARY KEY (`ID`)) ENGINE = MyISAM;

-- Create File System Table - Stores Files Data such as Size, Name etc

CREATE TABLE `web`.`Files` ( `ID` INT NOT NULL AUTO_INCREMENT , `FileName` VARCHAR(255) NOT NULL , `Location` VARCHAR(255) NOT NULL , `Size` INT(10) NOT NULL , `Owner` INT(10) NOT NULL , `isShared` INT NOT NULL , PRIMARY KEY (`ID`)) ENGINE = MyISAM;