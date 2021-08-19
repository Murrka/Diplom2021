<?php
//регистрация пользователя с доступом: студент
    session_start();

    $user=$_SESSION['users'];
    include('bd.php');
    $results=mysqli_query($bd, "SELECT admins_uz FROM `users_admins` WHERE admins_log='$user'");
    $rowuz=mysqli_fetch_array($results, MYSQLI_ASSOC);
    $UZ=$rowuz['admins_uz'];

    if(isset($_POST['fam'])){
        $fam=$_POST['fam'];
        if ($fam==""){
            unset($fam);
        }
        else if(!preg_match('/^[а-яё]+$/msui',$fam)){
            $_SESSION['messageError']="Фамилия должна содержать только русские буквы";
            header('Location: ../layout/admins/AddUser1.php');
            exit();
        }
    }
    if(isset($_POST['name'])){
        $name=$_POST['name'];
        if ($name==""){
            unset($name);
        }
        else if(!preg_match('/^[а-яё]+$/msui',$name)){
            $_SESSION['messageError']="Имя должно содержать только русские буквы";
            header('Location: ../layout/admins/AddUser1.php');
            exit();
        }
    }
    if(isset($_POST['otch'])){
        $otch=$_POST['otch'];
        if ($otch==""){
            unset($otch);
        }
        else if(!preg_match('/^[а-яё]+$/msui',$otch)){
            $_SESSION['messageError']="Отчество должно содержать только русские буквы";
            header('Location: ../layout/admins/AddUser1.php');
            exit();
        }
    }
    if(isset($_POST['login'])){
        $login=$_POST['login'];
        if ($login==""){
            unset($login);
        }
    }
    if(isset($_POST['password'])){
        $password=$_POST['password'];
        if($password==""){
            unset($password);
        }
    }
    if(isset($_POST['Groups'])){
        $group=$_POST['Groups'];
        if($group=="-- --"){
            unset($group);
        }
    }
    if(isset($_POST['pincodes'])){
        $pin=$_POST['pincodes'];
        if($pin==""){
            unset($pin);
        }
    }
    if(empty($fam) or empty($name)){
        $_SESSION['messageError']='Введите Фамилию и имя студента';
        header('Location: ../layout/admins/AddUser1.php');
        exit();
    }
    if(empty($group)){
        $_SESSION['messageError']='Выберите группу в которой обучается студент';
        header('Location: ../layout/admins/AddUser1.php');
        exit();
    }
    if(empty($login)){
        $_SESSION['messageError']='Введите логин';
        header('Location: ../layout/admins/AddUser1.php');
        exit();
    }
    if(empty($password)){
        $_SESSION['messageError']='Введите пароль';
        header('Location: ../layout/admins/AddUser1.php');
        exit();
    }
    if(empty($pin)){
        $_SESSION['messageError']='Введите pin-код администратора';
        header('Location: ../layout/admins/AddUser1.php');
        exit();
    }
    //защита от sql-инъекций
    $login=stripcslashes($login);
    $login=htmlspecialchars($login);
    $password=stripcslashes($password);
    $password=htmlspecialchars($password);
    $fam=stripcslashes($fam);
    $fam=htmlspecialchars($fam);
    $name=stripcslashes($name);
    $name=htmlspecialchars($name);
    $otch=stripcslashes($otch);
    $otch=htmlspecialchars($otch);
    $pin=stripcslashes($pin);
    $pin=htmlspecialchars($pin);
    $login=trim($login);
    $password=trim($password);
    $fam=trim($fam);
    $name=trim($name);
    $otch=trim($otch);
    $pin=trim($pin);
    
    //подключение к бд
    

    //проверка логина существования в бд
    $result=mysqli_query($bd , "SELECT users_id FROM users WHERE users_name='$login'" );
    $myrow=mysqli_fetch_array($result, MYSQLI_ASSOC);

    
    
    
    if(!preg_match("/^[A-Za-z]+$/",$login)){
        $_SESSION['messageError']="Логин должен содержать только латинские буквы";
        header('Location: ../layout/admins/AddUser1.php');
        exit();
    }
    if(!preg_match("/^.{6,}+$/",$password)){
        $_SESSION['messageError']="Пароль должен быть не короче 6 символов";
        header('Location: ../layout/admins/AddUser1.php');
        exit();
    }
    if(!preg_match("/^([A-Za-z])+([0-9])+$/",$password)){
        $_SESSION['messageError']="Пароль должен содержать тольцо латинские буквы и цифры";
        header('Location: ../layout/admins/AddUser1.php');
        exit();
    }
    if(!empty($myrow['users_id'])){
        $_SESSION['messageError']="Логин уже существует";
        header('Location: ../layout/admins/AddUser1.php');
        exit();
    }
    //проверка пин-кода администратора
    $resultPin=mysqli_query($bd ,"SELECT admins_pin FROM users_admins WHERE admins_log='$user'");
    $rowPin=mysqli_fetch_array($resultPin, MYSQLI_ASSOC);
    
    if($rowPin['admins_pin']==$pin){
        //добавление нового пользователя если пройдены все проверки
        $datereg=date("Y-m-d");
        $password=password_hash($password,PASSWORD_DEFAULT);
        $result2=mysqli_query($bd , "INSERT INTO users (users_name, users_pass, users_access, users_date) VALUES('$login', '$password', '2', '$datereg')");
        $result2_1=mysqli_query($bd, "INSERT INTO users_students (students_f, students_n, students_o, students_log, students_group, students_uz) VALUES ('$fam', '$name', '$otch', '$login', '$group', '$UZ')");
        if($result2==TRUE){
            $_SESSION['messageSuccess']='Студент ' . $fam . ' ' . mb_substr($name, 0,1, 'utf-8') . '.' . mb_substr($otch, 0,1, 'utf-8') . '. успешно зарегистрирован!';
            header('Location: ../layout/admins/AddUser1.php');
            exit();
        }
    }
    else{
        $_SESSION['messageError']='Pin код администратора введен не верно';
        header('Location: ../layout/admins/AddUser1.php');
        exit();
    }
?>