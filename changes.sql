ALTER TABLE `owners` CHANGE `iban` `iban` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;

ALTER TABLE `drivers` CHANGE `vehicle_type` `vehicle_type` CHAR(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL, CHANGE `car_make` `car_make` INT(10) UNSIGNED NULL, CHANGE `car_model` `car_model` INT(10) UNSIGNED NULL;

-- ALTER TABLE `driver_details` DROP INDEX `driver_details_company_foreign`;