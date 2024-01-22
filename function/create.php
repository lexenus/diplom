<?php
session_start();
include ('function.php');

userIssetCreat($_POST['email']); //занят ли email
creatUser($_POST['email'], $_POST['password']);  //создаём пользователя
$user = selectUserByEmail($_POST['email']);
editInfo($user['id'], $_POST['name'], $_POST['work'], $_POST['phone'], $_POST['address']); //Изменяем общую информацию
editStatus($user['id'], $_POST['status']); //Изменить статус
editUserPic($user['id']); //Добавить аватар
editSocial($user['id'], $_POST['vk'], $_POST['tg'], $_POST['inst']); //Добавить социальные сети

$_SESSION['loginInfo'] = 'Новый пользователь добавлен';
header ("Location: /users.php");
?>