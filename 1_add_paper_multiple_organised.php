<?php
ob_start();
session_start();
include_once('head.php');
include_once('header.php');
//check if user has logged in or not

if (!isset($_SESSION['loggedInUser'])) {
    //send the iser to login page
    header("location:index.php");
}
$_SESSION['currentTab'] = "sttp1";

//connect ot database
include_once("includes/connection.php");

//include custom functions files 
include_once("includes/functions.php");
include_once("includes/scripting.php");
include_once("includes/config.php");

if ($_SESSION['type'] != 'faculty') {
    header("location:index.php");
}

//setting error variables
$nameError = "";
$emailError = "";
$Act_title = $startDate = $endDate = $Act_type =  $location = $organized_by = $resource = $coordinated = $role_of_faculty = $time_activities = $status_act = $sponsors = $sponsor_details = $approval_details = $brochurepath = $permissionpath = $certificate1_path = $reportpath = "";
$flag = 1;
$success = 0;
$no_of_participants = 0;
$equi_duration = 0;
$no_of_days = $no_of_hours = $no_of_weeks = $month = $year = 0;
$s = 1;
$error1 = $error2 = $error3 = $error4 = "";
$fid = $_SESSION['Fac_ID'];
$queryrun = "SELECT * FROM facultydetails where Fac_ID=$fid";
$resultrun = mysqli_query($conn, $queryrun);
while ($row = mysqli_fetch_assoc($resultrun)) {
    $_SESSION['Dept'] = $row['Dept'];
    $_SESSION['type'] = $row['type'];
    $_SESSION['F_NAME'] = $row['F_NAME'];
}
// $act_name = $_SESSION['act_name'];
$faculty_name = $_SESSION['loggedInUser'];

//check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['add'])) {

        //the form was submitted

        $Act_title_array = $_POST['Act_title'];
        $Act_type_array = $_POST['Act_type'];
        $organized_by_array = $_POST['Organized_by'];
        $startDate_array = $_POST['Date_from'];
        $endDate_array = $_POST['Date_to'];
        $location_array = $_POST['Location'];
        $resouce_array = $_POST['Resource'];
        // $coordinated_array = $_POST['coordinated'];
        // $role_of_faculty_array = $_POST['role_of_faculty'];
        // $time_activities_array = $_POST['time_activities'];
        $no_of_participants_array = $_POST['No_Of_Participants'];
        // $equi_duration_array = $_POST['Equivalent_Duration'];
        $status_act_array = $_POST['Status_Of_Activity'];
        // $duration_array = $_POST['duration'];
        // $sponsors_array = $_POST['sponsors'];
        // $sponsor_details_array = $_POST['sponsor_details'];
        // $approval_details_array = $_POST['approval_details'];
        $category_array = $_POST['Category'];
        $resourOrg_array = $_POST['ResourceOrg'];
        $targetaud_array = $_POST['TargetAud'];

        //check for any blank input which are required

        for ($i = 0; $i < 1; $i++) {
            $Act_title = mysqli_real_escape_string($conn, $Act_title_array[$i]);
            $Act_type = mysqli_real_escape_string($conn, $Act_type_array[$i]);
            $organized_by = mysqli_real_escape_string($conn, $organized_by_array[$i]);
            $resource = mysqli_real_escape_string($conn, $resouce_array[$i]);
            $startDate = mysqli_real_escape_string($conn, $startDate_array[$i]);
            $endDate = mysqli_real_escape_string($conn, $endDate_array[$i]);
            $location = mysqli_real_escape_string($conn, $location_array[$i]);
            // $coordinated = mysqli_real_escape_string($conn, $coordinated_array[$i]);
            // $role_of_faculty = mysqli_real_escape_string($conn, $role_of_faculty_array[$i]);
            // $time_activities = mysqli_real_escape_string($conn, $time_activities_array[$i]);
            $no_of_participants = mysqli_real_escape_string($conn, $no_of_participants_array[$i]);
            // $equi_duration = mysqli_real_escape_string($conn, $equi_duration_array[$i]);
            $status_act = mysqli_real_escape_string($conn, $status_act_array[$i]);
            // $sponsors = mysqli_real_escape_string($conn, $sponsors_array[$i]);
            // $sponsor_details = mysqli_real_escape_string($conn, $sponsor_details_array[$i]);
            // $approval_details = mysqli_real_escape_string($conn, $approval_details_array[$i]);
            $resourOrg = mysqli_real_escape_string($conn, $resourOrg_array[$i]);
            $targetaud = mysqli_real_escape_string($conn, $targetaud_array[$i]);
            $category = mysqli_real_escape_string($conn, $category_array[$i]);
            // $duration = mysqli_real_escape_string($conn, $duration_array[$i]);
            // echo "\n\n\n\n" . $startDate . "\n" . $endDate;
            $time = time();
            $start = new DateTime(date($startDate, $time));
            $end = new DateTime(date($endDate, $time));
            $days = date_diff($start, $end);
            $no_of_days = $days->format('%d') + 1;
            $month = $start->format('n');
            $year = $start->format('Y');
            $no_of_weeks = $no_of_days / 7;
            $no_of_hours = $no_of_days * 8;

            $Act_title = validateFormData($Act_title);
            $Act_title = "'" . $Act_title . "'";

            $organized_by = validateFormData($organized_by);
            $organized_by = "'" . $organized_by . "'";

            $resource = validateFormData($resource);
            $resource = "'" . $resource . "'";

            $Act_type = validateFormData($Act_type);
            $Act_type = "'" . $Act_type . "'";

            if ($startDate>$endDate) {
                $nameError = $nameError . "Start date cannot be greater than end date<br>";
                $error = "Start date cannot be greater than end date";
			    $s = 0;
                $flag = 0;
            }

            $startDate = validateFormData($startDate);
            $startDate = "'" . $startDate . "'";

            $endDate = validateFormData($endDate);
            $endDate = "'" . $endDate . "'";

            $location = validateFormData($location);
            $location = "'" . $location . "'";

            // $role_of_faculty = validateFormData($role_of_faculty);
            // $role_of_faculty = "'" . $role_of_faculty . "'";

            // $time_activities = validateFormData($time_activities);
            // $time_activities = "'" . $time_activities . "'";

            $no_of_participants = validateFormData($no_of_participants);
            $no_of_participants = "'" . $no_of_participants . "'";

            $equi_duration = validateFormData($equi_duration);
            $equi_duration = "'" . $equi_duration . "'";

            $status_act = validateFormData($status_act);
            $status_act = "'" . $status_act . "'";

            // $sponsors = validateFormData($sponsors);
            // $sponsors = "'" . $sponsors . "'";

            // if ($sponsor_details != "") {
            //     $sponsor_details = validateFormData($sponsor_details);
            //     $sponsor_details = "$sponsor_details";
            // } else {
            //     $sponsor_details = 'NA';
            // }

            // $flag = 1;
            // if ($approval_details != "") {
            //     $approval_details = validateFormData($approval_details);
            //     $approval_details = "$approval_details";
            // } else {
            //     $approval_details = 'NA';
            // }

            $replace_str = array('"', "'", '', '');
            if (isset($_POST['Resource']))
                $resource = str_replace($replace_str, "", $resource);
            else
                $resource  = '';

            // $replace_str = array('"', "'", '', '');
            // if (isset($_POST['role_of_faculty']))
            //     $role_of_faculty = str_replace($replace_str, "", $role_of_faculty);
            // else
            //     $role_of_faculty  = '';


            $replace_str = array('"', "'", '', '');
            if (isset($_POST['Organized_by'])) {
                $organized_by = str_replace($replace_str, "", $organized_by);
                $organized_by = str_replace("rn", '', $organized_by);
            } else
                $organized_by  = '';

            // echo $organized_by;

            if (!isset($_POST['co_name']) && $s != 0) {
                $coauthorname = "NA";
            }
            $coauthorname = "";
            $coautharray = array();
            if (isset($_POST["co_name"]) && $s != 0) {
                for ($count2 = 0; $count2 < count($_POST["co_name"]); $count2++) {
                    $co_name = $_POST["co_name"][$count2];
                    array_push($coautharray, $co_name);
                }
                $coauthorname = implode(',', $coautharray);
            }

            // if ($duration == 'hours') {
            //     $no_of_hours = $equi_duration;
            // } else if ($duration == 'days') {
            //     $no_of_days = $equi_duration;
            // } else if ($duration == 'weeks') {
            //     $no_of_weeks = $equi_duration;
            //     $no_of_days = 7 * $equi_duration;
            // }

            if (isset($_POST['applicable'])) {
                if ($_POST['applicable'] == 2) {
                    $permissionpath = 'NULL';
                    $success = 1;
                } else if ($_POST['applicable'] == 3) {
                    $permissionpath = 'not_applicable';
                    $success = 1;
                } else if ($_POST['applicable'] == 1) {
                    if (isset($_FILES['permission']) && $_FILES['permission']['name'] != NULL && $_FILES['permission']['name'] != "") {
                        $errors = array();
                        $fileName = $_FILES['permission']['name'];
                        $fileSize = $_FILES['permission']['size'];
                        $fileTmp = $_FILES['permission']['tmp_name'];
                        $fileType = $_FILES['permission']['type'];
                        $temp = explode('.', $fileName);
                        $fileExt = strtolower(end($temp));
                        date_default_timezone_set('Asia/Kolkata');
                        $targetName = $datapath . "permissions/" . $_SESSION['F_NAME'] . "_permissions_" . date("d-m-Y H-i-s", time()) . "." . $fileExt;
                        if (empty($errors) == true) {
                            if (file_exists($targetName)) {
                                unlink($targetName);
                            }
                            $moved = move_uploaded_file($fileTmp, "$targetName");
                            if ($moved == true) {
                                $permissionpath = $targetName;
                                $success = 1;
                            } else {
                                // echo "<h1> $targetName </h1>";
                            }
                        } else {
                            print_r($errors);
                        }
                    } else {
                        $s = 0;
                        $error1 = "No file selected";
                    }
                }
            }
            if (isset($_POST['applicable2'])) {
                if ($_POST['applicable2'] == 2) {
                    $reportpath = 'NULL';
                    $success = 1;
                } else if ($_POST['applicable2'] == 3) {
                    $reportpath = 'not_applicable';
                    $success = 1;
                } else if ($_POST['applicable2'] == 1) {
                    if (isset($_FILES['report']) && $_FILES['report']['name'] != NULL && $_FILES['report']['name'] != "") {
                        $errors = array();
                        $fileName = $_FILES['report']['name'];
                        $fileSize = $_FILES['report']['size'];
                        $fileTmp = $_FILES['report']['tmp_name'];
                        $fileType = $_FILES['report']['type'];
                        $temp = explode('.', $fileName);
                        $fileExt = strtolower(end($temp));
                        date_default_timezone_set('Asia/Kolkata');
                        $targetName = $datapath . "reports/" . $_SESSION['F_NAME'] . "_reports_" . date("d-m-Y H-i-s", time()) . "." . $fileExt;
                        if (empty($errors) == true) {
                            if (file_exists($targetName)) {
                                unlink($targetName);
                            }
                            $moved = move_uploaded_file($fileTmp, "$targetName");
                            if ($moved == true) {
                                $reportpath = $targetName;
                                $success = 1;
                            } else {
                                // echo "<h1> $targetName </h1>";
                            }
                        } else {
                            print_r($errors);
                        }
                    } else {
                        $s = 0;
                        $error3 = "No file selected";
                    }
                }
            }
            if (isset($_POST['applicable1'])) {
                if ($_POST['applicable1'] == 2) {
                    $certificate1_path = 'NULL';
                    $success = 1;
                } else if ($_POST['applicable1'] == 3) {
                    $certificate1_path = 'not_applicable';
                    $success = 1;
                } else if ($_POST['applicable1'] == 1) {
                    if (isset($_FILES['certificate']) && $_FILES['certificate']['name'] != NULL && $_FILES['certificate']['name'] != "") {
                        $errors = array();
                        $fileName = $_FILES['certificate']['name'];
                        $fileSize = $_FILES['certificate']['size'];
                        $fileTmp = $_FILES['certificate']['tmp_name'];
                        $fileType = $_FILES['certificate']['type'];
                        $temp = explode('.', $fileName);
                        $fileExt = strtolower(end($temp));
                        date_default_timezone_set('Asia/Kolkata');
                        $targetName = $datapath . "certificates/" . $_SESSION['F_NAME'] . "_certificates_" . date("d-m-Y H-i-s", time()) . "." . $fileExt;
                        if (empty($errors) == true) {
                            if (file_exists($targetName)) {
                                unlink($targetName);
                            }
                            $moved = move_uploaded_file($fileTmp, "$targetName");
                            if ($moved == true) {
                                $certificate1_path = $targetName;
                                $success = 1;
                            } else {
                                // echo "<h1> $targetName </h1>";
                            }
                        } else {
                            print_r($errors);
                        }
                    } else {
                        $s = 0;
                        $error2 = "No file selected";
                    }
                }
            }

            if (isset($_POST['applicable3'])) {
                if ($_POST['applicable3'] == 2) {
                    $brochurepath = 'NULL';
                    $success = 1;
                } else if ($_POST['applicable3'] == 3) {
                    $brochurepathh = 'not_applicable';
                    $success = 1;
                } else if ($_POST['applicable3'] == 1) {
                    if (isset($_FILES['brochure']) && $_FILES['brochure']['name'] != NULL && $_FILES['brochure']['name'] != "") {
                        $errors = array();
                        $fileName = $_FILES['brochure']['name'];
                        $fileSize = $_FILES['brochure']['size'];
                        $fileTmp = $_FILES['brochure']['tmp_name'];
                        $fileType = $_FILES['brochure']['type'];
                        $temp = explode('.', $fileName);
                        $fileExt = strtolower(end($temp));
                        date_default_timezone_set('Asia/Kolkata');
                        $targetName = $datapath . "brochures/" . $_SESSION['F_NAME'] . "_brochures_" . date("d-m-Y H-i-s", time()) . "." . $fileExt;
                        if (empty($errors) == true) {
                            if (file_exists($targetName)) {
                                unlink($targetName);
                            }
                            $moved = move_uploaded_file($fileTmp, "$targetName");
                            if ($moved == true) {
                                $brochurepath = $targetName;
                                $success = 1;
                            } else {
                                // echo "<h1> $targetName </h1>";
                            }
                        } else {
                            print_r($errors);
                        }
                    } else {
                        $s = 0;
                        $error4 = "No file selected";
                    }
                }
            }
            //checking if there was an error or not
            $query = "SELECT Fac_ID from facultydetails where Email='" . $_SESSION['loggedInEmail'] . "';";
            $result = mysqli_query($conn, $query);
            if ($result) {
                $row = mysqli_fetch_assoc($result);
                $author = $row['Fac_ID'];
            }
            if ($flag != 0 && $s != 0) {
                $sql = "INSERT INTO organised(Fac_ID,Coordinated_by,Date_from,Date_to,month,year,noofdays,noofweeks,Equivalent_Duration,Location,Act_type,Status_Of_Activity,Act_title,Organized_by,Resource,ResourceOrg,TargetAud,No_Of_Participants,Category,Brochure_path,Report_Path,Certificate_path,Permission_path) VALUES ('$author','$coauthorname',$startDate,$endDate,$month,$year,$no_of_days,$no_of_weeks,$no_of_hours,$location,$Act_type,$status_act,$Act_title,'$organized_by','$resource','$resourOrg','$targetaud',$no_of_participants,'$category','" . $brochurepath . "','" . $reportpath . "','" . $certificate1_path . "','" . $permissionpath . "')";
                echo $sql;
                echo $no_of_hours;

                if ($conn->query($sql) === TRUE) {
                    $success = 1;
                    header("location:2_dashboard_organised.php?alert=success");
                } else if($s!=0){
                    header("location:2_dashboard_organised.php?alert=error");
                }
                else if($s==0){
                    echo "<script>alert('$error')</script>";
                }
            }
        } //end of for

    }
}


//close the connection
mysqli_close($conn);

function fill_unit_select_box($connect)
{
    $output = '';
    $query = "SELECT * FROM facultydetails WHERE type='faculty' ORDER BY F_NAME ASC";
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach ($result as $row) {
        $output .= '<option value="' . $row["F_NAME"] . '">' . $row["F_NAME"] . '</option>';
    }
    return $output;
}
?>






<?php
if ($_SESSION['type'] == 'hod') {
    include_once('sidebar_hod.php');
} elseif ($_SESSION['type'] == 'cod' || $_SESSION['type'] == 'com') {
    include_once('sidebar_cod.php');
} else {
    include_once('sidebar.php');
}

?>

<style>
    .error {
        color: red;
        border: 1px solid red;
        background-color: #ebcbd2;
        border-radius: 10px;
        margin: 5px;
        padding: 0px;
        font-family: Arial, Helvetica, sans-serif;
        width: 510px;
    }

    .colour {
        color: #ff0000;
    }

    .demo {
        width: 120px;
    }
    #form {
	width: 100% !important;
	}
</style>


<div class="content-wrapper">

    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-8" id="form">
                <!-- general form elements -->
                <br /><br /><br />

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="icon">
                            <i style="font-size:20px" class="fa fa-edit"></i>
                            <h3 class="box-title"><b>STTP/WS/FDP/QIP/TR/S/IN Organised Form</b></h3>
                            <br>
                        </div>
                    </div><!-- /.box-header -->
                    <div style="text-align:right">
                        <!--	<a href="menu.php?menu=3 "> <u>Back to STTP/Workshop/FDP Attended/Organised Menu</u></a> -->
                    </div>
                    <br>
                    <!-- form start -->

                    <?php

                    for ($k = 1; $k <= 1; $k++) {

                    ?>
                        <form role="form" method="POST" class="row" action="" style="margin:10px;align:center" enctype="multipart/form-data">

                            <?php
                            if ($flag == 0) {
                                echo '<div class="error">' . $nameError . '</div>';
                            }

                            $replace_str = array('"', "'", '', '');
                            if (isset($_POST['resource']))
                                $resource = str_replace($replace_str, "", $resource);
                            else
                                $resource  = '';

                            $replace_str = array('"', "'", '', '');
                            if (isset($_POST['role_of_faculty']))
                                $role_of_faculty = str_replace($replace_str, "", $role_of_faculty);
                            else
                                $role_of_faculty  = '';


                            $replace_str = array('"', "'", '', '');
                            if (isset($_POST['organized_by'])) {
                                $organized_by = str_replace($replace_str, "", $organized_by);
                                $organized_by = str_replace("rn", '', $organized_by);
                            } else
                                $organized_by  = '';
                            ?>

                            <div class="form-group col-md-6">
                                <label for="department_name">Department Name</label>
                                <input required type="text" class="form-control input-lg" id="dept-name" name="deptName" value="<?php echo strtoupper($_SESSION['Dept']); ?>" readonly>
                            </div>
                        
                            <div class="form-group col-md-6">
                                <label for="faculty-name">Faculty Name</label>
                                <input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $faculty_name; ?>" readonly>
                            </div>

                            <!-- <div class="form-group col-md-6">
                         <label for="paper-title">Title *</label> -->
                            <!--   <input required type="text" class="form-control input-lg" id="paper-title" name="Act_title[]">-->
                            <!-- <input  type="text" class="form-control input-lg"  name="Act_title[]" id="paper-title" <?php if ($Act_title != '') echo "value = $Act_title"; ?>  required> -->
                            <!-- </div> -->

                            <div class="form-group col-md-6">
                                <label for="c_name">Co-Organiser</label>
                                <div class="table-repsonsive">
                                    <span id="error"></span>
                                    <table class="table table-bordered" id="c_name">
                                        <tr>
                                            <th>Click to select </th>
                                            <th><button type="button" name="add" class="btn btn-success btn-sm add"><span class="glyphicon glyphicon-plus"></span></button></th>
                                        </tr>
                                    </table>
                                </div>
                            </div><br>

                            
                            <div class="form-group col-md-6">
                                <label for="venue">Venue</label>
                                <span class="colour" style="color : red"><b> *</b></span>
                                <input <?php if (isset($_POST['Location'])) echo "value = $location"; ?> required type="text" class="form-control input-lg" id="location" name="Location[]">
                            </div>
                            
                            <div class="form-group col-md-6">
                                <label for="paper-type">Activity Type</label>
                                <span class="colour" style="color : red"><b> *</b></span>
                                <select id="paper-type" required name="Act_type[]" class="form-control input-lg">
                                    <option value="" disabled selected>Select your option</option>
                                    <option name="STTP" value="STTP">Short Term Training Programme(STTP)</option>
                                    <option name="FDP" value="FDP">Faculty Development Programme(FDP)</option>
                                    <option name="WS" value="WS">Workshop(WS)</option>
                                    <option name="TR" value="TR">Training(TR)</option>
                                    <option name="S" value="S">Seminar(S)</option>
                                    <option name="IN" value="IN">Internship(IN)</option>
                                    <option name="EL" value="EL">Expert Lecture(EL)</option>
                                    <option name="Webinar" value="Webinar">Webinar</option>
                                    <option name="REFRESHER" value="Others">Others</option>
                                </select>
                            </div>
                            
                            <div class="form-group col-md-6">
                                <label for="start-date">Start Date</label>
                                <span class="colour" style="color : red"><b> *</b></span>
                                <input <?php if (isset($_POST['Date_from'])) echo "value = $startDate"; ?> required type="date" class="form-control input-lg" id="start-date" name="Date_from[]" placeholder="03:10:10">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="end-date">End Date</label>
                                <span class="colour" style="color : red"><b> *</b></span>
                                <input <?php if (isset($_POST['Date_to'])) echo "value = $endDate"; ?> required type="date" class="form-control input-lg" id="end-date" name="Date_to[]" placeholder="03:10:10">
                            </div>
                            

                             <!-- <div class="form-group col-md-6">
                                <label for="organised_under">Name of Co-Organiser</label>
                                <span class="colour" style="color : red"><b> *</b></span>
                                <select required name="status_activities[]" id="status_activities" class="form-control input-lg">
                                    <option value="" disabled selected>Select your option:</option>
                                    <option name="department" value="department">Department</option>
                                    <option name="student_council" value="student_council">Student Council</option>
                                    <option name="society" value="society">Society</option>
                                    <option name="student_chapter" value="student_chapter">Student Chapter</option>
                                    <option name="mega_project" value="mega_project">Mega Project</option>
                                    <option name="college" value="college">College</option>
                                </select>
                            </div> -->

                            <!-- <div class="form-group col-md-6">
                                <label for="Month">Month</label>
                                <input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $faculty_name; ?>" readonly>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="Year">Year</label>
                                <input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $faculty_name; ?>" readonly>
                            </div> -->

                            <!-- <div class="form-group col-md-6">
                                <label for="duration">Days/Weeks/Hours</label>
                                <span class="colour" style="color : red"><b> *</b></span>
                                <select required name="duration[]" id="duration" class="form-control input-lg">
                                    <option value="" disabled selected>Select your option:</option>
                                    <option name="college" value="hours">Hours</option>
                                    <option name="national" value="days">Days</option>
                                    <option name="international" value="weeks">Week</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="no_of_participants">Equivalent Duration</label>
                                <span class="colour" style="color : red"><b> *</b></span>
                                <input type="number" name="no_of_hours[]" id="no_of_hours" class="form-control input-lg" min="1" required>
                            </div> -->

                            <!-- <div class="form-group col-md-6">
                        <label for="days/weeks/hours">Number of Days/Weeks/Hours *</label>
                        <select required name="status_activities[]" id="status_activities" class="form-control input-lg" >
                            <option value="" disabled selected>Select your option:</option>
                            <option name="hours" value="hours">Hours</option>
                            <option name="days" value="days">Days</option>
                            <option name="weeks" value="weeks">Weeks</option>
                        </select>
                    </div> -->

                           

                            <div class="form-group col-md-6">
                                <label for="level">Level: </label>
                                <span class="colour" style="color : red"><b> *</b></span>
                                <select required name="Status_Of_Activity[]" id="level" class="form-control input-lg">
                                    <option value="" disabled selected>Select your option:</option>
                                    <option name="college" value="college">College</option>
                                    <option name="national" value="national">National</option>
                                    <option name="international" value="international">International</option>
                                    <option name="Others" value="Others">Others</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="resource">Description of the Activity </label>
                                <span class="colour" style="color : red"><b> *</b></span>
                                <input type="text" required class="form-control input-lg" id="Act_title" name="Act_title[]">
                            </div>

                            <br>
                            <div class="form-group col-md-6">
                                <label for="Organized_by">Organised Under </label>
                                <span class="colour" style="color : red"><b> *</b></span>
                                <select required name="Organized_by[]" id="Organized_by" class="form-control input-lg">
                                    <option value="" disabled selected>Select your option:</option>
                                    <option name="department" value="department">Department</option>
                                    <option name="student_council" value="student_council">Student Council</option>
                                    <option name="society" value="society">Society</option>
                                    <option name="student_chapter" value="student_chapter">Student Chapter</option>
                                    <option name="mega_project" value="mega_project">Mega Project</option>
                                    <option name="college" value="college">College</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="resource">Name of Resource person </label>
                                <span class="colour" style="color : red"><b> *</b></span>
                                <input type="text" required class="form-control input-lg" id="Resource" name="Resource[]" <?php if (isset($_POST['Resource'])) echo "value = $resource"; ?>>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="resource">Name of Resource Organization </label>
                                <span class="colour" style="color : red"><b> *</b></span>
                                <input type="text" required class="form-control input-lg" id="ResourceOrg" name="ResourceOrg[]" <?php if (isset($_POST['ResourceOrg'])) echo "value = $resourOrg"; ?>>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="target_audience">Target Audience </label>
                                <span class="colour" style="color : red"><b> *</b></span>
                                <input required type="text" class="form-control input-lg" id="target" name="TargetAud[]">
                            </div>


                            <div class="form-group col-md-6">
                                <label for="no_of_participants">Number of participants: </label>
                                <span class="colour" style="color : red"><b> *</b></span>
                                <input <?php if (isset($_POST['No_Of_Participants'])) echo "value = $no_of_participants"; ?> type="number" name="No_Of_Participants[]" id="no_of_participants" class="form-control input-lg" min="1" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="category_of_program_organised">Category of Program Organised </label>
                                <span class="colour" style="color : red"><b> *</b></span>
                                <select required name="Category[]" id="Category" class="form-control input-lg">
                                    <option value="" disabled selected>Select your option:</option>
                                    <option name="technical" value="technical">Technical</option>
                                    <option name="research" value="research">Research</option>
                                    <option name="project_innovation_based" value="project_innovation_based">Project and Innovation Based</option>
                                    <option name="entrepreneurship" value="entrepreneurship">Entrepreneurship</option>
                                    <option name="life_skills" value="life_skills">Life Skills</option>
                                    <option name="yoga_and_stress_management" value="yoga_and_stress_management">Yoga and Stress Management</option>
                                    <option name="other" value="other">Other</option>
                                </select>
                            </div>


                            <!-- <div class="form-group col-md-6">
                        <label for="no_of_hours">Equivalent duration *</label>
                        <input   class="form-control input-lg" type="text" name="no_of_hours[]" id="no_of_hours" placeholder="" value="">
                    </div> -->


                            <!-- <div class="form-group col-md-6">
                         <label for="organized_by">Organized by :(With Brief Address) *</label>
                         <input type="text" required class="form-control input-lg" id="organized_by" name="organized_by[]" id="organized_by"  <?php if (isset($_POST['organized_by'])) echo "value = $organized_by "; ?>>
                     </div>

					 <div class="form-group col-md-6">
                         <label for="coordinated">Co-ordinated by *</label>
                         <input <?php if (isset($_POST['coordinated'])) echo "value = $coordinated"; ?> required type="text" class="form-control input-lg" id="coordinated" name="coordinated[]">
                     </div> -->

                            <!-- <div class="form-group col-md-6">
                        <label for="role_of_faculty">Role of Faculty: *</label>
                        <input type="text" id="role_of_faculty" class="form-control input-lg" name="role_of_faculty[]" required  <?php if (isset($_POST['role_of_faculty'])) echo "value = $role_of_faculty"; ?>>
						 </div> -->

                            <!-- <div class="form-group col-md-6">
                        <label id="time_activities">Full-Time/Part-Time: *</label>
                        <select required name="time_activities[]" id="time_activites" class="form-control input-lg">
                            <option  value="" disabled selected>Select your option:</option>
                            <option <?php if (isset($_POST['time_activities'])) if ($time_activities == "full-time") echo "selected = 'selected'" ?> name="full-time" value="full-time">Full-Time</option>
                            <option <?php if (isset($_POST['time_activities'])) if ($time_activities == "part-time") echo "selected = 'selected'" ?> name="part-time" value="part-time">Part-Time</option>
                        </select></div> -->

                            <!-- <div class="form-group col-md-6">
                        <label for="sponsors">Sponsored/Not-sponsored:</label>
                        <span class="colour"><b> *</b></span>
                        <select required class="form-control input-lg sponsors" id="sponsors" name="sponsors[]">
                             <option <?php if (isset($_POST['sponsors'])) if ($sponsors == "not-sponsored") echo "selected = 'selected'" ?> value ="not-sponsored">Not sponsored</option>
                             <option <?php if (isset($_POST['sponsors'])) if ($sponsors == "sponsored") echo "selected = 'selected'" ?> value ="sponsored">Sponsored</option>
                        </select>
                        </div>

                        <div id="sponser" class="form-group col-md-6" style="display:none">
                            <label for="sponsor_details">Sponsor Details: </label>
                                <br>
                            <input type="text" name="sponsor_details[]" id="sponsor_details" class="form-control input-lg">		
                        </div>
                        

                        <div id="app" class="form-group col-md-6" style="display:none">
                            <label for="approval_details">Approval Details: </label>
                            <br>
                            <input type="text" name="approval_details[]" id="approval_details" class="form-control input-lg">
                        </div> -->

                            <div class="form-group col-md-6 col-md-offset-1"></div>
                            <br>
                            <div class="form-group col-md-12">
                                &nbsp;<label for="course">Upload Permission Letter: Applicable?<br></label>
                                <span class="colour"><b> *</b></span>
                                <span class="error" style="border : none;"> <?php echo $error1 ?> </span>
                                <br> &nbsp;<input required type='radio' name='applicable' class='non-vac' value='1' <?php if (isset($_POST['applicable'])) echo ($_POST['applicable'] == "1") ? 'checked' : '' ?>> Yes <br>
                                &nbsp;<input type='radio' name='applicable' class='vac' value='2' <?php if (isset($_POST['applicable'])) echo ($_POST['applicable'] == "2") ? 'checked' : '' ?>> Applicable, but not yet available <br>
                                &nbsp;<input type='radio' name='applicable' class='vac' value='3' <?php if (isset($_POST['applicable'])) echo ($_POST['applicable'] == "3") ? 'checked' : '' ?>> No <br>
                            </div>
                            <br>
                            <div class='second-reveal' <?php if (isset($_POST['applicable'])) echo ($_POST['applicable'] == "1") ? 'style = "display : block" ' : '' ?>>
                                <div>

                                    <label for="card-image">Permission Letter</label><span class="colour"><b> *</b></span>
                                    <input type="file" class="form-control input-lg" id="card-image" name="permission">
                                </div>
                            </div>


                            <br>

                            <div class="form-group col-md-12">

                                &nbsp;<label for="course">Upload Certificate: Applicable?<br></label>
                                <span class="colour"><b> *</b></span>
                                <span class="error" style="border : none;"> <?php echo $error2 ?> </span>
                                <br> &nbsp;<input required type='radio' name='applicable1' class='non-vac1' value='1' <?php if (isset($_POST['applicable1'])) echo ($_POST['applicable1'] == "1") ? 'checked' : '' ?>> Yes <br>
                                &nbsp;<input type='radio' name='applicable1' class='vac1' value='2' <?php if (isset($_POST['applicable1'])) echo ($_POST['applicable1'] == "2") ? 'checked' : '' ?>> Applicable, but not yet available <br>

                                &nbsp;<input type='radio' name='applicable1' class='vac1' value='3' <?php if (isset($_POST['applicable1'])) echo ($_POST['applicable1'] == "3") ? 'checked' : '' ?>> No <br>
                            </div>
                            <br>
                            <div class='second-reveal1' <?php if (isset($_POST['applicable1'])) echo ($_POST['applicable1'] == "1") ? 'style = "display : block" ' : '' ?>>
                                <div>
                                    <label for="card-image">Certificate </label><span class="colour"><b> *</b></span>
                                    <input type="file" class="form-control input-lg" id="card-image" name="certificate">
                                </div>
                            </div>

                            <br>

                            <div class="form-group col-md-12">

                                &nbsp;<label for="course">Upload Report: Applicable?<br></label>
                                <span class="colour"><b> *</b></span>
                                <span class="error" style="border : none;"> <?php echo $error3 ?> </span>
                                <br> &nbsp;<input required type='radio' name='applicable2' class='non-vac2' value='1' <?php if (isset($_POST['applicable2'])) echo ($_POST['applicable2'] == "1") ? 'checked' : '' ?>> Yes <br>
                                &nbsp;<input type='radio' name='applicable2' class='vac2' value='2' <?php if (isset($_POST['applicable2'])) echo ($_POST['applicable2'] == "2") ? 'checked' : '' ?>> Applicable, but not yet available <br>

                                &nbsp;<input type='radio' name='applicable2' class='vac2' value='3' <?php if (isset($_POST['applicable2'])) echo ($_POST['applicable2'] == "3") ? 'checked' : '' ?>> No <br>
                            </div>
                            <br>
                            <div class='second-reveal2' <?php if (isset($_POST['applicable2'])) echo ($_POST['applicable2'] == "1") ? 'style = "display : block" ' : '' ?>>
                                <div>

                                    <label for="card-image">Report </label><span class="colour"><b> *</b></span>
                                    <input type="file" class="form-control input-lg" id="card-image" name="report">
                                </div>
                            </div>

                            <br>

                            <div class="form-group col-md-12">

                                &nbsp;<label for="course">Upload Brochure: Applicable?<br></label>
                                <span class="colour"><b> *</b></span>
                                <span class="error" style="border : none;"> <?php echo $error4 ?> </span>
                                <br> &nbsp;<input required type='radio' name='applicable3' class='non-vac4' value='1' <?php if (isset($_POST['applicable3'])) echo ($_POST['applicable3'] == "1") ? 'checked' : '' ?>> Yes <br>
                                &nbsp;<input type='radio' name='applicable3' class='vac4' value='2' <?php if (isset($_POST['applicable3'])) echo ($_POST['applicable3'] == "2") ? 'checked' : '' ?>> Applicable, but not yet available <br>

                                &nbsp;<input type='radio' name='applicable3' class='vac4' value='3' <?php if (isset($_POST['applicable3'])) echo ($_POST['applicable3'] == "3") ? 'checked' : '' ?>> No <br>
                            </div>
                            <br>
                            <div class='second-reveal4' <?php if (isset($_POST['applicable3'])) echo ($_POST['applicable3'] == "1") ? 'style = "display : block" ' : '' ?>>
                                <div>

                                    <label for="card-image">Brochure </label><span class="colour"><b> *</b></span>
                                    <input type="file" class="form-control input-lg" id="card-image" name="brochure">
                                </div>
                            </div>
                </div>
                <!-- <br>
                    <div>
                    <form action ="?" method = "POST" enctype="multipart/form-data">
                     <p><input type="file" name = "file" multiple value= "Upload Certificate"/></p> 
                    <form>
                    </div>
                    <br> -->


                <script>
                    $('.sponsors').each(function() {
                        $('.sponsors').on('change', myfunction);
                    });

                    function myfunction() {
                        var x = this.value;

                        if (x == 'sponsored') {
                            //document.getElementById("demo").innerHTML = "You selected:" +x;
                            $("#sponser").show();
                            $("#app").show();
                        } else {
                            $("#sponser").hide();
                            $("#app").hide();
                        }
                    }
                </script>
            <?php
                    }
            ?>
            <br />
            <div class="form-group col-md-12">
                <button name="add" type="submit" class="btn btn-success pull-right btn-lg">Submit</button>
                <a href="list_of_activities_user.php" type="button" class="btn btn-warning btn-lg">Cancel</a>
            </div>
            </form>
            </div>
        </div>
</div>
</section>


</div>
<script>
    $(document).ready(function() {

        $(document).on('click', '.add', function() {
            var html = '';
            html += '<tr>';
            html += '<td><select name="co_name[]" class="form-control item_unit" id="search"><option value="">Select Co-author</option><?php echo fill_unit_select_box($connect); ?></select></td>';
            html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm remove"><span class="glyphicon glyphicon-minus"></span></button></td></tr>';
            $('#c_name').append(html);
        });

        $(document).on('click', '.remove', function() {
            $(this).closest('tr').remove();
        });
    });
</script>



<?php include_once('footer.php'); ?>