<?php
define("ADMIN_EMAIL", "raafatwahb@gmail.com");
define("APP_NAME", "TUTORME");

$ADMIN_ACCOUNTS = array("admin@admin");
$ADMIN_PASSWORDS = array("admin");

define("BASE_DIR", pathinfo(__DIR__)["basename"]);
define("SUB_DIR", basename(pathinfo($_SERVER["SCRIPT_FILENAME"])["dirname"]));

define("SITE_URL", "http://" . $_SERVER["HTTP_HOST"] . "/" . BASE_DIR . "/");
define("ROOT_PATH", realpath(__DIR__) . "/");
define("SUB_URL", SITE_URL . SUB_DIR);

define("BASELINK", SITE_URL);

define("CLASSES_PATH", ROOT_PATH . "php/classes/");
define("LIBS_PATH", ROOT_PATH . "php/libs/");

define("COOKIE_LIFETIME", 1209600);
define("COOKIE_DOMAIN", ".127.0.0.1");
define("COOKIE_SECRET_KEY", ".,some09random`~number!@#$12%^&*?");

# echo "Site URL: " . SITE_URL . "<br />Root Path: " . ROOT_PATH;
?>
