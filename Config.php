<?php
if (!defined('SpiderShare')) {
	die();
}

/**
 * Created by Khalid Bj.
 * User: D34DlyM4N
 * Email: info@sp4dev.com
 * Date: 09/04/2016
 * Time: 01:19:14 PM
 */
define("ENV_DEV", true);

$configINI = "Config.ini";

if (!file_exists(__DIR__ . "/" . $configINI)) {
	die("File Missing $configINI");
}

$ini_array = parse_ini_file($configINI);
//bad extension
$bExt = array("txt","log","html","htm","txt","ini","bat","sh","cmd","php", "phtml", "php3", "php4", "php5", "php7", "phps", "asp", "aspx", "js", "css", "pl", "sql", "mdb", "accdb", "htaccess");
//bad mime
$bMime = array("application/xml", "text/plain", "text/x-php", "text/html", "text/x-perl");

$D_Server = $ini_array['DB_SERVER'];
$D_User = $ini_array['DB_USER'];
$D_Pass = $ini_array['DB_PASSWORD'];
$D_Name = $ini_array['DB_NAME'];

?>
