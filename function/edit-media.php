<?php
session_start();
include('function.php');

if(!isLogin()) {
    header("Location: /page_login.php");
    exit;
}

if(isAdmin()) {
    $_SESSION['loginInfo'] = 'Вы админ и можете редактировать данные этого пользователя';
} else {
    if($_POST['id'] == $_SESSION['id']) {
        $_SESSION['loginInfo'] = 'Вы можете редактировать ваши данные';
    } else {
        $_SESSION['loginInfo'] = 'Вы не можете редактировать данные этого пользователя.';
        header("Location: /users.php");
        exit;
    }
}

upload_avatar($_POST['id']);
$_SESSION['loginInfo'] = "Аватар пользователя ".$_POST['id']." успешно отредактирован.";
header("Location: /users.php");
exit;


?>