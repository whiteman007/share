<?php
include "include/init.php";
/**
 * Created by KhalidBj.
 * Email: info@sp4dev.com
 * User: d34dlym4n
 * Date: 10/24/16
 * Time: 1:29 PM
 */
$Category_id = strip_tags(intval($_GET["categore_id"]));

if (isset($_GET["page"])) {
    $getPage = strip_tags(intval($_GET["page"]));
} else {
    $getPage = 0;
}

$Category = $Share->getCategoriesByID($Category_id);

if ($Category["category_parents"] <= 0) {
    header("Location:/");
    exit();
}

$smarty->assign("sTitle", $Category["category_name"]);
$smarty->assign("filesData", $Share->pagging("", array($Category_id), 1, $getPage, $rowsperpage));

$smarty->display("category.tpl");

?>
