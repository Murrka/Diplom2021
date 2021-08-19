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
    $id_SL=$_GET['id_SL'];
    $result=mysqli_query($bd, "SELECT * FROM `study_load` WHERE id_SL='$id_SL'");
    $disResult=mysqli_fetch_array($result, MYSQLI_ASSOC);
    $taskResult=mysqli_query($bd, "SELECT * FROM `tasks` WHERE id_SL='$id_SL' ORDER BY data_create");
    
    

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
                <div class="DivInfoDis">
                    <a href="Discipline.php?id_SL=<?=$id_SL?>"><div id="BoxDis">
                        <h3>Оценки</h3>
                    </div></a>
                    <a href="HardWork.php?id_SL=<?=$id_SL?>"><div id="BoxDis">
                        <h3>Практические занятия</h3>
                    </div></a>
                    <a href="TeorWork.php?id_SL=<?=$id_SL?>"><div id="BoxDis">
                        <h3>Теоритические занятия</h3>
                    </div></a>
                    <a href="MethodMat.php?id_SL=<?=$id_SL?>"><div id="BoxDis">
                        <h3>Методический материал</h3>
                    </div></a>
                    <a href="#"><div id="BoxDis">
                        <h3>Онлайн занятия</h3>
                    </div></a>
                </div>
            </div>
            <div class="DivContentHome">
                <div class="Zagolovok">
                <h1><?=$disResult['name_dis']?>>Методический материал</h1>
                </div>
                
                <div class="DivMBoxs_Mouth">
                    <?php
                        while($taskRow=mysqli_fetch_array($taskResult, MYSQLI_ASSOC)){ ?>
                            <div class="BoxDate">
                            <h3>Задание: <?=$taskRow['name_tasks']?> от <?php $date= new DateTime($taskRow['data_create']); echo $date->format('d.m.Y'); ?></h3>
                            <div></div>
                            </div>
                            <?php
                                $id_task=$taskRow['id_task'];
                                $resultDT=mysqli_query($bd, "SELECT * FROM `downloads_task` WHERE id_task='$id_task'");
                                while($rowDT=mysqli_fetch_array($resultDT, MYSQLI_ASSOC)){ ?>
                                    <div class="BoxFile"><a href="../../<?=$rowDT['path_DT']?>" id="aFile" download="">
                                    <p><?=$rowDT['name_file']?></p>
                                    <span><?php $date= new DateTime($rowDT['date_create_DT']); echo $date->format('d.m.Y'); ?></span>
                                    </a></div>
                            <?php    }
                            
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
