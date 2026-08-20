-- --------------------------------------------------------
-- DigiLodge incremental migrations
-- Apply to an existing database before running the new build.
-- --------------------------------------------------------

-- Add booking lifecycle columns to ldg_bookings
-- bookingStatus : confirmed | checked_in | checked_out | cancelled
ALTER TABLE `ldg_bookings`
    ADD COLUMN `bookingStatus` varchar(20) NOT NULL DEFAULT 'confirmed' AFTER `bookingComments`,
    ADD COLUMN `checkInDtm` datetime DEFAULT NULL AFTER `bookingStatus`,
    ADD COLUMN `checkOutDtm` datetime DEFAULT NULL AFTER `checkInDtm`;

-- --------------------------------------------------------
-- Add role-based access matrix (per-module list/create/edit/delete permissions)
-- --------------------------------------------------------
CREATE TABLE `ldg_access_matrix` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `access` text,
  `roleId` int(11) NOT NULL,
  `isDeleted` tinyint(4) NOT NULL DEFAULT '0',
  `createdBy` int(11) NOT NULL,
  `createdDtm` datetime NOT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updatedDtm` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Seed defaults that preserve current effective access: Lodge Manager and Booker
-- keep full Booking/Customer access (both were ungated before this migration) and
-- no access to Floors/Rooms/RoomSizes/BaseFare/Reports (both were admin-only before).
-- Adjust via the new Roles > Edit Permissions screen after applying this migration.
INSERT INTO `ldg_access_matrix` (`access`, `roleId`, `isDeleted`, `createdBy`, `createdDtm`, `updatedBy`, `updatedDtm`) VALUES
('[{"module":"Floors","total_access":0,"list":0,"create_records":0,"edit_records":0,"delete_records":0},{"module":"Rooms","total_access":0,"list":0,"create_records":0,"edit_records":0,"delete_records":0},{"module":"RoomSizes","total_access":0,"list":0,"create_records":0,"edit_records":0,"delete_records":0},{"module":"BaseFare","total_access":0,"list":0,"create_records":0,"edit_records":0,"delete_records":0},{"module":"Reports","total_access":0,"list":0,"create_records":0,"edit_records":0,"delete_records":0},{"module":"Customer","total_access":1,"list":1,"create_records":1,"edit_records":1,"delete_records":1},{"module":"Booking","total_access":1,"list":1,"create_records":1,"edit_records":1,"delete_records":1}]', 2, 0, 1, NOW(), NULL, NULL),
('[{"module":"Floors","total_access":0,"list":0,"create_records":0,"edit_records":0,"delete_records":0},{"module":"Rooms","total_access":0,"list":0,"create_records":0,"edit_records":0,"delete_records":0},{"module":"RoomSizes","total_access":0,"list":0,"create_records":0,"edit_records":0,"delete_records":0},{"module":"BaseFare","total_access":0,"list":0,"create_records":0,"edit_records":0,"delete_records":0},{"module":"Reports","total_access":0,"list":0,"create_records":0,"edit_records":0,"delete_records":0},{"module":"Customer","total_access":1,"list":1,"create_records":1,"edit_records":1,"delete_records":1},{"module":"Booking","total_access":1,"list":1,"create_records":1,"edit_records":1,"delete_records":1}]', 3, 0, 1, NOW(), NULL, NULL);

-- --------------------------------------------------------
-- Add role management (add/update/delete), same pattern as CIAS's tbl_roles:
-- status (Active/Inactive) + isDeleted (soft delete) + audit columns.
-- --------------------------------------------------------
ALTER TABLE `ldg_roles`
    ADD COLUMN `status` tinyint(4) NOT NULL DEFAULT '1' AFTER `role`,
    ADD COLUMN `isDeleted` tinyint(4) NOT NULL DEFAULT '0' AFTER `status`,
    ADD COLUMN `createdBy` int(11) NOT NULL DEFAULT '1' AFTER `isDeleted`,
    ADD COLUMN `createdDtm` datetime NULL AFTER `createdBy`,
    ADD COLUMN `updatedBy` int(11) DEFAULT NULL AFTER `createdDtm`,
    ADD COLUMN `updatedDtm` datetime DEFAULT NULL AFTER `updatedBy`;

UPDATE `ldg_roles` SET `createdDtm` = NOW() WHERE `createdDtm` IS NULL;
ALTER TABLE `ldg_roles` MODIFY `createdDtm` datetime NOT NULL;
