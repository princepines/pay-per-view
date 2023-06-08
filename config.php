<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;
// initialize dotenv and variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', $_ENV['HOST']);
define('DB_USERNAME', $_ENV['USER']);
define('DB_PASSWORD', $_ENV['PASS']);
define('DB_NAME', $_ENV['DB']);
define('DB_PORT', $_ENV['PORT']);
 
/* Attempt to connect to MySQL database */
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);

// Check connection
if($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
} else {
    // create table if not exists
    $sql = "CREATE TABLE IF NOT EXISTS `events` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `code` varchar(255) NOT NULL,
        `firstname` varchar(255) NOT NULL,
        `lastname` varchar(255) NOT NULL,
        `email` varchar(255) NOT NULL,
        `phone` varchar(255) NOT NULL,
        `school` varchar(255) NOT NULL,
        `pref_course` varchar(255) NOT NULL,
        `parent_name` varchar(255) NOT NULL,
        `parent_phone` varchar(255) NOT NULL,
        `paid` varchar(255) NOT NULL,
        `device_once` varchar(255) NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    // execute query
    if ($mysqli->query($sql) === TRUE) {
        // echo "Table created successfully";
    } else {
        echo "Error creating table: " . $mysqli->error;
    }
}
?>