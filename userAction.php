<?php 
session_start(); 

// подключаем файл с иничиализацияе БД
require_once 'Json.class.php'; 
$db = new Json(); 

//массив пользователей
$members = $db->getRows(); 

if(!empty($sessData['status']['msg']))
{ 
    $statusMsg = $sessData['status']['msg']; 
    $statusMsgType = $sessData['status']['type'];
    unset($_SESSION['sessData']['status']); 
}
// URL по умолчанию
$redirectURL = 'index.php'; 

if(isset($_POST['userSubmit']))
{ 
    // присваиваем полям значения 
    $name = strip_tags($_POST['name']); 
    $email = strip_tags($_POST['email']); 
    $password = strip_tags($_POST['password']); 
    $login = strip_tags($_POST['login']); 
    $confirm_password = strip_tags($_POST['confirm_password']); 
    
    //User validation
    $errorMsg = '';
    $is_valid = false;
    foreach($members as $row)
    { 
        if($login == $row['login'] || $email == $row['email'])
        {
            $is_valid = true;
            break;
        }             
    }
    if($is_valid)
    {
        $errorMsg .= 'Данный пользоваетель уже существует.';
    }
    else
    {
        //input validation
        if(mb_strlen($name) <= 2 || !preg_match('/^[a-z]+$/i', $name) || empty($name) || $name != trim($name))
        {
            $errorMsg .= 'Имя не корректно.'; 
        }
        else if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){ 
            $errorMsg .= 'Введите карректный email.'; 
        }
        else if(mb_strlen($password) <= 5 ||   !preg_match('/^[0-9A-Za-zА-Яа-я]*$/', $password) || empty($password) || $password != trim($password))
        {
            $errorMsg .= 'Пароль не корректный.';
        }
        else if($password != $confirm_password || empty($confirm_password))
        {
            $errorMsg .= 'Пароль не совпадает.';
        }
        else if(mb_strlen($login) < 6 || empty($login) || $login != trim($login) || !preg_match('/^[a-z]+$/i', $login))
        {
            $errorMsg .= 'Недопустимый логин.'; 
        }
        else
        {
            $password = md5($password."md5");
            
            $user = $login;
            setcookie("user", $user, time() + 3600, "/");
        }
    }

    // структура данных 
    $userData = array( 
        'name' => $_POST['name'], 
        'email' => $_POST['email'], 
        'password' => $_POST['password'], 
        'login' => $_POST['login'] 
    ); 
     
    // хранение и занесение данных в сессию 
    $sessData['userData'] = $userData; 
    if(empty($errorMsg))
    {  
        // добавления пользователя
        $insert = $db->insert($userData); 
             
        if($insert)
        { 
            $sessData['status']['type'] = 'success'; 
            $sessData['status']['msg'] = 'Member data has been added successfully.'; 
             
            unset($sessData['userData']); 
        }
        else
        { 
            $sessData['status']['type'] = 'error'; 
            $sessData['status']['msg'] = 'Some problem occurred, please try again.'; 
             
            $redirectURL = 'index.php'.$id_str; 
        } 
    }
    else
    { 
        //если errorMsg не пуст то передаем.
        $sessData['status']['type'] = 'error'; 
        $sessData['status']['msg'] = $errorMsg;
        // Url для перехода
        $redirectURL = 'index.php'.$id_str; 
    } 
     
    $_SESSION['sessData'] = $sessData; 
}
 
// Переводит пользователя по "Url"
header("Location:".$redirectURL); 
exit();
?>