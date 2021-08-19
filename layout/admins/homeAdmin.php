<?php
    session_start();

    $user=$_SESSION['users'];

    if($user==""){
        header('Location: ../../index.php');
        exit();
    }
    include('../../php/bd.php');
    $check=mysqli_query($bd, "SELECT users_access FROM `users` WHERE users_name='$user'");
    $row=mysqli_fetch_array($check, MYSQLI_ASSOC);
    switch($row['users_access']){
        case 0:
            $result=mysqli_query($bd, "SELECT * FROM `users_admins` WHERE admins_log='$user'");
            $FIO=mysqli_fetch_array($result, MYSQLI_ASSOC);
            break;
        case 1:
            header('Location: ../teachers/home.php');
            break;
        case 2:
            header('Location: ../students/home.php');
            break;
    }

    
?>
<html lang="en">
    <head>
        <title>Дистанционное обучение</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1 shrink-to-fit=no">
        <link href="../../CSS/indexCSS.css" rel="stylesheet">
        
    </head>
    <body>
        <!--Голова сайта-->
        <section class="SectionNavigation">
            <div class="DivTitle">
                <img src="../../IMG/logo.png" alt="logo">
            </div>
            <div class="TitleText">
                <h1>Единый образовательный ресурс дистанционного обучения</h1>
            </div> 
            <a href="homeAdmin.php"><div class="DivPanel">
                <h1>Панель администратора</h1>
            </div></a>
            <a href="../../php/exit.php"><div class="DivExit">
                <p>Выход </p>
                <img src="../../IMG/exits.png" alt="exit">
            </div></a>
        </section>

        <!--Тело сайта-->
        <section class="SectionBodyHome">
            <div class="LeftBar">
                <div class="DivAdminInfo">
                    <h2><?php echo 'Администратор: ' . $FIO['admins_f'] . ' ' . mb_substr($FIO['admins_n'], 0,1, 'utf-8') . '.' . mb_substr($FIO['admins_o'], 0,1, 'utf-8')?></h2>
                    <h2><?php echo 'Учебное учереждение:' . $FIO['admins_uz'] ?></h2>
                </div>
            </div>
            <div class="DivContentHome">
                <div class="Zagolovok">
                    <h1>Выберите действие:</h1>
                </div>
                <div class="DivBoxs">
                    <a href="AddUser1.php" id="CattAdmin"><div id="Box">
                        Добавить пользователя
                    </div></a>
                    <a href="AddGroup.php" id="CattAdmin"><div id="Box">
                        Добавить группу
                    </div></a>
                    <a href="AddDiscip.php" id="CattAdmin"><div id="Box">
                        Добавить дисциплину
                    </div></a>
                    <a href="Study_load.php" id="CattAdmin"><div id="Box">
                        Создать учебную нагрузку
                    </div></a>
                    <a href="Edit_Study_load.php" id="CattAdmin"><div id="Box">
                        Список учебной нагрузки
                    </div></a>
                    <a href="EditUser_list.php" id="CattAdmin"><div id="Box">
                        Список пользователей
                    </div></a>
                </div>
            </div>
            <div class="Clear"></div>
        </section>

        <!--Подвал сайта-->
        <section class="SectionFooter">
            <div class="SectionDiv">
                <a href="#"><div class="SectionFooterNav">
                   Помощь и поддержка
                </div></a>
                <a href="#"><div class="SectionFooterNav">
                    Условия использования
                </div></a>
                <a href="#"><div class="SectionFooterNav">
                    Задать вопрос?
                </div></a>
                <div class="SectionLanguageDiv">
                    <select>
                        <option>Русский</option>
                        <option disabled>English</option>
                    </select>
                </div>
                <div id="perem"></div>
                <div id="copy">
                    &copy; 2021 Корнев "Murrka" Алексей
                </div>
            </div>
            </div>
        </section>
    </body>
</html>
