<?php
session_start();
include ('function.php');

userIssetReg($_POST['email']);  //проверяю есть ли почта в базе
creatUser($_POST['email'], $_POST['password']);   //создаю пользователя

$user = selectUserByEmail($_POST['email']);   //Создание массива с данными созданного пользователя

$_SESSION['loginInfo'] = "Вы успешно зарегистрировались ".$user['email'];
$_SESSION['email'] = $user['email'];
$_SESSION['id'] = $user['id'];
$_SESSION['role'] = $user['role'];
header("Location: /users.php");

?>