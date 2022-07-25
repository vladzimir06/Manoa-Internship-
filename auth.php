<?php 
session_start(); 
 
    $login = trim(strip_tags($_POST['loginA'])); 
    $password = trim(strip_tags($_POST['passwordA'])); 

    $user = $login;
    setcookie("user", $user, time() + 3600, "/");

$password = md5($password."md5");
$sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:''; 
 
// Инициализируем класс работы с бд
require_once 'Json.class.php'; 
$db = new Json(); 

$members = $db->getRows(); 
 
if(!empty($sessData['status']['msg'])){ 
    $statusMsg = $sessData['status']['msg']; 
    $statusMsgType = $sessData['status']['type']; 
    unset($_SESSION['sessData']['status']); 
    } 
?>

<div class="row">
    <table class="table table-striped table-bordered">
         <tbody>
            <?php $is_valid = false;?>
            <?php if(!empty($members)){ $count = 0; foreach($members as $row){ 
                $count++;
                if($row['login'] == $login && $row['password'] == $password)
                {
                    $is_valid = true;
                    break;
                }
                
            } }?>
            <tr>
                <td><?php 
                if($is_valid) {header('Location: http://localhost/Demo');}
                else
                {
                    setcookie("user", $user, time()- 3600, '/');
                    header('Location: http://localhost/Demo');
                } 
            ?></td>
                <td>
                    <a href="index.php" class="btn btn-success">Выход</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>