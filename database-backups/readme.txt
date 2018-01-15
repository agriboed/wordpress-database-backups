=== Database Backups ===
Contributors: AGriboed
Donate link: https://v1rus.ru
Tags: backup, database, backup database, back up, backups, data base, database, database backup, db backup, dump, file, file backup, full backup, page backup, recover, recovery, restore, schedule, schedule backup, scheduled backup, schema, schema backup, site, site backup, web backup, web page, web page backup, WooCommerce backup
Requires at least: 4
Tested up to: 4.9.1
Stable tag: 1.3.0
License: MIT

Plugin helps create copies of your database in automatic mode with some period and save them on your server and on Amazon S3.

== Description ==
Plugin helps create copies of your database in automatic mode with some period and save them on your server and on Amazon S3.

* allows deleting old copies without your participation
* has compression to take less space
* has feature to limit of queries to DB to reduce the load on the server
* has ability to save only WP tables instead a whole database
* has ability to save only "clean" version without unnecessary entries (like revisions, spam comments etc.)
* sends a notify to you with result of the last backup

[Contribute on GitHub](https://github.com/agriboed/wordpress-database-backups)

Support and suggestions, [Support](https://v1rus.ru/database-backups-wordpress/)
skype: agriboed

= Languages =

* English (default)
* Русский (ru_RU)
* Беларуская (be_BE)
* Polski (pl_PL)

== Installation ==
1. Upload the `database-backup` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the Plugins menu in WordPress. After activation, go to menu "Tools" - "Database Backups"
3. Configure the plugin
4. Enjoy!

== Screenshots ==
1. Settings
2. Work area

== Changelog ==
= 1.3.0 =
* Added support of Amazon S3
* Bug fixes

= 1.2.2.6 =
* Change section in admin menu
* Added link to download at admin email
* Fixed language in admin email

= 1.2.2.4 =
* Fixed link in Plugins List
* Fixed schedules list

= 1.2.2.3 =
* Fixed charset error when quering from database

= 1.2.2.2 =
* Added option what allow notify admin about result of backup on email

= 1.2.2.1 =
* Fixed error when working with prefix of WP tables
* Added info of total free space in list of backups

= 1.2.2 =
* Added clean database option

= 1.2.1 =
* Added icons to backups list

= 1.2.0 =
* Added support to converting UTF-8
* Added option what allow to backup only wp tables, instead the whole tables
* Added auto delete old copies of backups
* Settings window hide when user not configuring the plugin
* New backups on top

= 1.1.0 =
* Fix for mysql table export format
* Added limits option for export tables if you see PHP Memory Error

= 1.0.2 =
* Fix for support old style php array()

= 1.0.1 =
* Some fixes

= 1.0.0 =
* Release