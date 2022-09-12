<?php
include "include/init.php";

if($Share->isLogged()){
    header("Location: /");
    exit();
}

/**
 * Created by KhalidBj.
 * Email: info@sp4dev.com
 * User: d34dlym4n
 * Date: 11/20/16
 * Time: 10:20 AM
 */

$smarty->assign("sTitle","Register");
$smarty->display("register.tpl");

?>

