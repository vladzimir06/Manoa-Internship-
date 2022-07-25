<?php 
session_start(); 
 
// подключаем файл с иничиализацияе БД
require_once 'Json.class.php'; 
$db = new Json(); 

// URL по умолчанию
$redirectURL = 'index.php'; 

if(isset($_POST['userSubmit']))
{ 
    // присваиваем полям значения
    $id = $_POST['id']; 
    $name = trim(strip_tags($_POST['name'])); 
    $email = trim(strip_tags($_POST['email'])); 
    $password = trim(strip_tags($_POST['password'])); 
    $login = trim(strip_tags($_POST['login'])); 
    $confirm_password = trim(strip_tags($_POST['confirm_password'])); 

    
    $password = md5($password."md5");
    $confirm_password = md5($confirm_password."md5");
    
    $id_str = ''; 
    if(!empty($id)){ 
        $id_str = '?id='.$id; 
    } 
     
    // "Валидация" полей
    $errorMsg = ''; 
    if(empty($name)){ 
        $errorMsg .= '<p>Введите имя.</p>'; 
    } 
    if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){ 
        $errorMsg .= '<p>Введите карректный email.</p>'; 
    } 
    if(empty($password)){ 
        $errorMsg .= '<p>Введите пароль.</p>'; 
    } 
    if(empty($login)){ 
        $errorMsg .= '<p>Введите логи.</p>'; 
    } 
    if(mb_strlen($login) < 6)
    {
          $errorMsg .= '<p>Недопустимая длинная логина</p>';  
    }   
    if(mb_strlen($name) < 2 || mb_strlen($name) > 2 || !preg_match('/^[a-z]+$/i', $name))
    {
        $errorMsg .= '<p>Имя не верно</p>';
    }
    if(mb_strlen($password) < 6 ||   !preg_match('/^[0-9A-Za-zА-Яа-я]*$/', $password ))
    {
        $errorMsg .= '<p>Пароль не верен</p>';
    }
    if($password != $confirm_password)
    {
        $errorMsg .= '<p>Пароль не совпадает</p>';
    }

    // структура данных 
    $userData = array( 
        'name' => $name, 
        'email' => $email, 
        'password' => $password, 
        'login' => $login 
    ); 
     
    // хранение и занесение данных в сессию 
    $sessData['userData'] = $userData; 
    if(empty($errorMsg)){ 
        if(!empty($_POST['id'])){ 
            // обновить информацию о пользователе
            $update = $db->update($userData, $_POST['id']); 
             
            if($update){ 
                $sessData['status']['type'] = 'success'; 
                $sessData['status']['msg'] = 'Member data has been updated successfully.'; 
                 
                unset($sessData['userData']); 
            }else{ 
                $sessData['status']['type'] = 'error'; 
                $sessData['status']['msg'] = 'Some problem occurred, please try again.'; 
                 
                $redirectURL = 'AddUser.php'.$id_str; 
            } 
        }else{ 
            // добавления пользователя
            $insert = $db->insert($userData); 
             
            if($insert){ 
                $sessData['status']['type'] = 'success'; 
                $sessData['status']['msg'] = 'Member data has been added successfully.'; 
                 
                unset($sessData['userData']); 
            }else{ 
                $sessData['status']['type'] = 'error'; 
                $sessData['status']['msg'] = 'Some problem occurred, please try again.'; 
                 
                $redirectURL = 'AddUser.php'.$id_str; 
            } 
        } 
    }else{ 
        $sessData['status']['type'] = 'error'; 
        $sessData['status']['msg'] = '<p>Please fill all the mandatory fields.</p>'.$errorMsg; 
         
        // Url для перехода
        $redirectURL = 'AddUser.php'.$id_str; 
    } 
     
    $_SESSION['sessData'] = $sessData; 
}
elseif(($_REQUEST['action_type'] == 'delete') && !empty($_GET['id'])){ 
    // удаление информации из бд 
    $delete = $db->delete($_GET['id']); 
     
    if($delete){ 
        $sessData['status']['type'] = 'success'; 
        $sessData['status']['msg'] = 'Member data has been deleted successfully.'; 
    }else{ 
        $sessData['status']['type'] = 'error'; 
        $sessData['status']['msg'] = 'Some problem occurred, please try again.'; 
    } 
     
    $_SESSION['sessData'] = $sessData; 
} 
 
// Переводит пользователя по "Url"
header("Location:".$redirectURL); 
exit(); 
?>