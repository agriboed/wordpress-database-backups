<?php
/**
 * Plugin Name: Database Backups
 * Description: Plugin helps create copies of your database in automatic mode
 * with some period and save them on your server and on Amazon S3.
 * Version: 1.3.0
 * Author: AGriboed
 * Text Domain: database-backups
 * Domain Path: /languages
 * Author URI: https://v1rus.ru/
 * License: MIT
 */
require __DIR__ . '/vendor/autoload.php';

new DatabaseBackups\Bootstrap( __FILE__ );