<?php
//подключение к бд
    $bd=mysqli_connect('localhost','root','root', 'distanclearning');
    if(!$bd){
        printf("Connect failed: %s/n", mysqli_connect_error());
        exit();
    }
?>