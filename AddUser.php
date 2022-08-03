<?php 
define('Work with database', True);

session_start(); 

//извлекаем информацию из сессии
$sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:''; 
 
// информация об пользователе
$memberData = $userData = array(); 
if(!empty($_GET['id'])){ 
    // Include and initialize JSON class 
    include 'Json.class.php'; 
    $db = new Json(); 
     
    $memberData = $db->getSingle($_GET['id']); 
} 
$userData = !empty($sessData['userData'])?$sessData['userData']:$memberData; 
unset($_SESSION['sessData']['userData']); 
 
$actionLabel = !empty($_GET['id'])?'Edit':'Add'; 
 
// сообщения о состояние сессии
if(!empty($sessData['status']['msg'])){             
    $statusMsg = $sessData['status']['msg']; 
    $statusMsgType = $sessData['status']['type']; 
    unset($_SESSION['sessData']['status']); 
} 
?>

<!-- Вывод сообщения об состоянии сессии -->
<?php if(!empty($statusMsg) && ($statusMsgType == 'success')){?>
<div class="col-xs-12">
    <div class="alert alert-success"><?php echo $statusMsg; ?></div>
</div><?php }

$ErrorName = $ErrorEmail = $ErrorPassword = $ErrorConfirm = $ErrorLogin = $ErrorUser = '';
if($statusMsg == 'Имя не корректно.')
{
    $ErrorName = $statusMsg;
}
else if($statusMsg == 'Введите карректный email.')
{
    $ErrorEmail = $statusMsg;
}
else if($statusMsg == 'Пароль не корректный.')
{
    $ErrorPassword = $statusMsg;
}
else if($statusMsg == 'Пароль не совпадает.')
{
    $ErrorConfirm = $statusMsg;
}
else if($statusMsg == 'Недопустимый логин.')
{
    $ErrorLogin = $statusMsg;
}
else if($statusMsg == 'Данный пользоваетель уже существует ')
{
    $ErrorUser = $statusMsg;
}
?>

<html lang = "en">
 <head>
  <meta charset="utf-8">
  <meta name = "viewport" content="width=device-width, initialscale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  
 <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
  <script src="ajax.js"></script>
 
 </head>

<!-- Форма регистрации -->
<div class="row"> 
    <div class="col-md-12">
        <h2><?php echo $actionLabel; ?> User</h2>
    </div>
    <div class="col-md-6">
     <?php
            if($_COOKIE['user'] == ''):
        ?>
         <form method="post" action="userAction.php"> <!-- "auth.php" -->
         
            <div class="form-group">
                <label>Name</label>
                <input type="text" class= "form-control" name="name" placeholder="Enter your name">
                <span class="error"> <?php echo $ErrorName ?></span>
                <span class="error"> <?php echo $ErrorUser ?></span>
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" name="email" placeholder="Enter your email">
                <span class="error"> <?php echo $ErrorEmail?></span>
            </div>
            
            <div class="form-group">
                <label>password</label>
                <input type="password" class="form-control" name="password" placeholder="Enter password">
                <span class="error"> <?php echo $ErrorPassword?></span>
            </div>

            <div class="form-group">
                <label>confirm_password</label>
                <input type="password" class="form-control" name="confirm_password" placeholder="confirm_password">
                <span class="error"> <?php echo $ErrorConfirm?></span>
                </div>

            <div class="form-group">
                <label>login</label>
                <input type="text" class="form-control" name="login" placeholder="Enter login">
                <span class="error"> <?php echo $ErrorLogin?></span>
            </div>
            
            <a href="index.php" class="btn btn-secondary">Back</a>
            <input type="hidden" name="id" value="<?php echo !empty($memberData['id'])?$memberData['id']:''; ?>">
            <input type="submit" name="userSubmit" class="btn btn-success" value="Submit">

        </form>
      <?php else: ?>
        <p>hello <?= $_COOKIE['user'] ?> <br> <a href="exit.php"> Выход</a> _ </p> 
        <?php endif;?>
    </div>
</div>