<?php
header("HTTP/1.1 200 OK");

date_default_timezone_set('Asia/Baghdad');
//Setting a custom header with PHP.
header("X-Xss-Protection:1; mode=block");
header("X-Frame-Options:sameorigin");
header("Feature-Policy:vibrate 'none'; geolocation 'none'");
header("Strict-Transport-Security:max-age=15552000; includeSubDomains; preload");
// header("Content-Security-Policy:upgrade-insecure-requests");
header("Referrer-Policy:origin");
header("X-Content-Type-Options:nosniff");
header("X-Content-Security-Policy:default-src 'self'; block-all-mixed-content; connect-src 'self'; font-src 'self' https://fonts.gstatic.com/; img-src 'self' https://ssl.google-analytics.com/ http://www.google-analytics.com/; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://ssl.google-analytics.com/; style-src 'self' 'unsafe-inline' http://share.supercellnetwork.com https://fonts.googleapis.com/");


ini_set('upload_max_filesize', '12G');
ini_set('post_max_size', '12G');
ini_set('memory_limit', '10G');
ini_set('max_input_time', 999);
ini_set('max_execution_time', 999);
ini_set('max_file_uploads', 20);

session_name('SpiderShare');
session_start();

//setcookie(session_name(), session_id(), time() + (86400), "/",".sp4dev.com"); // 86400 = 1

define('SpiderShare', true);

define('MAIN_DIR', str_replace("\\", "/", dirname(__DIR__)) . "/");

define('INCLUDE_DIR', MAIN_DIR . "include/");
define('LOG_DIR', INCLUDE_DIR . 'log/');

define('ASSETS_DIR', MAIN_DIR . "assets/");
define('IMAGE_SYSTEM', ASSETS_DIR . "img/");

define('UPLOAD_DIR', MAIN_DIR . "upload/");

define('UPLOAD_FILES_DIR', "files/");
define('UPLOAD_IMAGE_FOLDER', "images/");

define('UPLOAD_AVATAR_FOLDER', UPLOAD_IMAGE_FOLDER . "avatar/");
define('UPLOAD_TOPIC_IMAGE', UPLOAD_IMAGE_FOLDER . "topic/");

//ini_set("log_errors", 1);
//ini_set("error_log", LOG_DIR . "php-error.log");

//error_reporting(0);

/**
 * Created by Khalid Bj.
 * User: D34DlyM4N
 * Email: info@sp4dev.com
 * Date: 09/04/2016
 * Time: 01:19:14 PM
 */

include "Config.php";
include "class.PDO.php";
include "class.Configure.php";
include "class.Categories.php";

include "class.Comments.php";
include "class.Likes.php";
include "class.Slider.php";

include "class.Posts.php";
include "class.Users.php";
include "class.Requests.php";

include "class.Share.php";
include "class.initSmarty.php";

global $D_Server, $D_Name, $D_User, $D_Pass;

$db = array(
    'server' => $D_Server,
    'db_name' => $D_Name,
    'type' => 'mysql',
    'user' => $D_User,
    'pass' => $D_Pass,
    'charset' => 'charset=utf8'
);

$Share = new Share($db);

$smarty = new initSmarty($Share);

$rowsperpage = $Share->getSetting("ROW_PER_PAGE");
$selectedDisk = $Share->getMountedPlace($Share->getSetting('SELECT_DISK'));

//print_r($selectedDisk);
//die();
if ($Share->isLogged()) {
    $Share->updateUserSession($_SESSION["user_id"]);
}
