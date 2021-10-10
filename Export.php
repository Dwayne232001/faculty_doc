<?php
ob_start();
session_start();

error_reporting(E_ALL);
ini_set("display_errors", "ON");
require_once 'PHPExcel-1.8/Classes/PHPExcel.php';
require_once 'PHPExcel-1.8/Classes/PHPExcel/IOFactory.php';
// unset($files);
$files=array();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['email']) && !empty($_POST['email'])){
        $emails = $_POST['email'];
            foreach($emails as $e){
                // print("ID is " . $e);
                $facid = $e;
                // exportexcel($facid);
//                }
//     }
// }

// class export{

    // public static function exportexcel($facid)
    // {

include 'includes/connection.php';
$xls_filename = 'DataMissing' . $facid . '.xls'; // Define Excel (.xls) file name
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);
$FacID=$facid;
// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Paper Details');

//setting first row as bold
$objPHPExcel->getActiveSheet()->getStyle('A1:W1')->getFont()->setBold(true);

$heading = array(
    'F_NAME' => 'Faculty Name',
    'Paper_title' => 'Paper Title'
   
);

$no_of_cols = count($heading);
$rowNumberH = 1;
$colH = 'A';
$columns = array('0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J', '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P', '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T', '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', '24' => 'Y', '25' => 'Z');


$q = $conn->query("SELECT facultydetails.F_NAME,Paper_title from faculty inner join facultydetails on (facultydetails.Fac_ID='$FacID') and (faculty.Fac_ID = facultydetails.Fac_ID) where (paper_path = 'NULL' OR paper_path = '') and  (certificate_path = 'NULL' OR certificate_path = '') and (report_path = 'NULL' OR report_path = '') " );


 
 if ($q!= false && mysqli_num_rows($q) > 0) {
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
$objPHPExcel->getActiveSheet()->setTitle('Technical Papers Reviewed');

//setting first row as bold
$objPHPExcel->getActiveSheet()->getStyle('A1:n1')->getFont()->setBold(true);

$heading = array(
    'F_NAME' => 'Faculty Name',

    'Paper_title' => 'Paper Reviewed Title',
    'organised_by' => 'organised_by'
    


);

$no_of_cols = count($heading);
$rowNumberH = 1;
$colH = 'A';
$columns = array('0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J', '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P', '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T', '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', '24' => 'Y', '25' => 'Z');

$q = $conn->query("SELECT F_NAME,Paper_title, organised_by from paper_review inner join facultydetails on (facultydetails.Fac_ID='$FacID') and (paper_review.Fac_ID = facultydetails.Fac_ID) where (mail_letter_path = 'NULL' OR mail_letter_path = '') and (certificate_path = 'NULL' OR certificate_path = '') and (report_path = 'NULL' OR report_path = '')");

 if ($q!= false && mysqli_num_rows($q) > 0) {
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
$objPHPExcel->getActiveSheet()->setTitle('STTP attended');

//setting first row as bold
$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->getFont()->setBold(true);

$heading = array(
    'F_NAME' => 'Faculty Name',
    'Act_title' => 'Activity Title',
    'Act_type' => 'Activity Type',
    'Organized_by' => 'Organized by',
    'Date_from' => 'From',
    'Date_to' => 'To',
    'Location' => 'Location'
   
);

$no_of_cols = count($heading);
$rowNumberH = 1;
$colH = 'A';
$columns = array('0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J', '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P', '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T', '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', '24' => 'Y', '25' => 'Z');

$q = $conn->query("SELECT F_NAME,Act_title,Act_type,Organized_by, 
 Date_from, Date_to,Location FROM attended inner join facultydetails on (facultydetails.Fac_ID='$FacID') and (attended.Fac_ID = facultydetails.Fac_ID) where (Certificate_path = 'NULL' OR Certificate_path = '') and (Report_path = 'NULL' OR Report_path = '') and (Permission_path = 'NULL' OR Permission_path = '')");
 
 
 
if ($q!= false && mysqli_num_rows($q) > 0) {
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

// Create a new worksheet, after the default sheet
$objPHPExcel->createSheet();

$objPHPExcel->setActiveSheetIndex(3);

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('STTP Organized');

//setting first row as bold
$objPHPExcel->getActiveSheet()->getStyle('A1:U1')->getFont()->setBold(true);

$heading = array(
    'F_NAME' => 'Faculty Name',
    'Act_title' => 'Activity Title',
    'Act_type' => 'Activity Type',
    'Organized_by' => 'Organized by',
    'Resource' => 'Resource person',
    'Date_from' => 'From',
    'Date_to' => 'To',
    'Location' => 'Location'
    
);

$no_of_cols = count($heading);
$rowNumberH = 1;
$colH = 'A';
$columns = array('0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J', '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P', '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T', '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', '24' => 'Y', '25' => 'Z');

$q = $conn->query("SELECT F_NAME, Act_title, Act_type, Organized_by, organised.Resource, Date_from, Date_to,
   organised.Location FROM organised inner join facultydetails on (facultydetails.Fac_ID='$FacID') and (organised.Fac_ID = facultydetails.Fac_ID) where (Brochure_path = 'NULL' OR Brochure_path = '') and (Permission_path = 'NULL' OR Permission_path = '') and (Certificate_path = 'NULL' OR Certificate_path = '') and (Report_path = 'NULL' OR Report_path = '')");
if ($q!= false && mysqli_num_rows($q) > 0) {
    foreach ($heading as $z) {
        $objPHPExcel->getActiveSheet()->setCellValue($colH . $rowNumberH, $z);
        $objPHPExcel->getActiveSheet()->getColumnDimension($colH)->setWidth(20);
        $colH++;
    }
    $row = 2;
    while ($row_q = mysqli_fetch_assoc($q)) {
        $i = 0;
        foreach ($row_q as $key => $value) {
            if ($key == 'A_ID' || $key == 'Fac_ID' || $key == 'Brochure_copy' || $key == 'Permission_copy' || $key == 'Certificate_copy' || $key == 'Report_copy')
                continue;
            $objPHPExcel->getActiveSheet()->setCellValue($columns[$i] . $row, $row_q[$key]);
            $i++;
        }
        $row++;
    }
}

// Create a new worksheet, after the default sheet
$objPHPExcel->createSheet();

$objPHPExcel->setActiveSheetIndex(4);

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Research Details');

//setting first row as bold
$objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getFont()->setBold(true);

$heading = array(
    'F_NAME' => 'Faculty Name',
    'researchTitle' => 'researchTitle',
    'fromDate' => 'fromDate',
    'toDate' => 'toDate',
    'submittedTo' => 'submittedTo'
  
);

$no_of_cols = count($heading);
$rowNumberH = 1;
$colH = 'A';
$columns = array('0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J', '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P', '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T', '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', '24' => 'Y', '25' => 'Z');

$q = $conn->query("SELECT facultyName, researchTitle, fromDate,toDate, submittedTo FROM researchdetails where (facultydetails.Fac_ID='$FacID') and (reportPath = '' or reportPath = 'NULL' )");

if ($q!= false && mysqli_num_rows($q) > 0) {
    foreach ($heading as $z) {
        $objPHPExcel->getActiveSheet()->setCellValue($colH . $rowNumberH, $z);
        $objPHPExcel->getActiveSheet()->getColumnDimension($colH)->setWidth(20);
        $colH++;
    }
    $row = 2;
    while ($row_q = mysqli_fetch_assoc($q)) {
        $i = 0;
        foreach ($row_q as $key => $value) {
            if ($key == 'researchId' || $key == 'Fac_ID' )
                continue;
            $objPHPExcel->getActiveSheet()->setCellValue($columns[$i] . $row, $row_q[$key]);
            $i++;
        }
        $row++;
    }
}

// Create a new worksheet, after the default sheet
$objPHPExcel->createSheet();

$objPHPExcel->setActiveSheetIndex(5);

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Faculty Interaction');

//setting first row as bold
$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);

$heading = array(
    'F_NAME' => 'Faculty Name',
    'organized' => 'organized By',
    'durationf' => 'From',
    'durationt' => 'to',
    
   
);

$no_of_cols = count($heading);
$rowNumberH = 1;
$colH = 'A';
$columns = array('0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J', '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P', '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T', '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', '24' => 'Y', '25' => 'Z');

$q = $conn->query("SELECT F_NAME,organized,durationf,durationt FROM invitedlec inner join facultydetails on (facultydetails.Fac_ID='$FacID') and (invitedlec.Fac_ID = facultydetails.Fac_ID) where (invitation_path = 'NULL' OR invitation_path = '') and (certificate_path = 'NULL' OR certificate_path = '')");
 
if ($q!= false && mysqli_num_rows($q) > 0) {
    foreach ($heading as $z) {
        $objPHPExcel->getActiveSheet()->setCellValue($colH . $rowNumberH, $z);
        $objPHPExcel->getActiveSheet()->getColumnDimension($colH)->setWidth(20);
        $colH++;
    }
    $row = 2;
    while ($row_q = mysqli_fetch_assoc($q)) {
        $i = 0;
        foreach ($row_q as $key => $value) {
            if ($key == 'invited_id' || $key == 'Fac_ID' )
                continue;
            $objPHPExcel->getActiveSheet()->setCellValue($columns[$i] . $row, $row_q[$key]);
            $i++;
        }
        $row++;
    }
}


// Create a new worksheet, after the default sheet
$objPHPExcel->createSheet();

$objPHPExcel->setActiveSheetIndex(6);

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Organised Guest Lecture');

//setting first row as bold
$objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getFont()->setBold(true);

$heading = array(
    'F_NAME' => 'Faculty Name',
    'topic' => 'Topic of Lecture',
    'durationf' => 'From',
    'durationt' => 'to'
     
);

$no_of_cols = count($heading);
$rowNumberH = 1;
$colH = 'A';
$columns = array('0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J', '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P', '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T', '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', '24' => 'Y', '25' => 'Z');

$q = $conn->query("SELECT F_NAME,topic,durationf,durationt FROM guestlec inner join facultydetails on (facultydetails.Fac_ID='$FacID') and (guestlec.Fac_ID = facultydetails.Fac_ID) where (attendance_path = 'NULL' OR attendance_path = '') and (permission_path = 'NULL' OR permission_path = '') and (certificate1_path = 'NULL' OR certificate1_path = '') and (report_path = 'NULL' OR report_path = '')");

if ($q!= false && mysqli_num_rows($q) > 0) { 
    foreach ($heading as $z) {
        $objPHPExcel->getActiveSheet()->setCellValue($colH . $rowNumberH, $z);
        $objPHPExcel->getActiveSheet()->getColumnDimension($colH)->setWidth(20);
        $colH++;
    }
    $row = 2;
    while ($row_q = mysqli_fetch_assoc($q)) {
        $i = 0;
        foreach ($row_q as $key => $value) {
            if ($key == 'p_id' || $key == 'fac_id' )
                continue;
            $objPHPExcel->getActiveSheet()->setCellValue($columns[$i] . $row, $row_q[$key]);
            $i++;
        }
        $row++;
    }
}


// Create a new worksheet, after the default sheet
$objPHPExcel->createSheet();

$objPHPExcel->setActiveSheetIndex(7);

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Online Offline Course attended');

//setting first row as bold
$objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getFont()->setBold(true);

$heading = array(
    'F_NAME' => 'Faculty Name',
    'Course_Name' => 'Course_Name',
    'Date_From' => 'From',
    'Date_To' => 'to',
    'Organised_by' => 'Organised_by'
    
);

$no_of_cols = count($heading);
$rowNumberH = 1;
$colH = 'A';
$columns = array('0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J', '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P', '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T', '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', '24' => 'Y', '25' => 'Z');

$q = $conn->query("SELECT F_NAME,Course_Name,Date_From,Date_To,Organised_by FROM online_course_attended where inner join facultydetails on (facultydetails.Fac_ID='$FacID') and (online_course_attended.Fac_ID = facultydetails.Fac_ID) where (certificate_path = 'NULL' OR certificate_path = '') and (report_path = 'NULL' OR report_path = '') " );

if ($q!= false && mysqli_num_rows($q) > 0) {
    foreach ($heading as $z) {
        $objPHPExcel->getActiveSheet()->setCellValue($colH . $rowNumberH, $z);
        $objPHPExcel->getActiveSheet()->getColumnDimension($colH)->setWidth(20);
        $colH++;
    }
    $row = 2;
    while ($row_q = mysqli_fetch_assoc($q)) {
        $i = 0;
        foreach ($row_q as $key => $value) {
            if ($key == 'OC_A_ID' || $key == 'Fac_ID' )
                continue;
            $objPHPExcel->getActiveSheet()->setCellValue($columns[$i] . $row, $row_q[$key]);
            $i++;
        }
        $row++;
    }
}

// Create a new worksheet, after the default sheet
$objPHPExcel->createSheet();

$objPHPExcel->setActiveSheetIndex(8);

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Online Offline Course Organised');

//setting first row as bold
$objPHPExcel->getActiveSheet()->getStyle('A1:R1')->getFont()->setBold(true);

$heading = array(
    'F_NAME' => 'Faculty Name',
    'Course_Name' => 'Course_Name',
    'Date_From' => 'From',
    'Date_To' => 'to',
    'Organised_by' => 'Organised_by'
   
);


$no_of_cols = count($heading);
$rowNumberH = 1;
$colH = 'A';
$columns = array('0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J', '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P', '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T', '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', '24' => 'Y', '25' => 'Z');

$q = $conn->query("SELECT F_NAME,Course_Name,Date_From,Date_To,Organised_by FROM online_course_organised inner join facultydetails on (facultydetails.Fac_ID='$FacID') and (online_course_organised.Fac_ID = facultydetails.Fac_ID) where (certificate_path = 'NULL' OR certificate_path = '') and (report_path = 'NULL' OR report_path = '') and (attendence_path = 'NULL' OR attendence_path = '') ");

if ($q!= false && mysqli_num_rows($q) > 0) {
    foreach ($heading as $z) {
        $objPHPExcel->getActiveSheet()->setCellValue($colH . $rowNumberH, $z);
        $objPHPExcel->getActiveSheet()->getColumnDimension($colH)->setWidth(20);
        $colH++;
    }
    $row = 2;
    while ($row_q = mysqli_fetch_assoc($q)) {
        $i = 0;
        foreach ($row_q as $key => $value) {
            if ($key == 'OC_O_ID' || $key == 'Fac_ID' )
                continue;
            $objPHPExcel->getActiveSheet()->setCellValue($columns[$i] . $row, $row_q[$key]);
            $i++;
        }
        $row++;
    }
}

// Create a new worksheet, after the default sheet
$objPHPExcel->createSheet();

$objPHPExcel->setActiveSheetIndex(9);

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Co curricular activity');

//setting first row as bold
$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);

$heading = array(
    'F_NAME' => 'Faculty Name',
  'activity_name' => 'activity_name',
    'Date_from' => 'From',
    'Date_to' => 'to',
    'organized_by' => 'Organised_by'
     
   
);


$no_of_cols = count($heading);
$rowNumberH = 1;
$colH = 'A';
$columns = array('0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J', '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P', '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T', '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', '24' => 'Y', '25' => 'Z');

$q = $conn->query("SELECT F_NAME,activity_name,Date_from,Date_to, organized_by
 FROM co_curricular inner join facultydetails on (facultydetails.Fac_ID='$FacID') and (co_curricular.Fac_ID = facultydetails.Fac_ID) where (certificate_path = 'NULL' OR certificate_path = '') and (report_path = 'NULL' OR report_path = '') and (permission_path = 'NULL' OR permission_path = '')");

 
 if ($q!= false && mysqli_num_rows($q) > 0) {
    foreach ($heading as $z) {
        $objPHPExcel->getActiveSheet()->setCellValue($colH . $rowNumberH, $z);
        $objPHPExcel->getActiveSheet()->getColumnDimension($colH)->setWidth(20);
        $colH++;
    }
    $row = 2;
    while ($row_q = mysqli_fetch_assoc($q)) {
        $i = 0;
        foreach ($row_q as $key => $value) {
            if ($key == 'co_curricular_ID' || $key == 'Fac_ID' || $key == 'permission_copy' || $key == 'Certificate_copy' || $key == 'report_copy')
                continue;
            $objPHPExcel->getActiveSheet()->setCellValue($columns[$i] . $row, $row_q[$key]);
            $i++;
        }
        $row++;
    }
} 



// Create a new worksheet, after the default sheet
$objPHPExcel->createSheet();

$objPHPExcel->setActiveSheetIndex(10);

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Extra curricular activity');

//setting first row as bold
$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);

$heading = array(
    'F_NAME' => 'Faculty Name',
    'activity_name' => 'activity_name',
    'Date_from' => 'From',
    'Date_to' => 'to',
    'organized_by' => 'Organised_by',
 
);


$no_of_cols = count($heading);
$rowNumberH = 1;
$colH = 'A';
$columns = array('0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J', '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P', '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T', '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', '24' => 'Y', '25' => 'Z');

$q = $conn->query("SELECT F_NAME,activity_name,Date_from,Date_to, organized_by
  FROM ex_curricular where inner join facultydetails on (facultydetails.Fac_ID='$FacID') and (ex_curricular.Fac_ID = facultydetails.Fac_ID) where (certificate_path = 'NULL' OR certificate_path = '') and (report_path = 'NULL' OR report_path = '') and (permission_path = 'NULL' OR permission_path = '')");

 
 if ($q!= false && mysqli_num_rows($q) > 0) {
    foreach ($heading as $z) {
        $objPHPExcel->getActiveSheet()->setCellValue($colH . $rowNumberH, $z);
        $objPHPExcel->getActiveSheet()->getColumnDimension($colH)->setWidth(20);
        $colH++;
    }
    $row = 2;
    while ($row_q = mysqli_fetch_assoc($q)) {
        $i = 0;
        foreach ($row_q as $key => $value) {
            if ($key == 'ex_curricular_ID' || $key == 'Fac_ID' || $key == 'permission_copy' || $key == 'Certificate_copy' || $key == 'report_copy')
                continue;
            $objPHPExcel->getActiveSheet()->setCellValue($columns[$i] . $row, $row_q[$key]);
            $i++;
        }
        $row++;
    }
}


// Create a new worksheet, after the default sheet
$objPHPExcel->createSheet();

$objPHPExcel->setActiveSheetIndex(11);

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Any other activity');

//setting first row as bold
$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);

$heading = array(
    'F_NAME' => 'Faculty Name',
    'activity_name' => 'activity_name',
    'Date_from' => 'From',
    'Date_to' => 'to',
    'organized_by' => 'Organised_by'
    
);


$no_of_cols = count($heading);
$rowNumberH = 1;
$colH = 'A';
$columns = array('0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J', '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P', '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T', '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', '24' => 'Y', '25' => 'Z');

$q = $conn->query("SELECT F_NAME,activity_name,Date_from,Date_to, organized_by
  FROM any_other_activity inner join facultydetails on (facultydetails.Fac_ID='$FacID') and (any_other_activity.Fac_ID = facultydetails.Fac_ID) where (certificate_path = 'NULL' OR certificate_path = '') and (report_path = 'NULL' OR report_path = '') and (permission_path = 'NULL' OR permission_path = '')");


 
 if ($q!= false && mysqli_num_rows($q) > 0) {
    foreach ($heading as $z) {
        $objPHPExcel->getActiveSheet()->setCellValue($colH . $rowNumberH, $z);
        $objPHPExcel->getActiveSheet()->getColumnDimension($colH)->setWidth(20);
        $colH++;
    }
    $row = 2;
    while ($row_q = mysqli_fetch_assoc($q)) {
        $i = 0;
        foreach ($row_q as $key => $value) {
            if ($key == 'any_other_ID' || $key == 'Fac_ID' || $key == 'permission_copy' || $key == 'Certificate_copy' || $key == 'report_copy')
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
// $objWriter->save('php://output');
$objWriter->save('downloads/' . $xls_filename);
// array_push($files,$xls_filename);
array_push($files,'downloads/' . $xls_filename);
// $files=array($xls_filename);
// $files=array('downloads/' . $xls_filename . '.xls');
    }
$zipname = 'file.zip';
// $zipname = $files.length();
$zip = new ZipArchive;
// $zip->open($zipname, ZipArchive::CREATE);
$zip->open($zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE);
foreach ($files as $file) {
  $zip->addFile($file);
}
$zip->close();
ob_end_flush();
ob_clean();
header('Content-Type: application/zip');
header('Content-disposition: attachment; filename='.$zipname);
header('Content-Length: ' . filesize($zipname));

readfile($zipname);

// if(file_exists($zipname)){
//     unlink($zipname);
// }
// ob_end_flush();
// ob_clean();
    }
}
?>
