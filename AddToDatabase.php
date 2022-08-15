<?php 
session_start(); 

// подключаем файл с иничиализацияе БД
require_once 'Json.class.php'; 
$db = new Json(); 

//массив пользователей
$members = $db->getRows(); 

    // структура данных 
    $userData = array( 
        'name' => $name, 
        'email' => $email, 
        'password' => $password, 
        'login' => $login 
    );
        // добавления пользователя
$insert = $db->insert($userData); 
             
        
 
// Переводит пользователя по "Url"
var_dump("fghjkl;");
?>