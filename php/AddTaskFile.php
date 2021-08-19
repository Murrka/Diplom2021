<?php
//загрузка файла к заданию, перемещение в папку проекта, создание записи в бд
    session_start();

    $user=$_SESSION['users'];
    include('bd.php');
    
    $id_SL=$_GET['id_SL'];
    $id_task=$_GET['id_task'];
    $result=mysqli_query($bd, "SELECT tasks.data_create, study_load.name_group, study_load.name_teacher, study_load.name_dis FROM `tasks` INNER JOIN `study_load` on tasks.id_SL=study_load.id_SL WHERE tasks.id_SL='$id_SL' and tasks.id_task='$id_task'");
    $infoResult=mysqli_fetch_array($result, MYSQLI_ASSOC);

    //проверка пин-кода администратора
    if (isset($_FILES['FileAdd']) && $_FILES['FileAdd']['error'] === UPLOAD_ERR_OK) {
        
        $fileTmpPath = $_FILES['FileAdd']['tmp_name'];
        $fileName = $_FILES['FileAdd']['name'];
        $fileSize = $_FILES['FileAdd']['size'];
        $fileType = $_FILES['FileAdd']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $fileName=$fileNameCmps[0];
        //очистка имени файла и объединение с расширением
        $newFileName = $fileName . '.' . $fileExtension;
        $allowedfileExtensions = array('exe', 'xls', 'xlsx', 'doc', 'docx', 'pdf','pptx', 'mp3', 'zip', '7z', 'rar',
         'mp4', 'avi', 'txt', 'ai', 'psd', 'indd', 'jpeg', 'png');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            $structure='../downloads/' . $infoResult['name_teacher'] . '/' . $infoResult['name_group'] . '/' . $infoResult['name_dis'] . '/' . $infoResult['data_create'] . '/' . $_SESSION['users'] . '/';
            if(!is_dir($structure)){ //проверка на существование папки
            mkdir($structure, 0777 , true); //создание папки
            }
            
            $dest_path = $structure . $newFileName;
 
            if(move_uploaded_file($fileTmpPath, $dest_path))
            {
                $date_create=date("Y-m-d");
                $result=mysqli_query($bd, "INSERT INTO hard_work_students (path_HW, name_students, date_create_HW, name_file, id_task) VALUES ('$dest_path', '$user', '$date_create', '$newFileName', '$id_task')");
                header('Location: ../layout/students/Tasks.php?id_SL=' .$id_SL .'&id_task=' .$id_task);
                exit();
            }
            
        }
        else
            {
                $_SESSION['messageError'] = 'Принимаются файлы только с расширенияе exe, xls, xlsx, doc, docx, pdf, pptx, mp3, zip, 7z, rar, mp4, avi, txt, ai, psd, indd, jpeg, png';
                header('Location: ../layout/students/AddFileMat.php?id_SL=' .$id_SL .'&id_task=' .$id_task);
                exit();
            }
        
    }
    else{
        
                header('Location: ../layout/students/Tasks.php?id_SL=' .$id_SL .'&id_task=' .$id_task);
                exit();
    }
    
?>