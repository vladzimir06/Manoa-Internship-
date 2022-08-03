<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php 

define('Add', True);

$FILENAME = "D:\Manoa\MAMP\htdocs\Demo\JsonDataBase.json";
session_start(); 
 
$sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:''; 
 
$memberData = $userData = array(); 
if(!empty($_GET['id'])){ 
    include 'Json.class.php'; 
    $db = new Json(); 
     
    $memberData = $db->getSingle($_GET['id']); 
} 
$userData = !empty($sessData['userData'])?$sessData['userData']:$memberData; 
unset($_SESSION['sessData']['userData']); 
 
$actionLabel = !empty($_GET['id'])?'Edit':'Add'; 
 
if(!empty($sessData['status']['msg'])){ 
    $statusMsg = $sessData['status']['msg']; 
    $statusMsgType = $sessData['status']['type']; 
    unset($_SESSION['sessData']['status']); 
} 
?>

<?php if(!empty($statusMsg) && ($statusMsgType == 'success')){ ?>
<div class="col-xs-12">
    <div class="alert alert-success"><?php echo $statusMsg; ?></div>
</div>
<?php }elseif(!empty($statusMsg) && ($statusMsgType == 'error')){ ?>
<div class="col-xs-12">
    <div class="alert alert-danger"><?php echo $statusMsg; ?></div>
</div>
<?php } ?>

<html lang = "en">
 <head>
  <meta charset="utf-8">
  <meta name = "viewport" content="width=device-width, initialscale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Форма регистрации</title>
  
 <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
  <script src="ajax.js"></script>
 
 </head>
 <body>

    <div class="container mt-4">
                
        <?php
            if($_COOKIE['user'] == ''):
        ?>

        <div class = "row">
            <div class = "col" id = 'result'>
                <h1> Форма регистрации </h1>
                <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
                 <script src="ajax.js"></script>
                    <form action="AddUser.php" method ="post" id = "foo">

                        <input type="hidden" name="id" value="<?php echo !empty($memberData['id'])?$memberData['id']:''; ?>">
                            <button class = "btn btn-success"> Зарегестрироваться
                        </button>
                        
                    </form>
            </div>
             <div class = "col">    
                <h1> Форма авторизации </h1>
                    <form action="auth.php" method ="post" id ="ajax.js">
                        <label> Login </label>
                        <input type = "text" class = "form-control" name = "loginA" id = "loginA" placeholder = "Введите логин">
                        <span class="error"> <?php echo $loginError?> </span>
                        <br>
                        <label> Password </label>
                        <input type = "password" class = "form-control" name = "passwordA" id = "passwordA" placeholder = "Введите пароль">
                        <span class="error"> <?php echo $error?> </span>
                        <br>
                        <?php if (!file_get_contents($FILENAME)):?>
                           <p> No users</p>
                        <?php else: ?>
                        <button class = "btn btn-success"> Авторизоваться
                            </button>
                        <?php endif;?>
                    </form>
            </div>
        </div>
        <div id="result_form"></div> 
        <?php else: ?>
        <p>hello <?= $_COOKIE['user'] ?> <br> <a href="exit.php"> Выход</a> _ </p> 
        <?php endif;?>

    </div>
 </body>
</html>
