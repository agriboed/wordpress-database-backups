<?php
/**
 * Plugin Name: Database Backups
 * Description: Simple Plugin that allows do backup of database tables. Manually or auto. Простой плагин, который позволяет делать бэкапы вашей базы данных вручную, либо в атоматическом режиме.
 * Version: 1.3.0
 * Author: AGriboed
 * Text Domain: database-backups
 * Domain Path: /languages
 * Author URI: https://v1rus.ru/
 * License: MIT
 */
require __DIR__ . '/vendor/autoload.php';

new DatabaseBackups\Bootstrap( __FILE__ );