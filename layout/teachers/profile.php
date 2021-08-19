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
    if(!empty($_SESSION['id_SL'])){
        $id_SL=$_SESSION['id_SL'];
        
    }
    if(!empty($_SESSION['month'])){
        $month=$_SESSION['month'];
        
    }
    if(!empty($_SESSION['date'])){
        $date=$_SESSION['date'];
        $query=mysqli_query($bd, "SELECT * FROM `tasks` WHERE DATE_FORMAT(data_create, '%Y-%m')='$date' and id_SL='$id_SL' ORDER BY data_create");
        $check2=mysqli_fetch_assoc($query);
    }
    $resultDis=mysqli_query($bd, "SELECT name_dis,name_group,time_study_load FROM `study_load` WHERE id_SL='$id_SL'");
    $DisGroup=mysqli_fetch_assoc($resultDis);
    $result=mysqli_query($bd, "SELECT DISTINCT name_dis FROM `study_load` WHERE name_teacher='$user'");
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
                    <li><a  href="home.php">Главная</a></li>
                    <li><a href="https://collegetsaritsyno.mskobr.ru/raspisanie_zanyatij">Расписание</a></li>
                    <li><a href="#">Чат</a></li>
                    <li><a id="active" href="profile.php">Профиль</a></li>
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
                    <form action="../../php/statistic.php" method="POST" class="form_stat">
                        <div><p>Выберите месяц:</p>
                        <input type="month" name="month" min="2015-01">
                        </div>
                        <div><p>Выберите дисциплину:</p>
                        <select name="dis" id="dis">
                            <?php while($dis=mysqli_fetch_assoc($result)){ ?>
                                <option value="<?=$dis['name_dis']?>"><?=$dis['name_dis']?></option>
                    <?php   }?>
                            
                        </select>
                        </div>
                        <div><p>Выберите группу:</p>
                        <select name="group" id="group">
                            <?php 
                               $result=mysqli_query($bd, "SELECT DISTINCT name_group FROM `study_load` WHERE name_teacher='$user'");
                               while($group=mysqli_fetch_assoc($result)){ ?>
                                    <option value="<?=$group['name_group']?>"><?=$group['name_group']?></option>
                        <?php   } 
                            ?>
                        </select>
                        </div>
                        <div>
                        <input type="submit" name="sub_stat" value="Показать статистику">
                        </div>
                    </form>
                    <form action="../../php/down_forma2.php" method="POST" class="form_stat">
                        <div class="otchet"><p>Выберите месяц для получения отчета</p>
                        <input type="month" name="month" min="2015-01">
                        </div>
                        <div class="button_otchet">
                        <input type="submit" name="sub_stat" value="Создать отчет" onclick="location.href='../../php/down_forma2.php'">
                        </div>
                    </form>
                </div>
            </div>
            <div class="DivContentHome">
                <div class="Zagolovok">
                <h1>Профиль>Статистика</h1>
                </div>
                <div class="DivStatistic">
                    <div class="contein_Statistic">
                        <div class="Name_dis_contein"><h2>Дисциплина : <?=$DisGroup['name_dis']?></h2></div>
                        <div class="Name_group_contein"><h3>Группа: <?=$DisGroup['name_group']?></h3></div>
                        <div class="Mouth"><h3>Месяц: <?=$month?></h3></div>
                        <div class="table_flex_statistic">
                            <?php 
                                $summHours=0;
                                if(!empty($check2['id_task'])) {
                                    $query=mysqli_query($bd, "SELECT * FROM `tasks` WHERE DATE_FORMAT(data_create, '%Y-%m')='$date' and id_SL='$id_SL' ORDER BY data_create");
                                while($Table=mysqli_fetch_assoc($query)){ 
                                    
                                        ?>
                                    <div class="table_row">
                                        <div><h3><?php $date_create=new DateTime($Table['data_create']); echo $date_create->format('d.m');?></h3></div>
                                        <div><h3><?php if($Table['catt_tasks']=='Практические занятия') echo 'П'; else echo 'Т'; ?></h3></div>
                                        <div><h3><?=$Table['time_tasks']?></h3></div>
                                    </div>
                        <?php   
                            
                            ?>
                         <?php    }   } else{ ?>
                            <div class="Not_tasks"><h2>Занятия еще не проводились</h2></div>
                            <?php } 
                                    $hours=mysqli_query($bd, "SELECT SUM(time_tasks) FROM `tasks` WHERE id_SL='$id_SL'");
                                    $summHours=mysqli_fetch_assoc($hours);
                                    $summHours2=$summHours['SUM(time_tasks)']/$DisGroup['time_study_load']*100;
                                    $ostatok=$DisGroup['time_study_load']-$summHours['SUM(time_tasks)'];
                            ?>
                        </div>
                        <div class="snoska">
                            <p>*П-Практические занятия</p>
                            <p>*Т-Теоретические занятия</p>
                        </div>
                        <div class="Study_load_time"><h3>Нагрузка в часах</h3></div>
                        <div class="scale_contein">
                            <div class="scale_div">
                                <div id="execute_study_load" style="width: <?=$summHours2?>%;"><h4><?php if(!empty($summHours['SUM(time_tasks)'])) echo $summHours['SUM(time_tasks)'] . ' ч'; else echo 0 . ' ч'; ?></h4></div>
                            </div>
                            <div class="time">
                                <h3>Общая нагрузка по дисциплине: <?=$DisGroup['time_study_load']?> часов</h3>
                                <h3>Осталось: <?=$ostatok?> часов</h3>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
            </div>
            <div class="Clear"></div>
        </section>
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
