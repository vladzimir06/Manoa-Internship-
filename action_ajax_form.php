<?php

if (isset($_POST["login"]) && isset($_POST["password"]) ) { 

	// Формируем массив для JSON ответа
    $result = array(
    	'login' => $_POST["login"],
    	'password' => $_POST["password"]
    ); 

    // Переводим массив в JSON
    echo json_encode($result); 
}

?>