<?php
ob_start();
session_start();

$Fac_ID = $_SESSION['Fac_ID'];
$sql = $_SESSION['sql'];
$facultyName = "";

$flag_count = $_SESSION['flag_count'];

$from_date =  $_SESSION['from_date'] ;
$to_date = $_SESSION['to_date'] ;


error_reporting(E_ALL);
ini_set("display_errors", "ON");
require_once 'PHPExcel-1.8/Classes/PHPExcel.php';
require_once 'PHPExcel-1.8/Classes/PHPExcel/IOFactory.php';
include 'includes/connection.php';
$xls_filename = 'paperpublicationanalysis'  . '.xls'; // Define Excel (.xls) file name
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Research Details');

//setting first row as bold
$objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getFont()->setBold(true);

$heading = array(
	'facultyName' => 'Faculty Name',
    'Paper_title' => 'Paper Title',
	'Paper_type' => 'Paper type',
	'Paper_N_I' => 'Paper level',	
	'paper_category' => 'Paper category',	
	'conf_journal_name' => 'conf_journal_name',	
	
	'Date_from' => 'Start Date',
	'Date_to' => 'End Date',
	'Location' => 'Location',
	'Paper_co_authors' => 'Paper_co_authors',
	'volume' => 'volume',
    'scopusindex' => 'Index applicable?(Scopus/SCI/both)',
    'scopus' => 'Index (Scopus/SCI/both)',
    'h_index' => 'h_index',
	'citations' => 'citations',
	'FDC_Y_N' => 'FDC applicable ?',
	'presentation_status' => 'presentation_status',
	'presented_by' => 'presented_by',
	'Link_publication' => 'Link of publication',
	'Paper_awards' => 'Paper_awards',
	'FDC_approved_disapproved' => 'FDC_approved_disapproved',
	'Udate' => 'Updated on',
	'noofdays' => 'Number Of Days'
	
	
);

$no_of_cols = count($heading);
$rowNumberH = 1;
$colH = 'A';
$columns = array('0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J', '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P', '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T', '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', '24' => 'Y', '25' => 'Z');

if($flag_count === 1)
{
	$sql = "select * from faculty where Date_from >= '$from_date' and Date_from <= '$to_date' ";

}
else if($flag_count === 2)
{
			$sname = $_SESSION['sname'] ;
	$sql = "SELECT * from faculty inner join facultydetails on faculty.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' ";
	
}
else if($flag_count === 3)
{
			$from_date =  $_SESSION['from_date'] ;
			$to_date = $_SESSION['to_date'] ;
			$sname = $_SESSION['sname'] ;
			$sql = "SELECT * from faculty inner join facultydetails on faculty.Fac_ID = facultydetails.Fac_ID and facultydetails.F_NAME like '%$sname%' and faculty.Date_from >= '$from_date' and faculty.Date_from <= '$to_date'";
		
	
}


$q = $conn->query($sql);
if (mysqli_num_rows($q) > 0) {
    foreach ($heading as $h) {
        $objPHPExcel->getActiveSheet()->setCellValue($colH . $rowNumberH, $h);
        $objPHPExcel->getActiveSheet()->getColumnDimension($colH)->setWidth(20);
        $colH++;
    }
    $row = 0;
    while ($row_q = mysqli_fetch_assoc($q)) {
        $i = 0;
        foreach ($row_q as $key => $value) {
           // if ($key == 'P_ID' || $key == 'Fac_ID' || $key == 'Paper_copy' || $key == 'Certificate_copy' || $key == 'report_copy' || $key == 'paper_path' || $key == 'certificate_path' || $key == 'report_path' || $key == 'Adate')
               // continue;
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