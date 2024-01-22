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

if((userIssetSecurity($_POST['email'])) || ($user['email'] == $_POST['email'])) {
    echo 'fsdfsdfsd';
} else {
    $_SESSION['loginInfo'] = "Редактирование завершилось не корректно, пользователь с такой почтой уже есть в системе.";
    header("Location: /users.php");
    exit;
}

editCredentials($_POST['id'], $_POST['email'], $_POST['password']);
$_SESSION['loginInfo'] = "Данные пользователя".$user['name']." успешно отредактированы.";
header("Location: /users.php");
exit;


?>