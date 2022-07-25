<?php 
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
<?php if(!empty($statusMsg) && ($statusMsgType == 'success')){ ?>
<div class="col-xs-12">
    <div class="alert alert-success"><?php echo $statusMsg; ?></div>
</div>
<?php }elseif(!empty($statusMsg) && ($statusMsgType == 'error')){ ?>
<div class="col-xs-12">
    <div class="alert alert-danger"><?php echo $statusMsg; ?></div>
</div>
<?php } ?>

<!-- Форма регистрации -->
<div class="row"> 

        <?php
            if($_COOKIE['user'] == ''):
        ?>

    <div class="col-md-12">
        <h2><?php echo $actionLabel; ?> User</h2>
    </div>
    <div class="col-md-6">
         <form method="post" action="userAction.php">
            <div class="form-group">
                <label>Name</label>
                <input type="text" class="form-control" name="name" placeholder="Enter your name" value="<?php echo !empty($userData['name'])?$userData['name']:''; ?>" required="">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" name="email" placeholder="Enter your email" value="<?php echo !empty($userData['email'])?$userData['email']:''; ?>" required="">
            </div>
            <div class="form-group">
                <label>password</label>
                <input type="text" class="form-control" name="password" placeholder="Enter contact password" value="<?php echo !empty($userData['password'])?$userData['password']:''; ?>" required="">
            </div>

            <div class="form-group">
                <label>confirm_password</label>
                <input type="text" class="form-control" name="confirm_password" placeholder="confirm_password" value="<?php echo !empty($userData['confirm_password'])?$userData['confirm_password']:''; ?>" required="">
            </div>

            <div class="form-group">
                <label>login</label>
                <input type="text" class="form-control" name="login" placeholder="Enter login" value="<?php echo !empty($userData['login'])?$userData['login']:''; ?>" required="">
            </div>
            
            <a href="index.php" class="btn btn-secondary">Back</a>
            <input type="hidden" name="id" value="<?php echo !empty($memberData['id'])?$memberData['id']:''; ?>">
            <input type="submit" name="userSubmit" class="btn btn-success" value="Submit">
        </form>
    </div>
    
    <?php else: ?>
        <p>Hello <?= $_COOKIE['user'] ?> _ <a href="exit.php"> Выйти </a> _ </p> 
        <?php endif;?>
</div>