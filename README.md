# DigiLodge (Hotel-Room-Booking-System)

**Simple Hotel / Lodge Room Booking System using CodeIgniter**

A CodeIgniter (PHP MVC) admin application for managing a small hotel or lodge — rooms, rates, customers, and bookings, with a role-based staff login.

## Features

1. Login, Logout, Forgot Password / Reset Password, Change Password.
2. System user (staff) management, with roles: **System Administrator**, **Lodge Manager**, **Booker**.
3. Property setup: Floors, Room Sizes, Rooms, Base Fare (rates per room size).
4. Customer management.
5. Bookings: create/edit bookings with live room-availability lookup (by date range, floor, room size), booking status lifecycle (`confirmed` → `checked_in` → `checked_out`, or `cancelled`), and booking detail/history view.
6. Booking Report — filterable by date range and status, with a summary.

Property/rate setup, reports, and user management are restricted to the System Administrator role. Bookings and customer management are available to any logged-in staff member.

## Version Information

CodeIgniter 3.x, PHP 8.x compatible, MySQL/MariaDB.

## Installation

Download or clone the repository.

Open browser; goto [localhost/phpmyadmin](http://localhost/phpmyadmin).

Create a database named `lodge` and import `lodge.sql`.

> If you're upgrading an existing install created before the booking-status feature was added, also run `migrations.sql` against your existing database instead of re-importing `lodge.sql`.

Copy the code into your web server root, e.g.:

**WAMP : c:/wamp/www/lodge**

OR

**XAMPP : c:/xampp/htdocs/lodge**

Install PHP dependencies (Composer autoloader) by running the following command from the project root:

```bash
composer install
```

Open browser; goto [localhost/lodge](http://localhost/lodge) and press enter. The login screen will appear.

### Default accounts

| Role | Email | Password |
|---|---|---|
| System Administrator | email@gmail.com | admin |
| Lodge Manager | subadmin@gmail.com | admin |
| Booker | admin@example.com | admin |

## Notes

- If `loginMe` or other routes 404 after setup, make sure `mod_rewrite` is enabled and `.htaccess` is being read by your server (see the CIAS project's README for the same issue and fix, as both projects share this CodeIgniter routing setup).
