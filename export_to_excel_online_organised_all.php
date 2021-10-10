<?php
ob_start();
session_start();

error_reporting(E_ALL);
ini_set("display_errors", "ON");
require_once 'PHPExcel-1.8/Classes/PHPExcel.php';
require_once 'PHPExcel-1.8/Classes/PHPExcel/IOFactory.php';
include 'includes/connection.php';
$xls_filename = 'Online course organised'  . '.xls'; // Define Excel (.xls) file name
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Online course organised');

//setting first row as bold
$objPHPExcel->getActiveSheet()->getStyle('A1:S1')->getFont()->setBold(true);

$heading = array(
   'F_NAME' => 'Faculty Name',
  'Course_Name' => 'Course_Name',
    'Date_From' => 'From',
    'Date_To' => 'to',
    'Organised_by' => 'Organised_by',
    'Purpose' => 'Purpose of course',	
    'Target_Audience	' => 'Target_Audience',
    'faculty_role' => 'faculty_role',
    'full_part_time' => 'Full time/Part time?',
    'no_of_part' => 'Participants',
    'duration' => 'duration',
    'status' => 'status',
    'sponsored' => 'sponsored ?',
    'name_of_sponsor' => 'name_of_sponsor',
    'is_approved' => 'is_approved?',	
    'certificate_path' => 'certificate_path',
    'report_path' => 'report_path',
    'attendence_path' => 'attendence_path',	
    'updated_at' => 'Updated at'
);

$no_of_cols = count($heading);
$rowNumberH = 1;
$colH = 'A';
$columns = array('0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J', '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P', '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T', '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', '24' => 'Y', '25' => 'Z');

$q = $conn->query("SELECT facultydetails.F_NAME,Course_Name, Date_From, Date_To, Organised_by, Purpose, Target_Audience, faculty_role, full_part_time,
no_of_part, duration, status, sponsored, name_of_sponsor, is_approved, certificate_path, report_path, attendence_path, updated_at FROM online_course_organised inner join facultydetails on online_course_organised.Fac_ID = facultydetails.Fac_ID ");
if (mysqli_num_rows($q) > 0) {
    foreach ($heading as $z) {
		
		
		
        $objPHPExcel->getActiveSheet()->setCellValue($colH . $rowNumberH, $z);
        $objPHPExcel->getActiveSheet()->getColumnDimension($colH)->setWidth(20);
        $colH++;
    }
    $row = 2;
    while ($row_q = mysqli_fetch_assoc($q)) {
        $i = 0;
        foreach ($row_q as $key => $value) {
            if ($key == 'OC_A_ID' || $key == 'Fac_ID' || $key == 'Permission_copy' || $key == 'Certificate_copy' || $key == 'Report_copy')
                continue;
			
	
			
            $objPHPExcel->getActiveSheet()->setCellValue($columns[$i] . $row, $row_q[$key]);
            $i++;
        }
		$row++;
    }
}
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=$xls_filename");
header("Pragma: no-cache");
header("Expires: 0");
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');