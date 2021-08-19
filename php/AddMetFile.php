<?php
//добавление файла к методическому материалу
    session_start();

    $user=$_SESSION['users'];
    include('bd.php');
    
    $group=$_GET['gr'];
    $dis=$_GET['dis'];
    $id_SL=$_GET['id_SL'];
    
    if(isset($_POST['Task'])){
        $id_task=$_POST['Task'];
        if($id_task=="value0"){
            unset($id_task);
        }
    }


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
        $date_create=date("Y-m-d");
        if (in_array($fileExtension, $allowedfileExtensions)) {
            $structure='../downloads/' . $_SESSION['users'] . '/' . $group . '/' . $dis . '/' . $date_create . '/';
            if(!is_dir($structure)){ //проверка на существование папки
            mkdir($structure, 0777 , true); //создание папки
            }
            
            $dest_path = $structure . $newFileName;
 
            if(move_uploaded_file($fileTmpPath, $dest_path))
            {
                $date_create=date("Y-m-d");
                $result=mysqli_query($bd, "INSERT INTO downloads_task (path_DT, name_file, date_create_DT, id_task) VALUES ('$dest_path', '$newFileName', '$date_create', '$id_task')");
                header('Location: ../layout/teachers/MethodMat.php?gr=' .$group .'&dis=' .$dis . '&id_SL=' .$id_SL );
                exit();
            }
            
        }
        else
            {
                $_SESSION['messageError'] = 'Принимаются файлы только с расширенияе exe, xls, xlsx, doc, docx, pdf, pptx, mp3, zip, 7z, rar, mp4, avi, txt, ai, psd, indd, jpeg, png';
                header('Location: ../layout/teachers/MethodMat.php?gr=' .$group .'&dis=' .$dis . '&id_SL=' .$id_SL );
                exit();
            }
    }
    else{
        
                header('Location: ../layout/teachers/MethodMat.php?gr=' .$group .'&dis=' .$dis . '&id_SL=' .$id_SL );
                exit();
    }
    
?>