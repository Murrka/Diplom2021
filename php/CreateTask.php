<?php
//создание задания преподавателем
    session_start();

    $user=$_SESSION['users'];
    include('bd.php');
    
    $group=$_GET['gr'];
    $dis=$_GET['dis'];
    $id_SL=$_GET['id_SL'];
    

    if(isset($_POST['name_task'])){
        $name_task=$_POST['name_task'];
        if ($name_task==""){
            unset($name_task);
        }
    }
    if(isset($_POST['description'])){
        $description=$_POST['description'];
        if ($description==""){
            unset($description);
        }
    }
    if(isset($_POST['CattWork'])){
        $cattWork=$_POST['CattWork'];
    }
    if(isset($_POST['url'])){
        $url=$_POST['url'];
    }
    if(isset($_POST['date_create'])){
        $date_create=$_POST['date_create'];
        if ($date_create==""){
            unset($date_create);
        }
    }

    
    if(empty($name_task)){
        $_SESSION['messageError']='Введите название задания';
        header('Location: ../layout/teachers/CreateTasks.php?gr=' .$group .'&dis=' .$dis . '&id_SL=' . $id_SL);
        exit();
    }
    if(empty($date_create)){
        $_SESSION['messageError']='Выберите дату создания задания';
        header('Location: ../layout/teachers/CreateTasks.php?gr=' .$group .'&dis=' .$dis . '&id_SL=' . $id_SL);
        exit();
    }

    //защита от sql-инъекций
    $name_task=stripcslashes($name_task);
    $name_task=htmlspecialchars($name_task);
    $description=stripcslashes($description);
    $description=htmlspecialchars($description);
    $name_task=trim($name_task);
    $description=trim($description);
    //проверка пин-кода администратора
    if (isset($_FILES['addfile']) && $_FILES['addfile']['error'] === UPLOAD_ERR_OK) {
        
        $fileTmpPath = $_FILES['addfile']['tmp_name'];
        $fileName = $_FILES['addfile']['name'];
        $fileSize = $_FILES['addfile']['size'];
        $fileType = $_FILES['addfile']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $fileName=$fileNameCmps[0];
        //очистка имени файла и объединение с расширением
        $newFileName = $fileName . '.' . $fileExtension;
        if($_FILES['addfile']['size']>1*1024*1024*1024){
                $_SESSION['messageError'] = 'Файл не может превышать значение объема в 1 Гб.';
                header('Location: ../layout/teachers/CreateTasks.php?gr=' .$group .'&dis=' .$dis . '&id_SL=' . $id_SL);
                exit();
        }
        $allowedfileExtensions = array('exe', 'xls', 'xlsx', 'doc', 'docx', 'pdf','pptx', 'mp3', 'zip', '7z', 'rar',
         'mp4', 'avi', 'txt', 'ai', 'psd', 'indd', 'jpeg', 'png');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            $structure='../downloads/' . $_SESSION['users'] . '/' . $group . '/' . $dis . '/' . $date_create . '/';
            if(!is_dir($structure)){ //проверка на существование папки
            mkdir($structure, 0777 , true); //создание папки
            }

            $dest_path = $structure . $newFileName;
 
            if(move_uploaded_file($fileTmpPath, $dest_path))
            {
                $result=mysqli_query($bd, "INSERT INTO tasks (name_tasks, description_tasks, data_create, catt_tasks, time_tasks, id_SL) VALUES ('$name_task', '$description', '$date_create', '$cattWork', '2', '$id_SL')");
                $res=mysqli_query($bd, "SELECT id_task FROM `tasks` WHERE name_tasks='$name_task' and data_create='$date_create'");
                $resID=mysqli_fetch_array($res, MYSQLI_ASSOC);
                $id_DT=$resID['id_task'];
                $result1_1=mysqli_query($bd, "INSERT INTO downloads_task (path_DT, name_file, date_create_DT, id_task) VALUES ('$dest_path','$newFileName', '$date_create', '$id_DT')");
                header('Location: ' . $url);
                exit();
            }
            
        }
        else
            {
                $_SESSION['messageError'] = 'Принимаются файлы только с расширенияе exe, xls, xlsx, doc, docx, pdf, pptx, mp3, zip, 7z, rar, mp4, avi, txt, ai, psd, indd, jpeg, png';
                header('Location: ../layout/teachers/CreateTasks.php?gr=' .$group .'&dis=' .$dis . '&id_SL=' . $id_SL);
                exit();
            }
        
    }
    else{
        $result=mysqli_query($bd, "INSERT INTO tasks (name_tasks, description_tasks, data_create, catt_tasks, time_tasks, id_SL) VALUES ('$name_task', '$description', '$date_create', '$cattWork', '2', '$id_SL')");
                $res=mysqli_query($bd, "SELECT id_task FROM `tasks` WHERE name_tasks='$name_task' and data_create='$date_create'");
                $resID=mysqli_fetch_array($res, MYSQLI_ASSOC);
                $id_DT=$resID['id_task'];
                header('Location: ' . $url);
                exit();
    }
    
?>