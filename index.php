<?php 
session_start(); 
 
$sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:''; 
 
$memberData = $userData = array(); 

$userData = !empty($sessData['userData'])?$sessData['userData']:$memberData; 
unset($_SESSION['sessData']['userData']); 
 
if(!empty($sessData['status']['msg'])){ 
    $statusMsg = $sessData['status']['msg']; 
    $statusMsgType = $sessData['status']['type']; 
    unset($_SESSION['sessData']['status']); 
} 

$ErrorName = $ErrorEmail = $ErrorPassword = $ErrorConfirm = $ErrorLogin = $ErrorUser = $ErrorLoginAutherization = $ErrorPasswordAutherization = '';
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
else if($statusMsg == 'Данный пользоваетель уже существует.')
{
    $ErrorUser = $statusMsg;
}
else if($statusMsg == 'Логин не верен.')
{
    $ErrorLoginAutherization = $statusMsg;
}
else if($statusMsg == 'Пароль не верен.')
{
    $ErrorPasswordAutherization = $statusMsg;
}
?>

<html lang = "en">
 <head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Форма регистрации</title>
  <script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script> 
 
 </head>
 <body>

    <div class="container mt-4">
                
        <?php
            if($_COOKIE['user'] == ''):
        ?>

        <div class = "row">
            <div class = "col" id = 'result'>
                <h1> Форма регистрации </h1>
            
            <form method="post"  >
         
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
                <input type="text" class="form-control" name="password" placeholder="Enter password">
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
            <input type="submit" name="userSubmit" class="submit" value="Submit">

            </form>

            </div>
            
            <div class = "col">    
                <h1> Форма авторизации </h1>
            
                    <form action="UserAuth.php" method ="post">
                        
                        <label> Login </label>
                        <input type = "text" class = "loginnA" name = "loginA" id = "loginA" placeholder = "Введите логин">
                        <span class="error"> <?php echo $ErrorLoginAutherization?> </span>
                        <br>
                        
                        <label> Password </label>
                        <input type = "password" class = "passwordA" name = "passwordA" id = "passwordA" placeholder = "Введите пароль">
                        <span class="error"> <?php echo $ErrorPasswordAutherization?> </span>
                        <br>
                        
                        
                        <button class = "btn btn-success"> Авторизоваться
                        </button>

                    </form>
            </div>
        </div>
        <?php else: ?>
        <p>hello <?= $_COOKIE['user'] ?> <br> <a href="exit.php"> Выход</a> _ </p> 
        <?php endif;?>

    </div>

    <script>
        $(document).ready(function(){
            $('button').on('click', function(){
                var name = $('input.name').val();
                var email = $('input.Email').val();
                var password = $('input.Password').val();
                var login = $('input.Login').val();
                var confirm_password = $('input.Confirm_password').val();

                $.ajax({
                    url: "userAction.php",
                    type: " POST",
                    datatype: 'html',
                    data: {name: name, email: email, password: password, login: login, confirm_password: confirm_password}
                })
                .done(function() {
                    console.log("success");
                });
                
                
            })
        })
    </script>
 </body>
</html>
