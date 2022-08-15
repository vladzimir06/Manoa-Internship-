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

// присваиваем полям значения 
$login = strip_tags($_POST['loginA']); 
$password = trim(strip_tags($_POST['passwordA'])); 


$password = md5($password."md5");

//User validation
$errorMsg = '';
$is_valid = false;
if(mb_strlen($login) < 6 || empty($login) || $login != trim($login) || !preg_match('/^[a-z]+$/i', $login))
{
    $errorMsg .= 'Логин не верен.'; 
}
else
{
    if(!empty($members))
    { 
        foreach($members as $row)
        { 
            if($row['login'] == $login && $row['password'] == $password)
            {
                $is_valid = true;
                break;
            }            
        } 
    }
    if($is_valid == true) 
    {
        $user = $login;
        setcookie("user", $user, time() + 3600, "/");
    }
    else if($is_valid == false)
    {

        foreach($members as $row)
        { 
            if($row['login'] == $login && $row['password'] != $password)
            {
                $errorMsg .= "Пароль не верен.";
            }   
            else if($row['login'] != $login && $row['password'] == $password)  
            {
                 $errorMsg .= "Логин не верен.";
            }       
        } 

        //setcookie("user", $user, time()- 3600, '/'); 
        $errorMsg .= "Данные о пользователе введены не верно.";
    }
}

// хранение и занесение данных в сессию 
$sessData['userData'] = $userData; 
if(!empty($errorMsg))
{  
     //если errorMsg не пуст то передаем.
    $sessData['status']['type'] = 'error'; 
    $sessData['status']['msg'] = $errorMsg;
    // Url для перехода
    $redirectURL = 'index.php'.$id_str; 
}
$_SESSION['sessData'] = $sessData; 
 
// Переводит пользователя по "Url"
header("Location:".$redirectURL);
?>