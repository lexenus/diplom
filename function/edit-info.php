<?php
session_start();
include ('function.php');
if(!isLogin()) {
    header("Location: page_login.php");
    exit;
}

if (isAdmin()) {
    //тут задумывалась какая-либо реализация?
} else {
    if ($_POST['id'] == $_SESSION['id']) {
        //аналогично?
    } else {
        header("Location: users.php");
        exit;
    }
}

editInfo($_POST['id'], $_POST['name'], $_POST['work'], $_POST['phone'], $_POST['address']);

$_SESSION['loginInfo'] = 'Пользователь с ID='.$_POST['id'].' был изменён';

header("Location: ../users.php");// видимо особенность функции header перенапрвляет на файлы той папки из которой вызывается, сам об этом не знал
