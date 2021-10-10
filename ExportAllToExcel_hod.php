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
$xls_filename = 'Data'  . '.xls'; // Define Excel (.xls) file name
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Paper Details');

//setting first row as bold
$objPHPExcel->getActiveSheet()->getStyle('A1:W1')->getFont()->setBold(true);

$heading = array(
    'F_NAME' => 'Faculty Name',
    'Paper_title' => 'Paper Title',
    'Paper_type' => 'Paper Type',
    'Paper_N_I' => 'Whether National/International ?',
    'cof_journal_name' => 'Name of conference/journal',
    'paper_category' => 'Paper Category',
	'Date_from' => 'Start date',
	'Date_to' => 'End date',
	'Location' => 'Location',
    'paper_path' => 'Paper Path',
    'certificate_path' => 'Certificate Path',
	'report_path' => 'Report Path',
	'Paper_co_authors' => 'Co-Author(s)',
	'volume' => 'Volume',
	'scopusindex' => 'Index(Scopus/Sci/Both)',
	'h_index' => 'H Index',
	'FDC_Y_N' => 'Whether FDC ?',
	'presentation_status' => 'Presented ?',
	'presented_by' => 'Presented by',
	'Link_publication' => 'Publication Link',
	'Paper_awards' => 'Awards',
	'FDC_approved_disapproved' => 'FDC_approved_disapproved',
	'Adate' => 'Added at',
	'Udate' => 'Updated at',
);

$no_of_cols = count($heading);
$rowNumberH = 1;
$colH = 'A';
$columns = array('0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J', '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P', '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T', '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', '24' => 'Y', '25' => 'Z');


$q = $conn->query("SELECT facultydetails.F_NAME,Paper_title,Paper_type, Paper_N_I, conf_journal_name, paper_category,
  Date_from, Date_to,faculty.Location, paper_path, certificate_path, report_path,
 Paper_co_authors, volume, scopusindex, h_index, FDC_Y_N, presentation_status, presented_by,
 Link_publication, Paper_awards, FDC_approved_disapproved, Adate, Udate from faculty inner join facultydetails on faculty.Fac_ID = facultydetails.Fac_ID");
 
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

    'Paper_title' => 'Paper Title',
    'Paper_type' => 'Paper Type',
    'Paper_N_I' => 'Whether National/International ?',
    'cof_journal_name' => 'Name of conference/journal',
    'paper_category' => 'Paper Category',
	'Date_from' => 'Start date',
	'Date_to' => 'End date',
	'organised_by' => 'organised_by',
	'details' => 'details',	
    'mail_letter_path' => 'Mail Invitation Path',
    'certificate_path' => 'Certificate Path',
	'report_path' => 'Report Path',	
	'volume' => 'Volume',	
	'last_added' => 'Updated at'


);

$no_of_cols = count($heading);
$rowNumberH = 1;
$colH = 'A';
$columns = array('0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J', '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P', '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T', '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', '24' => 'Y', '25' => 'Z');

$q = $conn->query("SELECT F_NAME,Paper_title,Paper_type, Paper_N_I, conf_journal_name, paper_category,
  Date_from, Date_to,organised_by, details, mail_letter_path, certificate_path, report_path,
 volume, last_added from paper_review inner join facultydetails on paper_review.Fac_ID = facultydetails.Fac_ID");

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
    'Location' => 'Location',
    'Status_Of_Activity' => 'Status_Of_Activity',
    'Equivalent_Duration' => 'Equivalent_Duration',
    'Awards' => 'Awards',
    'Certificate_path' => 'Certificate Path',
	'Report_path' => 'Report Path',	
	'Permission_path' => 'Paper Path',
	'FDC_Y_N' => 'FDC_Y_N',
	'LastUpdated' => 'LastUpdated',	
	'ApprovalStatus' => 'ApprovalStatus'
);

$no_of_cols = count($heading);
$rowNumberH = 1;
$colH = 'A';
$columns = array('0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J', '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P', '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T', '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', '24' => 'Y', '25' => 'Z');

$q = $conn->query("SELECT F_NAME,Act_title,Act_type,Organized_by, 
 Date_from, Date_to,Location, Status_Of_Activity,Equivalent_Duration,Awards,
 Certificate_path, Report_path, Permission_path, FDC_Y_N , LastUpdated, ApprovalStatus FROM attended inner join facultydetails on attended.Fac_ID = facultydetails.Fac_ID");
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
    'Location' => 'Location',
    'Coordinated_by' => 'Coordinated_by',
    'Role_Of_Faculty' => 'Role_Of_Faculty',
    'Time_Activities' => 'Time_Activities',
    'No_Of_Participants' => 'No_Of_Participants',
    'Equivalent_Duration' => 'Equivalent_Duration',
    'Status_Of_Activity' => 'Status_Of_Activity',
    'Sponsorship' => 'Sponsorship',
    'Sponsor_Details' => 'Sponsor_Details',
    'Approval_Details' => 'Approval_Details',
	'LastUpdated' => 'LastUpdated',	
    'Brochure_path' => 'Brochure Path',
	'Permission_path' => 'Paper Path',
    'Certificate_path' => 'Certificate Path',
	'Report_path' => 'Report Path'
);

$no_of_cols = count($heading);
$rowNumberH = 1;
$colH = 'A';
$columns = array('0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J', '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P', '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T', '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', '24' => 'Y', '25' => 'Z');

$q = $conn->query("SELECT F_NAME, Act_title, Act_type, Organized_by, Resource, Date_from, Date_to,
   Location, Coordinated_by, Role_Of_Faculty, Time_Activities, No_Of_Participants, Equivalent_Duration,
   Status_Of_Activity, Sponsorship,Sponsor_Details, Approval_Details,LastUpdated, Brochure_path,
  Permission_path, Certificate_path, Report_path FROM organised inner join facultydetails on organised.Fac_ID = facultydetails.Fac_ID");
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
    'submittedTo' => 'submittedTo',
    'principleInvestigator' => 'principleInvestigator',
    'coInvestigator' => 'coInvestigator',
    'proposedAmount' => 'proposedAmount',
    'radioApproval' => 'ApprovalStatus',
    'amountSanctioned' => 'amountSanctioned',
    'awardsWon' => 'awardsWon',
    'reportPath' => 'reportPath',
    'currentTimestamp' => 'Updated at'
);

$no_of_cols = count($heading);
$rowNumberH = 1;
$colH = 'A';
$columns = array('0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J', '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P', '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T', '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', '24' => 'Y', '25' => 'Z');

$q = $conn->query("SELECT F_NAME, researchTitle, fromDate,toDate, submittedTo, principleInvestigator,
   coInvestigator, proposedAmount,radioApproval, amountSanctioned, awardsWon, reportPath,currentTimestamp FROM researchdetails inner join facultydetails on researchdetails.Fac_ID = facultydetails.Fac_ID");
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
    'res_type' => 'Invited for',
    'award' => 'award',
    'topic' => 'topic of lecture',
    'details' => 'details',
    'tdate' => 'Updated at',
    'invitation_path' => 'invitation_path',
    'certificate_path' => 'certificate_path'
);

$no_of_cols = count($heading);
$rowNumberH = 1;
$colH = 'A';
$columns = array('0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J', '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P', '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T', '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', '24' => 'Y', '25' => 'Z');

$q = $conn->query("SELECT F_NAME,organized,durationf,durationt,res_type,
 award,topic,details,tdate,invitation_path, certificate_path FROM invitedlec inner join facultydetails on invitedlec.Fac_ID = facultydetails.Fac_ID");
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
    'durationt' => 'to',
    'name' => 'name of Resource Person',
    'designation' => 'designation',
    'organisation	' => 'organisation',
    'targetaudience' => 'targetaudience',
    'attendance_path' => 'attendance_path',
    'permission_path' => 'permission_path',
    'certificate1_path' => 'certificate_path',
    'report_path' => 'report_path',
    'tdate' => 'Updated at'
);

$no_of_cols = count($heading);
$rowNumberH = 1;
$colH = 'A';
$columns = array('0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J', '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P', '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T', '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', '24' => 'Y', '25' => 'Z');

$q = $conn->query("SELECT F_NAME,topic,durationf,durationt,name,designation,
  organisation,targetaudience,attendance_path,permission_path,certificate1_path,report_path,tdate FROM guestlec inner join facultydetails on guestlec.Fac_ID = facultydetails.Fac_ID");
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
    'Organised_by' => 'Organised_by',
    'Purpose' => 'Purpose of course',	
    'FDC_Y_N' => 'FDC Y/N ?',
    'type_of_course' => 'type_of_course',
    'status_of_activity' => 'status_of_activity',
    'duration' => 'duration in weeks or hours',
    'credit_audit' => 'credit_audit',	
    'certificate_path' => 'certificate_path',
    'report_path' => 'report_path',
    'updated_at' => 'Updated at'
);

$no_of_cols = count($heading);
$rowNumberH = 1;
$colH = 'A';
$columns = array('0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J', '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P', '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T', '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', '24' => 'Y', '25' => 'Z');

$q = $conn->query("SELECT F_NAME,Course_Name,Date_From,Date_To,Organised_by,Purpose,
 FDC_Y_N,type_of_course,status_of_activity,duration,credit_audit,certificate_path, report_path,updated_at FROM online_course_attended inner join facultydetails on online_course_attended.Fac_ID = facultydetails.Fac_ID");
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
    'Organised_by' => 'Organised_by',
    'Purpose' => 'Purpose of course',	
    'Target_Audience' => 'Target_Audience',
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

$q = $conn->query("SELECT F_NAME,Course_Name,Date_From,Date_To,Organised_by,Purpose,
 Target_Audience,faculty_role, full_part_time,no_of_part,duration,status,sponsored,
 name_of_sponsor,is_approved,certificate_path,report_path,attendence_path,updated_at FROM online_course_organised inner join facultydetails on online_course_organised.Fac_ID = facultydetails.Fac_ID");
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
    'organized_by' => 'Organised_by',
    'purpose_of_activity' => 'Purpose of course',   
    'permission_path' => 'permission_path',	
    'certificate_path' => 'certificate_path',
    'report_path' => 'report_path',
    'currentTimestamp' => 'Updated at'
);


$no_of_cols = count($heading);
$rowNumberH = 1;
$colH = 'A';
$columns = array('0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J', '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P', '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T', '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', '24' => 'Y', '25' => 'Z');

$q = $conn->query("SELECT F_NAME,activity_name,Date_from,Date_to, organized_by,
 purpose_of_activity,permission_path, certificate_path,report_path, currentTimestamp FROM co_curricular inner join facultydetails on co_curricular.Fac_ID = facultydetails.Fac_ID");

 
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
    'purpose_of_activity' => 'Purpose of course',   
    'permission_path' => 'permission_path',	
    'certificate_path' => 'certificate_path',
    'report_path' => 'report_path',
    'currentTimestamp' => 'Updated at'
);


$no_of_cols = count($heading);
$rowNumberH = 1;
$colH = 'A';
$columns = array('0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J', '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P', '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T', '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', '24' => 'Y', '25' => 'Z');

$q = $conn->query("SELECT F_NAME,activity_name,Date_from,Date_to, organized_by,
 purpose_of_activity,permission_path, certificate_path,report_path, currentTimestamp FROM ex_curricular inner join facultydetails on ex_curricular.Fac_ID = facultydetails.Fac_ID");

 
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
    'organized_by' => 'Organised_by',
    'purpose_of_activity' => 'Purpose of course',   
    'permission_path' => 'permission_path',	
    'certificate_path' => 'certificate_path',
    'report_path' => 'report_path',
    'currentTimestamp' => 'Updated at'
);


$no_of_cols = count($heading);
$rowNumberH = 1;
$colH = 'A';
$columns = array('0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J', '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P', '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T', '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', '24' => 'Y', '25' => 'Z');

$q = $conn->query("SELECT F_NAME,activity_name,Date_from,Date_to, organized_by,
 purpose_of_activity,permission_path, certificate_path,report_path, currentTimestamp FROM any_other_activity inner join facultydetails on any_other_activity.Fac_ID = facultydetails.Fac_ID");


 
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
$objWriter->save('php://output');
?>