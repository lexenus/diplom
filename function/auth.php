<?php
session_start();
include ('function.php');

userIsset($_POST['email']);

$user = selectUserByEmail($_POST['email']);

checkPassword($_POST['password'], $user['password']);

$_SESSION['loginInfo'] = "Вы успешно вошли ".$user['email'];
$_SESSION['email'] = $user['email'];
$_SESSION['id'] = $user['id'];
$_SESSION['role'] = $user['role'];

header("Location: ../users.php");
