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
            header('Location: ../admins/homeAdmin.php');
            break;
        case 1:
            header('Location: ../teachers/home.php');
            break;
        case 2:
            $result=mysqli_query($bd, "SELECT * FROM `users_students` WHERE students_log='$user'");
            $FIO=mysqli_fetch_array($result, MYSQLI_ASSOC);
            break;
    }
    $group=$FIO['students_group'];
    $result=mysqli_query($bd, "SELECT * FROM `study_load` WHERE name_group='$group'");
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
            <div class="DivNav">
                <ul class="menu">
                    <li><a id="active" href="home.html">Главная</a></li>
                    <li><a href="https://collegetsaritsyno.mskobr.ru/raspisanie_zanyatij">Расписание</a></li>
                    <li><a href="#">Чат</a></li>
                    <li><a href="#">Профиль</a></li>
                </ul>
            </div>
            <a href="../../php/exit.php"><div class="DivExit">
                <p>Выход </p>
                <img src="../../IMG/exits.png" alt="exit">
            </div></a>
        </section>

        <!--Тело сайта-->
        <section class="SectionBodyHome">
            <div class="LeftBar">
                <div class="DivPolInfo">
                    <h2><?php echo 'Студент: ' . $FIO['students_f'] . ' ' . mb_substr($FIO['students_n'], 0,1, 'utf-8') . '.' . mb_substr($FIO['students_o'], 0,1, 'utf-8')?></h2>
                    <h2><?php echo 'Группа: ' . $FIO['students_group'];?></h2>
                    <h2><?php echo 'Учебное учереждение:' . $FIO['students_uz'] ?></h2>
                </div>
            </div>
            <div class="DivContentHome">
                <div class="Zagolovok">
                    <h1>Выберите дисциплину:</h1>
                </div>
                <div class="DivBoxs">
                    <?php 
                        while($rowResult=mysqli_fetch_array($result, MYSQLI_ASSOC)){
                            ?>
                                <a href="Discipline.php?id_SL=<?=$rowResult['id_SL']?>" id="Catt"><div id="Box">
                                    <?=$rowResult['name_dis']?>
                                </div></a>
                           <?php 
                        }
                    ?>
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
