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

    if(!isset($_SESSION['messageError'])){
        unset($_SESSION['messageError']);
    }
    if(!isset($_SESSION['messageSuccess'])){
        unset($_SESSION['messageSuccess']);
    }
?>

<html lang="en">
    <head>
        <title>Дистанционное обучение</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1 shrink-to-fit=no">
        <link href="../../CSS/indexCSS.css" rel="stylesheet">
        
    </head>
    <script>
        function Close(){
            document.getElementById('messageError').style.display='none';
        }
    </script>

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
                    <h2><?php echo 'Учебное учреждение:' . $FIO['admins_uz'] ?></h2>
                </div>
                <div class="DivAdminBar">
                    <a href="AddUser1.php"><div id="BoxAdmin">
                        <h3>Добавить студента</h3>
                    </div></a>
                    <a href="AddUser2.php"><div id="BoxAdmin">
                        <h3>Добавить преподавателя</h3>
                    </div></a>
                    <a href="AddGroup.php"><div id="BoxAdmin">
                        <h3>Добавить группу</h3>
                    </div></a>
                    <a href="AddDiscip.php"><div id="BoxAdmin" >
                        <h3>Добавить дисциплину</h3>
                    </div></a>
                    <a href="Study_load.php"><div id="BoxAdmin" class="ActiveBox5">
                        <h3>Создать учебную нагрузку</h3>
                    </div></a>
                    <a href="Edit_Study_load.php"><div id="BoxAdmin">
                        <h3>Список учебой нагрузки</h3>
                    </div></a>
                    <a href="EditUser_list.php"><div id="BoxAdmin">
                        <h3>Список пользователей</h3>
                    </div></a>
                </div>
            </div>
            <div class="DivContentHome">
                <div class="Zagolovok">
                    <h1>Создать нагрузку</h1>
                </div>
                
                <?php if(isset($_SESSION['messageError'])){ //вывод сообщения об ошибке при регистрации?>
                <div class="Error" id="messageError">
                    <h3><?php echo $_SESSION['messageError'];?></h3>
                    <button type="button" class="closeError" tabindex="0" onclick="Close()">X</button>
                </div>
                <?php unset($_SESSION['messageError']); } ?>

                <?php if(isset($_SESSION['messageSuccess'])){ //вывод сообщения о успешной регистрации?>
                <div class="Error Success" id="messageError">
                    <h3><?php echo $_SESSION['messageSuccess'];?></h3>
                    <button type="button" class="closeError" tabindex="0" onclick="Close()">X</button>
                </div>
                <?php unset($_SESSION['messageSuccess']); } ?>

                <div class="AddUser">
                    <form action="../../php/AddStudy_load.php" method="POST" name="AddDis">
                        <div>
                            <p>Выберите отделение</p>
                            <select name="Otdel" id="AddUserSelect" >
                                <option value="" selected>-- --</option>
                                <?php include('../../php/autoZapolOtdel.php'); 
                                for($j=0; $j<count($corpus); $j++){
                            ?><option value="<?=$corpus[$j];?>"><?=$corpus[$j];?></option>
                            <?php } ?>
                            </select>
                        </div>
                        <div><p>Выберите дисциплину</p>
                        <select name="Moduls" id="AddModul" >
                                <option value="Value0" selected>-- --</option>
                            </select>
                        </div>
                        <div><p>Выберите преподавателя</p>
                        <select name="Teachers" id="AddTeachers" >
                                <option value="Value0" selected>-- --</option>
                            </select>
                        </div>
                        <div>
                        <p>Выберите группу</p>
                        <select name="Groups" id="Addgroup">
                            <option value="Value0" selected>-- --</option>
                            <?php include('../../php/autoZapol.php'); 
                                for($j=0; $j<count($groups); $j++){
                            ?><option value="<?=$groups[$j];?>"><?=$groups[$j];?></option>
                            <?php } ?>
                        </select>
                        </div>
                        <div>
                            <p>Введите количество часов</p>
                            <input type="text" name="time_study_load">
                        </div>
                        <div class="google_table">
                            <div><p>Введите адрес гугл-таблицы для группы</p>
                                <input type="text" name="url">
                            </div>
                            <div><p>Введите адрес гугл-таблицы для редактирования</p>
                                <input type="text" name="url_edit">
                            </div>
                        </div>
                        <div><p>Введите Pin-code администратора</p>
                            <input type="password" name="pincodes">
                        </div>
                        <div>
                            <input type="submit" name="SaveUser1" value="Добавить учебную нагрузку">
                        </div>
                    </form>
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

<script type="text/javascript">
  function getXmlHttp() {
    var xmlhttp;
    try {
      xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (E) {
        xmlhttp = false;
      }
    }
    if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
      xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
  }
  var sel=document.getElementById('AddUserSelect');
  
    sel.addEventListener('change', function () {
    var xmlhttp = getXmlHttp(); 
    var nameOtdel=sel.value;
    xmlhttp.open('POST', 'Modul.php', true); // Открываем асинхронное соединение
    xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); 
    xmlhttp.send("nameOtdel=" + encodeURIComponent(nameOtdel)); // Отправляем POST-запрос
    xmlhttp.onreadystatechange = function() { // Ждём ответа от сервера
      if (xmlhttp.readyState == 4) { 
        if(xmlhttp.status == 200) { 
          var Dis = JSON.parse(xmlhttp.responseText); // Преобразуем JSON-строку в массив
          var text = "<option value=''>-- --</option>"; // Начинаем создавать элементы в select
          for (var i in Dis) {
            /* Перебираем все элемены и создаём набор options */
            text += "<option value='" + Dis[i] + "'>" + Dis[i] + "</option>";
          }
          document.AddDis.Moduls.innerHTML = text; // Устанавливаем options в select
        }
      }
    };
  });

    sel.addEventListener('change', function () {
    var xmlhttp = getXmlHttp(); 
    var nameOtdel=sel.value;
    xmlhttp.open('POST', 'teachers.php', true); // Открываем асинхронное соединение
    xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); 
    xmlhttp.send("nameOtdel=" + encodeURIComponent(nameOtdel)); // Отправляем POST-запрос
    xmlhttp.onreadystatechange = function() { // Ждём ответа от сервера
      if (xmlhttp.readyState == 4) { 
        if(xmlhttp.status == 200) { 
          var UserFIO = JSON.parse(xmlhttp.responseText); // Преобразуем JSON-строку в массив
          var text = "<option value=''>-- --</option>"; // Начинаем создавать элементы в select
          for (var i in UserFIO) {
           
            text += "<option value='" + UserFIO[i]['login'] + "'>" + UserFIO[i]['FIO'] + "</option>";
          }
          document.AddDis.Teachers.innerHTML = text; // Устанавливаем options в select
        }
      }
    };
  });
</script>