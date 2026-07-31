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
