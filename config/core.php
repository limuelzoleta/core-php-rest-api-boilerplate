<?php
define('ROOT_DIR', dirname(__DIR__) );


include ROOT_DIR . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::create(ROOT_DIR);
$dotenv->load();


define('FUNCTIONS_DIR', ROOT_DIR . '/helpers/functions.php');

define('APP_URL',     getenv('APP_URL') ? getenv('APP_URL') : '' );
define('DB_HOST',     getenv('DB_HOST') ? getenv('DB_HOST') : 'localhost' );
define('DB_PORT',     getenv('DB_PORT') ? getenv('DB_PORT') : '3306' );
define('DB_DATABASE', getenv('DB_DATABASE') ? getenv('DB_DATABASE') : 'database' );
define('DB_USERNAME', getenv('DB_USERNAME') ? getenv('DB_USERNAME') : 'root' );
define('DB_PASSWORD', getenv('DB_PASSWORD') ? getenv('DB_PASSWORD') : '' );
define('JWT_KEY',     getenv('JWT_KEY') ? getenv('JWT_KEY') : '' );
include FUNCTIONS_DIR;

include ROOT_DIR . '/config/database.php';

// Establish Database connection
$DB = new Database(DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD);
$CONN = $DB->getConnection();