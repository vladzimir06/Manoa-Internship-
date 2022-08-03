<?php 

if(! defined('Work with database'))
{
    header("Location: index.php");
}

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
    $id = $_POST['id']; 
    $name = strip_tags($_POST['name']); 
    $email = strip_tags($_POST['email']); 
    $password = strip_tags($_POST['password']); 
    $login = strip_tags($_POST['login']); 
    $confirm_password = trim(strip_tags($_POST['confirm_password'])); 
    
    $user = $login;
    setcookie("user", $user, time() + 3600, "/");
    
    // "Валидация" полей
    $errorMsg = '';
    if(mb_strlen($name) < 2 || mb_strlen($name) > 2 || !preg_match('/^[a-z]+$/i', $name) || empty($name) || $name != trim($name))
    {
        $errorMsg .= 'Имя не корректно.'; 
    }
    else if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){ 
        $errorMsg .= 'Введите карректный email.'; 
    }
    else if(mb_strlen($password) < 6 ||   !preg_match('/^[0-9A-Za-zА-Яа-я]*$/', $password) || empty($password) || $password != trim($password))
    {
        $errorMsg .= 'Пароль не корректный.';
    }
    else if($password != $confirm_password)
    {
        $errorMsg .= 'Пароль не совпадает.';
    }
    else if($login != trim($login) || mb_strlen($login) < 6 || empty($login))
    {
        $errorMsg .= 'Недопустимый логин.'; 
    }
    else if(!empty($members))
    { 
        $count = 0; 
        foreach($members as $row)
        { 
            $count++;
            if($row['login'] == $login || $row['email'] == $email)
            {
                $errorMsg .= 'Данный пользоваетель уже существует ';
                //break;
            }        
        } 
    }

    $password = md5($password."md5");

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
        //если errorMsg не пуст то передаем.
        $sessData['status']['type'] = 'error'; 
        $sessData['status']['msg'] = $errorMsg;
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