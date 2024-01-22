<?php

include ('connect.php');   

//Соединение с БД
function connect(){
    $hostname ="localhost";
    $dbname = "diplom";
    $username = "root";
    $password = "";
    $conn = new PDO("mysql:host=$hostname; dbname=$dbname", $username, $password);
    return $conn;
}

//Выбираем пользователя по почте из базы и формируем массив данных (принимается почта)
function selectUserByEmail($email) {  
    $conn = connect();
    $sql = "SELECT * FROM users WHERE email=:email";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user;
}

//Выбираем пользователя по ID из базы и формируем массив данных (принимается ID)
function selectUserById($id) {  
    $conn = connect();
    $sql = "SELECT * FROM users WHERE id=:id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user;
}

// проверяем существует ли пользователь в базе (при регистрации)
function userIsset($email) {  
    $user = selectUserByEmail($email);
    if(empty($user)) { 
        $_SESSION['error'] = "Пользователей с такой почтой нет в системе";
        header("Location: /page_login.php");
        exit;
    }
}

// проверяем существует ли пользователь в базе (при входе) - понимаю что это дубль функции, но не пойму как сделать 
function userIssetReg($email) {  
    $user = selectUserByEmail($email);
    if(!empty($user)) { 
        $_SESSION['error'] = "Пользователей с такой почтой есть в системе";
        header("Location: /page_register.php");
        exit;
    }
}


// проверяем существует ли пользователь в базе (при входе) - понимаю что это дубль функции, но не пойму как сделать 
function userIssetCreat($email) {  
    $user = selectUserByEmail($email);
    if(!empty($user)) { 
        $_SESSION['error'] = "Пользователей с такой почтой есть в системе";
        header("Location: /create_user.php");
        exit;
    }
}



function userIssetSecurity($email) {  
    $user = selectUserByEmail($email);
    if(empty($user)) { 
        return true;
    }
}






//Создаём массив всех пользователей БД
function selectAllUsers() {  
    $conn = connect();
    $sql = "SELECT * FROM users";
    $stmt = $conn->query($sql);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $users;
}

//Создание пользователя (имя, пароль и роль)
function creatUser($email, $pass) {
    $conn = connect();
    $sql = "INSERT INTO users (email, password, role) VALUE (:email, :password, :role)";
    $stmt = $conn->prepare($sql);
    $stmt -> execute([
        'email' => $email,
        'password' => password_hash($pass, PASSWORD_DEFAULT),
        'role' => 'user'
    ]);
}

//Добавление общей информации
function editInfo ($id, $name, $work, $phone, $address) {
    $conn = connect();
    $sql = "UPDATE users SET name=:name, work=:work, phone=:phone, address=:address WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt-> execute([
        ':id' => $id,
        ':name' => $name,
        ':work' => $work,
        ':phone' => $phone,
        ':address' => $address
    ]);
}

//Изменение статуса пользователя
function editStatus($id, $status) {
    $conn = connect();
    $sql = "UPDATE users SET status=:status WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':id' => $id,
        ':status' => $status
    ]);
}

//Обновление ключевых данных
function editCredentials($id, $email, $password) {
    $conn = connect();
    $sql = "UPDATE users SET email=:email, password=:password WHERE id=:id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ":id" => $id,
        ":email" => $email,
        ":password" => password_hash($password, PASSWORD_DEFAULT),
    ]);
}

// //Изменение аватара пользователя
function editUserPic($id) {
    if(!is_uploaded_file($_FILES['userpic']['tmp_name'])) {
        $_SESSION['error'] = "Файл загружен не через форму";
        header("Location: /create_user.php");
        exit;
    } else {
        $pathinfoImg = pathinfo($_FILES['userpic']['name']);
        $nameimg = uniqid().".".$pathinfoImg['extension'];   // генерация переменной
        $uploaddir = 'C:\\OSPanel\\domains\\diplom\\img\\avatars\\'.$nameimg;  //каталог загрузки
        move_uploaded_file($_FILES['userpic']['tmp_name'], $uploaddir);
        
        $conn = connect();
        $sql = "UPDATE users SET userpic=:userpic WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ":userpic" => $nameimg
        ]);
    }
} 

//Изменение социальных сетей
function editSocial ($id, $vk, $tg, $inst) {
    $conn = connect();
    $sql = "UPDATE users SET vk=:vk, tg=:tg, inst=:inst WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':id' => $id,
        'vk' => $vk,
        'tg' => $tg,
        'inst' => $inst
    ]);
}


//Вошёл пользователь в систему или нет
function isLogin(){  
    if(isset($_SESSION['email'])) {
        return true;
    } else {
        return false;
    }
}


//Проверка Админ пользователь или нет
function isAdmin(){
    $user = selectUserByEmail($_SESSION['email']);
    if($user['role'] == 'admin') {
        return true;
    } else {
        return false;
    }
}

function logOut() {
    session_destroy();
    header("Location: /page_login.php");
}


// Проверяем верно ли введён пароль
function checkPassword($password, $hashPassword) {
    if(!password_verify($password, $hashPassword)){ 
        $_SESSION['error'] = "Вы ввели не верный пароль";
        header("Location: /page_login.php");
        exit;
    }
}

//Написал с одним параметром, я не понял зачем второй
function has_image($id) {
    $user = selectUserById($id);
    if (!empty($user['userpic'])) {
        echo "<img src='img/avatars/".$user['userpic']."' alt='' class='img-responsive' width='200'>";
    } else {
        echo "<img src='img/demo/avatars/avatar-m.png' alt='' class='img-responsive' width='200'>";
    }
}

function upload_avatar($id) {
    $user = selectUserById($id);
    $filePath = "C:\\OSPanel\\domains\\diplom\\img\\avatars\\".$user['userpic'];
    if (file_exists($filePath)) {
        unlink($filePath);
    }
    if(!is_uploaded_file($_FILES['userpic']['tmp_name'])) {
        $_SESSION['error'] = "Файл загружен не через форму";
        header("Location: /users.php");
        exit;
    } else {
        $pathinfoImg = pathinfo($_FILES['userpic']['name']);
        $nameimg = uniqid().".".$pathinfoImg['extension'];   // генерация переменной
        $uploaddir = "C:\\OSPanel\\domains\\diplom\\img\\avatars\\".$nameimg;  //каталог загрузки
        move_uploaded_file($_FILES['userpic']['tmp_name'], $uploaddir);
        
        $conn = connect();
        $sql = "UPDATE users SET userpic=:userpic WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ":userpic" => $nameimg
        ]);
    }
}


function deleteUser($id) {
    $user = selectUserById($id);
    $filePath = "C:\\OSPanel\\domains\\diplom\\img\\avatars\\".$user['userpic'];
    if (file_exists($filePath)) {
        unlink($filePath);
    }
    $conn = connect();
    $sql = "DELETE FROM users WHERE id=:id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ":id" => $id
    ]);
}

