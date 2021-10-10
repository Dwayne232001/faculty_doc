<?php
ob_start();
session_start();

include("includes/connection.php");

if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}

if(isset($_SESSION['type'])){
    if($_SESSION['type'] != 'hod' && $_SESSION['type'] != 'cod' && $_SESSION['type']!='com'){
    //if not hod then send the user to login page
    session_destroy();
    header("location:index.php");
  }
  }  
  

  $fid=$_SESSION['Fac_ID'];
  
  $queryrun="SELECT * FROM facultydetails where Fac_ID=$fid";
  $resultrun = mysqli_query($conn, $queryrun);
  while($row=mysqli_fetch_assoc($resultrun)){
  $_SESSION['Dept']=$row['Dept'];
  $_SESSION['type']=$row['type'];
  }

error_reporting(E_ALL);
ini_set("display_errors", "ON");
require_once 'PHPExcel-1.8/Classes/PHPExcel.php';
require_once 'PHPExcel-1.8/Classes/PHPExcel/IOFactory.php';
include 'includes/connection.php';
$xls_filename = 'Data in format'  . '.xls'; // Define Excel (.xls) file name
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Paper Details');

//setting first row as bold
$objPHPExcel->getActiveSheet()->getStyle('A1:W1')->getFont()->setBold(true);

$heading = array(
    'F_NAME' => 'Faculty Name',
    'Paper_title' => 'Title of paper',
	'Location' => 'Affiliation of Publication/Organizers',	
    'Paper_type' => 'Conference/Journal',    
	'noofdays' => 'No of days',
	
	'Date_from' => 'Start date',
	'Date_to' => 'End date',
	'citations' => 'No of citations as on date',
	
	'h_index' => 'H Index',	
	'Paper_awards' => 'Awards if any'
	
);

$no_of_cols = count($heading);
$rowNumberH = 1;
$colH = 'A';
$columns = array('0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J', '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P', '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T', '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', '24' => 'Y', '25' => 'Z');

$q = $conn->query("SELECT F_NAME, Paper_title, faculty.Location, Paper_type, noofdays, Date_from, Date_to,citations, h_index, Paper_awards from faculty inner join facultydetails on faculty.Fac_ID = facultydetails.Fac_ID  ");
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
            if ($key == 'P_ID' || $key == 'Fac_ID' || $key == 'Paper_copy' || $key == 'Certificate_copy' || $key == 'report_copy')
                continue;
			
		if($row_q[$key] == 'journal')
			$row_q[$key]= 'J';
		else if($row_q[$key] == 'conference')
			$row_q[$key] = 'C';	
		
			
            $objPHPExcel->getActiveSheet()->setCellValue($columns[$i] . $row, $row_q[$key]);
            $i++;
        }
		$row++;
    }
}



// Create a new worksheet, after the default sheet
$objPHPExcel->createSheet();

$objPHPExcel->setActiveSheetIndex(1);

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('STTP Workshops etc');

//setting first row as bold
$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);

$heading = array(
    'F_NAME' => 'Faculty Name',

    'Act_title' => 'Title of STTP/WS attended ',
    'Organized_by' => 'Affiliation of Publication/Organizers of STTP WS etc',
    'Act_type' => 'STTP/Workshop/Seminar',
	'noofdays' => 'No of days',
	
    'Date_from' => 'From Date',
    'Date_to' => 'To Date',

    'Awards' => 'Awards'
 


);

$no_of_cols = count($heading);
$rowNumberH = 1;
$colH = 'A';
$columns = array('0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J', '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P', '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T', '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', '24' => 'Y', '25' => 'Z');

$q = $conn->query("SELECT F_NAME, Act_title, Organized_by, Act_type, noofdays, Date_from, Date_to, Awards FROM attended inner join facultydetails on attended.Fac_ID = facultydetails.Fac_ID ");
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
            if ($key == 'A_ID' || $key == 'Fac_ID' || $key == 'Permission_copy' || $key == 'Certificate_copy' || $key == 'Report_copy')
                continue;
			
			if($row_q[$key] == 'Workshop')
				$row_q[$key]= 'WS';
			
			
			
            $objPHPExcel->getActiveSheet()->setCellValue($columns[$i] . $row, $row_q[$key]);
            $i++;
        }
		$row++;
    }
}

// Create a new worksheet, after the default sheet
$objPHPExcel->createSheet();

$objPHPExcel->setActiveSheetIndex(2);

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Online Offline Course');

//setting first row as bold
$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);

$heading = array(
    'F_NAME' => 'Faculty Name',
    'Course_Name' => 'Title of online/offline course attended ',
    'Organised_by' => 'Affiliation of Organizers etc',
    'type_of_course' => 'Type of Course',
	'noofdays' => 'No of days',
	
    'Date_from' => 'From Date',
    'Date_to' => 'To Date'
);

$no_of_cols = count($heading);
$rowNumberH = 1;
$colH = 'A';
$columns = array('0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J', '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P', '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T', '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', '24' => 'Y', '25' => 'Z');

$q = $conn->query("SELECT F_NAME, Course_Name, Organised_by, type_of_course, noofdays, Date_from, Date_to FROM online_course_attended inner join facultydetails on online_course_attended.Fac_ID = facultydetails.Fac_ID ");
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
            if ($key == 'A_ID' || $key == 'Fac_ID' || $key == 'Permission_copy' || $key == 'Certificate_copy' || $key == 'Report_copy')
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
?>