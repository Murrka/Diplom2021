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
            $result=mysqli_query($bd, "SELECT * FROM `users_teachers` WHERE teachers_log='$user'");
            $FIO=mysqli_fetch_array($result, MYSQLI_ASSOC);
            break;
        case 2:
            header('Location: ../students/home.php');
            break;
    }
    $group=$_GET['gr'];
    $dis=$_GET['dis'];
    $result=mysqli_query($bd, "SELECT id_task, name_tasks, data_create, study_load.id_SL FROM `tasks` INNER JOIN `study_load` on tasks.id_SL=study_load.id_SL WHERE study_load.name_group='$group' and study_load.name_dis='$dis' and tasks.catt_tasks='Практические занятия' ORDER BY data_create");

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
                    <li><a id="active" href="home.php">Главная</a></li>
                    <li><a href="https://collegetsaritsyno.mskobr.ru/raspisanie_zanyatij">Расписание</a></li>
                    <li><a href="#">Чат</a></li>
                    <li><a href="profile.php">Профиль</a></li>
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
                    <h2><?php echo 'Преподаватель: ' . $FIO['teachers_f'] . ' ' . mb_substr($FIO['teachers_n'], 0,1, 'utf-8') . '.' . mb_substr($FIO['teachers_o'], 0,1, 'utf-8')?></h2>
                    <h2><?php echo 'Учебное учереждение:' . $FIO['teachers_uz'] ?></h2>
                </div>
                <div class="DivInfoDis">
                    <a href="Discipline.php?gr=<?=$_GET['gr']?>&dis=<?=$_GET['dis']?>&id_SL=<?=$_GET['id_SL']?>"><div id="BoxDis">
                        <h3>Оценки</h3>
                    </div></a>
                    <a href="HardWork.php?gr=<?=$_GET['gr']?>&dis=<?=$_GET['dis']?>&id_SL=<?=$_GET['id_SL']?>"><div id="BoxDis">
                        <h3>Практические занятия</h3>
                    </div></a>
                    <a href="TeorWork.php?gr=<?=$_GET['gr']?>&dis=<?=$_GET['dis']?>&id_SL=<?=$_GET['id_SL']?>"><div id="BoxDis">
                        <h3>Теоритические занятия</h3>
                    </div></a>
                    <a href="MethodMat.php?gr=<?=$_GET['gr']?>&dis=<?=$_GET['dis']?>&id_SL=<?=$_GET['id_SL']?>"><div id="BoxDis">
                        <h3>Методический материал</h3>
                    </div></a>
                    <a href="#"><div id="BoxDis">
                        <h3>Онлайн занятия</h3>
                    </div></a>
                </div>
            </div>
            <div class="DivContentHome">
                <div class="Zagolovok">
                <h1><?=$_GET['gr'];?>><?=$_GET['dis'];?>>Практические занятия</h1>
                </div>
                <div class="DivHBoxs">
                    <?php
                        while($tasksResult=mysqli_fetch_array($result, MYSQLI_ASSOC)){
                            if(!empty($tasksResult['id_task'])){
                            ?><a href="Tasks.php?gr=<?=$_GET['gr']?>&dis=<?=$dis?>&id_SL=<?=$tasksResult['id_SL']?>&id_task=<?=$tasksResult['id_task']?>" id="Catt"><div id="HBox">
                                <h3><?=$tasksResult['name_tasks']?></h3>
                                <span>дата создания <?php $date= new DateTime($tasksResult['data_create']); echo $date->format('d.m.Y'); ?></span>
                                </div></a>
                        <?php }
                        }
                    ?>
                    <a href="CreateTasks.php?gr=<?=$_GET['gr']?>&dis=<?=$_GET['dis']?>&id_SL=<?=$_GET['id_SL']?>" id="Catt"><div id="HBox">
                        <h3>Создать задание</h3>
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
