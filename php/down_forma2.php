<?php 
//создание ексель файла с заполнением данными из бд
    session_start();

    $user=$_SESSION['users'];
    $url=$_SERVER['HTTP_REFERER'];
    include('bd.php');

    $query=mysqli_query($bd,"SELECT * FROM `users_teachers` WHERE teachers_log='$user'");
    $FIO=mysqli_fetch_assoc($query);
    $F=$FIO['teachers_f'];
    $N=$FIO['teachers_n'];
    $O=$FIO['teachers_o'];
    if(isset($_POST['month'])){
        $month=$_POST['month'];
    }
    
    $newDate=new DateTime($month);
    $newMonth=$newDate->format('m');
    $newYear=$newDate->format('Y');
    switch($newMonth){
        case '01':
            $monthResult='Январь';
            break;
        case '02':
            $monthResult='Февраль';
            break;
        case '03':
            $monthResult='Март';
            break;
        case '04':
            $monthResult='Апрель';
            break;
        case '05':
            $monthResult='Май';
            break;
        case '06':
            $monthResult='Июнь';
            break;
        case '07':
            $monthResult='Июль';
            break;
        case '08':
            $monthResult='Август';
            break;
        case '09':
            $monthResult='Сентябрь';
            break;
        case '10':
            $monthResult='Октябрь';
            break;
        case '11':
            $monthResult='Ноябрь';
            break;
        case '12':
            $monthResult='Декабрь';
            break;
    }
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=my_excel_filename.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    require_once 'PHPExcel.php';
 
    $objExel = new PHPExcel();
    $objExel->setActiveSheetIndex(0);

    $objWriter=PHPExcel_IOFactory::createWriter($objExel,'Excel5');
 
    $sheet=$objExel->getActiveSheet();
    $style = array(
        'font' => array(
            'name' => 'Times New Roman',
            'size' => 11,  
        )
    );
    $sheet->getDefaultStyle()->applyFromArray($style);
    $sheet->setTitle('Форма отчета 2');
    $sheet->mergeCells('B4:C4');//объединение
    $sheet->setCellValue('B4', 'ФИО Преподавателя:');//добавление
    $sheet->getColumnDimension('C')->setWidth(15);//ширина
    $sheet->getRowDimension("6")->setRowHeight(35);//высота
    $sheet->mergeCells('D4:P4');
    $sheet->setCellValue('D4', $F .' '. $N .' '. $O);
    $sheet->getStyle("B4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle("C6")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle("C6")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $sheet->getStyle("B6")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle("B6")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $sheet->getStyle("D6")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle("D6")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $sheet->mergeCells('U4:X4');
    $sheet->setCellValue('U4', 'Месяц:');
    $sheet->getStyle("U4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle("U4")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $sheet->mergeCells('AA4:AG4');
    $sheet->setCellValue('AA4', $monthResult . ' ' . $newYear . ' г.');
    $sheet->getStyle("AA4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle("AA4")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $style = array(
        'font' => array(
            'name'      => 'Times New Roman',
            'size'      => 18,
            'bold'      => true,   
        )
    );
    $sheet->getStyle('AA4')->applyFromArray($style);


    $sheet->mergeCells('B6:B7');$sheet->mergeCells('C6:C7');$sheet->mergeCells('D6:D7');
    $sheet->setCellValue('B6', '№ п/п');$sheet->setCellValue('C6', 'Наименование дисциплины');$sheet->setCellValue('D6', 'Группа');
    $sheet->getStyle("C6")->getAlignment()->setWrapText(true);
    $sheet->mergeCells('E6:AI6');
    $sheet->setCellValue('E6', 'Числа месяца');
    $sheet->getStyle("E6")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle("E6")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $column=4;
    $row=7;
    for($i=1;$i<=31;$i++){
        $sheet->setCellValueExplicitByColumnAndRow($column,$row,$i,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $sheet->getStyleByColumnAndRow($column, $row)->getAlignment()->
                setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getColumnDimensionByColumn($column)->setWidth(5);
        $column++;
    }
    $sheet->mergeCells('AJ6:AJ7');
    $sheet->setCellValue('AJ6', 'Часов дано в группе');
    $sheet->getStyle("AJ6")->getAlignment()->setWrapText(true);
    $sheet->getStyle("AJ6")->getFont()->setSize(8);
    $border = array(
        'borders'=>array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => '000000')
            )
        )
    );
    $sheet->getStyle("B6:AJ7")->applyFromArray($border);
    $query=mysqli_query($bd, "SELECT * FROM `study_load` WHERE name_teacher='$user' ORDER BY name_group");
    $nom=1;
    $columnB=1;
    $rowB=8;
    $columnE=4;
    $summHour=0;
    while($study_load=mysqli_fetch_assoc($query)){
        $id_SL=$study_load['id_SL'];
        $sheet->setCellValueExplicitByColumnAndRow($columnB,$rowB,$nom,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $sheet->getStyleByColumnAndRow($columnB,$rowB)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyleByColumnAndRow($columnB,$rowB)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $sheet->setCellValueExplicitByColumnAndRow($columnB+1,$rowB,$study_load['name_dis']);
        $sheet->getStyleByColumnAndRow($columnB+1,$rowB)->getAlignment()->setWrapText(true);
        $sheet->getStyleByColumnAndRow($columnB+1,$rowB)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyleByColumnAndRow($columnB+1,$rowB)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $sheet->setCellValueExplicitByColumnAndRow($columnB+2,$rowB,$study_load['name_group']);
        $sheet->getStyleByColumnAndRow($columnB+2,$rowB)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyleByColumnAndRow($columnB+2,$rowB)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $tasks=mysqli_query($bd, "SELECT * FROM `tasks` WHERE DATE_FORMAT(data_create, '%Y-%m')='$month' and id_SL='$id_SL' ORDER BY data_create");
        while($hour=mysqli_fetch_assoc($tasks)){
                $data=$hour['data_create'];
                $data=new DateTime($data);
                $newDay=$data->format('d');
                $sheet->setCellValueExplicitByColumnAndRow($columnE+$newDay-1,$rowB,$hour['time_tasks'],PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $sheet->getStyleByColumnAndRow($columnE+$newDay-1,$rowB)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $sheet->getStyleByColumnAndRow($columnE+$newDay-1,$rowB)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $summHour+=$hour['time_tasks'];
                $sheet->setCellValueExplicitByColumnAndRow($columnE+31,$rowB,$summHour,PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $sheet->getStyleByColumnAndRow($columnE+31,$rowB)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $sheet->getStyleByColumnAndRow($columnE+31,$rowB)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        }
        for($j=0;$j<=34; $j++){
        $border = array(
            'borders'=>array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => '000000')
                )
            )
        );
        $sheet->getStyleByColumnAndRow($columnB+$j, $rowB)->applyFromArray($border);
        }
        $rowB++;
        $nom++;
    }
    $sheet->mergeCells('B' .$rowB .':AI' . $rowB);
    $sheet->setCellValue('B'. $rowB, 'Всего часов:');
    $sheet->getStyle("B" . $rowB)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $sheet->getStyle("B" . $rowB)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    
    for($j=0;$j<=33;$j++){
        $sheet->getStyleByColumnAndRow($columnB+$j, $rowB)->applyFromArray($border);
    }
    $sheet->setCellValue('AJ'. $rowB, "=SUM(AJ8:AJ".$rowB .")");
    $sheet->getStyle("AJ" . $rowB)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $sheet->getStyle("AJ" . $rowB)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $sheet->getStyle("AJ" . $rowB)->applyFromArray($border);

    $objWriter->save('Forma2.xls');
    $objWriter->save('php://output');
    unlink('Forma2.xls');
    exit();	
    
    header("Location: " . $url);
?>