<?php
include "include/init.php";

/**
 * Created by KhalidBj.
 * Email: info@sp4dev.com
 * User: d34dlym4n
 * Date: 10/24/16
 * Time: 1:28 PM
 */

if (isset($_GET["post_id"])) {
    $postID = strip_tags(intval($_GET["post_id"]));
} else {
    header("Location: /");
    exit();
}

$postDetails = $Share->getPostByID($postID);


if (empty($postDetails["post_id"])) {
    header("Location: /");
    exit();
}

$Share->IncViewCounterByPostID($postID);

$smarty->assign("sTitle", $postDetails["post_title"]);
$smarty->assign("postDetails", $postDetails);

$smarty->display("post.tpl");

?>
