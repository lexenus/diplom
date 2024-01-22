<?php
session_start();
include ('function.php');
if(!isLogin()) {
    header("Location: /page_login.php");
    exit;
}

if(isAdmin()) {
    
} else {
    if($_POST['id'] == $_SESSION['id']) {

    } else {
        header("Location: /users.php");
        exit;
    }
}

$user = selectUserById($_POST['id']);
editStatus($_POST['id'], $_POST['status']);
$_SESSION['loginInfo'] = "Статус пользователя".$user['name']." успешно отредактирован.";
header("Location: /users.php");
exit;

?>