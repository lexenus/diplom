<?php
session_start();
include('./function/function.php');

if(!isLogin()) {
    header("Location: /page_login.php");
    exit;
}

if(isAdmin()) {
    $_SESSION['loginInfo'] = 'Вы админ и можете редактировать данные этого пользователя';
} else {
    if($_GET['id'] == $_SESSION['id']) {
        $_SESSION['loginInfo'] = 'Вы можете редактировать ваши данные';
    } else {
        $_SESSION['loginInfo'] = 'Вы не можете редактировать данные этого пользователя.';
        header("Location: /users.php");
        exit;
    }
}


deleteUser($_GET['id']);
if (isAdmin()){
    $_SESSION['loginInfo'] = 'Вы успешно удалили пользователя с id ='.$_GET['id'];
    header("Location: /users.php");
    exit;
} else {
    session_destroy();
    header("Location: /page_register.php");
    exit;
}
?>