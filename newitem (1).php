<?php
include "include/init.php";

if (isset($_GET["page"])) {
    $getPage = strip_tags(intval($_GET["page"]));
} else {
    $getPage = 0;
}

$smarty->assign("requestData", $Share->getAllRequests($getPage));
$smarty->display("request.tpl");



?>

