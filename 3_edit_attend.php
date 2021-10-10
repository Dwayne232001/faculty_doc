<?php
ob_start();
session_start();
//check if user has logged in or not

if (!isset($_SESSION['loggedInUser'])) {
    //send the iser to login page
    header("location:index.php");
}
$_SESSION['currentTab'] = "sttp";

//connect ot database
include_once("includes/connection.php");

//include custom functions files 
include_once("includes/functions.php");
include_once("includes/config.php");

$Fac_ID = $_SESSION['Fac_ID'];
//date_default_timezone_set("Asia/Kolkata");
//setting error variables
$nameError = "";
$emailError = "";
$activitytitle = $startDate = $endDate = $activitytype = $location = $status_activities = $awards = "";
$no_of_hours = 0;

if (isset($_POST['rid'])) {
    $id = $_POST['rid'];
    $_SESSION['id'] = $_POST['rid'];
}
$id = $_SESSION['id'];
$query = "SELECT * from attended where A_ID = $id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$Fac_ID = $row['Fac_ID'];
$activitytitle = $row['Act_title'];
$startDate = $row['Date_from'];
$endDate = $row['Date_to'];
$activitytype = $row['Act_type'];
$organized = $row['Organized_by'];
$location = $row['Location'];
$status_act = $row['Status_Of_Activity'];
$no_of_hours = $row['Equivalent_Duration'];
$awards = $row['Awards'];
$fdc = $row['FDC_Y_N'];
$category = $row['Category'];
$last_updated = $row['LastUpdated'];
$paperpath = $row['Permission_path'];
$certipath = $row['Certificate_path'];
$reportpath = $row['Report_path'];
$_SESSION['a1'] = $activitytitle;

$query2 = "SELECT * from facultydetails where Fac_ID = $Fac_ID";
$result2 = mysqli_query($conn, $query2);
if ($result2) {
    $row = mysqli_fetch_assoc($result2);
    $F_NAME = $row['F_NAME'];
}
$_SESSION['F_NAME'] = $F_NAME;
//check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update'])) {
        //the form was submitted
        $clientName = $clientEmail = $clientPhone = $clientAddress = $clientCompany = $clientNotes = "";

        //check for any blank input which are required
        $time = time();
        if (!$_POST['activitytitle']) {
            $nameError = "Please enter a Title<br>";
        } else {
            $activitytitle = validateFormData($_POST['activitytitle']);
            $activitytitle = "'" . $activitytitle . "'";
        }

        if (!$_POST['startDate']) {
            $nameError = "Please enter a start date<br>";
        } else {
            $startDate = validateFormData($_POST['startDate']);
            $start = new DateTime(date($startDate, $time));
            $startDate = "'" . $startDate . "'";
        }
        if (!$_POST['endDate']) {
            $nameError = "Please enter an end date<br>";
        } else {
            $endDate = validateFormData($_POST['endDate']);
            $end = new DateTime(date($endDate, $time));
            $endDate = "'" . $endDate . "'";
        }
        if (!$_POST['activitytype']) {
            $nameError = "Please select activity type<br>";
        } else {
            $activitytype = validateFormData($_POST['activitytype']);
            $activitytype = "'" . $activitytype . "'";
        }
        if (!$_POST['location']) {
            $location = "Please enterlocation<br>";
        } else {
            $location = validateFormData($_POST['location']);
            $location = "'" . $location . "'";
        }
        if (!$_POST['organized']) {
            $organized = "Please enter name<br>";
        } else {
            $organized = validateFormData($_POST['organized']);
            $organized = "'" . $organized . "'";
        }
        if (!$_POST['status_act']) {
            $status_act = "Please enter status of activity<br>";
        } else {
            $status_act = validateFormData($_POST['status_act']);
            $status_act = "'" . $status_act . "'";
        }
        if (!$_POST['category']) {
            $category = "Please enter category<br>";
        } else {
            $category = validateFormData($_POST['category']);
            $category = "'" . $category . "'";
        }
        // if (!$_POST['no_of_hours']) {
        //     $no_of_hours = "Please enter no of hours<br>";
        // } else {
        //     $no_of_hours = validateFormData($_POST['no_of_hours']);
        //     $no_of_hours = "'" . $no_of_hours . "'";
        // }
        // $days = date_diff($start, $end);
        // $noofdays = $days->format('%d');
        $days = date_diff($start, $end);
        $no_of_days = $days->format('%d') + 1;
        $month = $start->format('n');
        $year = $start->format('Y');
        $no_of_weeks = $no_of_days / 7;
        $no_of_hours = $no_of_days * 8;

        // $awards = validateFormData($_POST['awards']);
        // $awards = "'" . $awards . "'";

        //following are not required so we can directly take them as it is

        $applicablefdc = $_POST["applicablefdc"];
        $fdc = $applicablefdc;

        if ($applicablefdc == 'Yes') {
            $fdc = "Yes";
        } else if ($applicablefdc == 'No') {
            $fdc = "Not applicable";
        }
        if (isset($_POST['applicable'])) {
            // console.log($_POST['applicable']);
            if ($_POST['applicable'] == 2) {
                $paperpath = 'NULL';
                $success = 1;
            } else if ($_POST['applicable'] == 3) {
                $paperpath = 'not_applicable';
                $success = 1;
            } else if ($_POST['applicable'] == 1) {
                if (isset($_FILES['paper'])) {
                    $errors = array();
                    $fileName = $_FILES['paper']['name'];
                    $fileSize = $_FILES['paper']['size'];
                    $fileTmp = $_FILES['paper']['tmp_name'];
                    $fileType = $_FILES['paper']['type'];
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
                            $paperpath = $targetName;
                            $success = 1;
                        } else {
                            //not successful
                            //header("location:error.php");
                            echo "<h1> $targetName </h1>";
                        }
                    } else {
                        print_r($errors);
                        //header("location:else.php");
                    }
                }
            }
        }

        if (isset($_POST['applicable1'])) {
            if ($_POST['applicable1'] == 2) {
                $certipath = 'NULL';
                $success = 1;
            } else if ($_POST['applicable1'] == 3) {
                $certipath = 'not_applicable';
                $success = 1;
            } else if ($_POST['applicable1'] == 1) {
                if (isset($_FILES['certificate'])) {
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
                            $certipath = $targetName;
                            $success = 1;
                        } else {
                            echo "<h1> $targetName </h1>";
                        }
                    } else {
                        print_r($errors);
                    }
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
                if (isset($_FILES['report'])) {
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
                            echo "<h1> $targetName </h1>";
                        }
                    } else {
                        print_r($errors);
                    }
                }
            }
        }
        $query = "SELECT Fac_ID from attended where Email='" . $_SESSION['loggedInEmail'] . "';";
        $result = mysqli_query($conn, $query);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $author = $row['Fac_ID'];
            $_SESSION['author'] = $author;
            $author = $_SESSION['author'];
        }
        $success1 = 0;

        $sql = "update attended set Act_title = $activitytitle,
									Act_type = $activitytype,
									Organized_by = $organized,
									Date_from = $startDate,
									Date_to = $endDate, 
									Status_Of_Activity=$status_act,
                                    category=$category,
                                    Certificate_path='" . $certipath . "',
                                    Report_path='" . $reportpath . "',
                                    noofdays = $no_of_days,
                                    noofweeks = $no_of_weeks,
                                    month = $month,
                                    year = $year,
                                    Equivalent_Duration = $no_of_hours
									WHERE A_ID = '" . $_SESSION['id'] . "'";

        echo $sql;

        if ($conn->query($sql) === TRUE) {
            // $success = 1;
        }

        if ($success == 1) {
            if ($_SESSION['type'] == 'hod') {
                header("location:2_dashboard_attend_hod.php?alert=update");
            } else {
                header("location:2_dashboard_attend.php?alert=update");
            }
        } else {
            if ($_SESSION['type'] == 'hod')
                header("location:2_dashboard_attend_hod.php?alert=error");
            else
                header("location:2_dashboard_attend.php?alert=error");
        }
    }
}
//close the connection
mysqli_close($conn);
?>





<?php include_once('head.php'); ?>
<?php include_once('header.php'); ?>
<?php
if ($_SESSION['type'] == 'hod') {
    include_once('sidebar_hod.php');
} elseif ($_SESSION['type'] == 'cod' || $_SESSION['type'] == 'com') {
    include_once('sidebar_cod.php');
} else {
    include_once('sidebar.php');
}

?>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script>
    $(document).ready(function() {
        // jQuery methodS ...
        $(".yes").click(function() {
            $(".reveal-if-active").show();
        });
        $(".no").click(function() {
            $(".reveal-if-active").hide();
        });

        $(".non-vac").click(function() {
            $(".second-reveal").show();
        });
        $(".non-vac1").click(function() {
            $(".second-reveal1").show();
        });
        $(".non-vac2").click(function() {
            $(".second-reveal2").show();
        });
        $(".vac").click(function() {
            $(".second-reveal").hide();
        });
        $(".vac1").click(function() {
            $(".second-reveal1").hide();
        });
        $(".vac2").click(function() {
            $(".second-reveal2").hide();
        });
        $(".1").click(function() {
            $(".reveal-if-active").show();
        });
        $(".0").click(function() {
            $(".reveal-if-active").hide();
        });
        $(".applicable_yes").click(function() {
            $(".reveal-if-active").show();
        });
        $(".applicable_no").click(function() {
            $(".reveal-if-active").hide();
        });

        $(".sponsored").click(function() {
            $(".second-reveal").show();
        });
        $(".not-sponsored").click(function() {
            $(".second-reveal").hide();
        });

    });
</script>

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

    .reveal-if-active,
    .second-reveal,
    .second-reveal1,
    .second-reveal2 {
        display: none;
    }

    .second-reveal,
    .second-reveal1,
    .second-reveal2,
    .reveal-if-active {
        padding-left: 20px;
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
                            <h3 class="box-title"><b>Edit STTP/WS/FDP/QIP/TR/S/IN Attended Form</b></h3>
                            <br>
                        </div>


                    </div><!-- /.box-header --><br>

                    <!-- form start -->
                    <form role="form" method="POST" class="row" action="" style="margin:10px;" enctype="multipart/form-data">
                        <input type='hidden' name='id' value='<?php echo $_SESSION['id']; ?>'>
                        <div class="form-group col-md-6">
                            <label for="department_name">Department</label>
                            <input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $_SESSION['Dept']; ?>" readonly>
                        </div>
                        <div class="form-group col-md-6">


                            <label for="faculty-name">Faculty Name</label>
                            <input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $F_NAME; ?>" readonly>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="paper-type">Select the Activity</label>
                            <span class="colour" style="color : red"><b> *</b></span>
                            <select required class="form-control input-lg" id="paper-type" name="activitytype">
                                <option <?php if ($activitytype == "STTP") echo "selected = 'selected'" ?> value="STTP">Short Term Training Programme(STTP)</option>
                                <option <?php if ($activitytype == "FDP") echo "selected = 'selected'" ?> value="FDP">Faculty Development Programme(FDP)</option>
                                <option <?php if ($activitytype == "WS") echo "selected = 'selected'" ?> value="WS">Workshop(WS)</option>
                                <option <?php if ($activitytype == "TR") echo "selected = 'selected'" ?> value="TR">Training(TR)</option>
                                <option <?php if ($activitytype == "S") echo "selected = 'selected'" ?> value="S">Seminar(S)</option>
                                <option <?php if ($activitytype == "IN") echo "selected = 'selected'" ?> value="IN">Internship(IN)</option>
                                <option <?php if ($activitytype == "EL") echo "selected = 'selected'" ?> value="EL">Expert Lecture(EL)</option>
                                <!-- <option <?php if ($activitytype == "Webinar") echo "selected = 'selected'" ?> value="Webinar">Webinar</option> -->
                                <option <?php if ($activitytype == "Others") echo "selected = 'selected'" ?> value="Others">Others(O)</option>

                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="paper-title">Name of the Activity</label>
                            <span class="colour" style="color : red"><b> *</b></span>
                            <input required type="text" class="form-control input-lg" id="paper-title" name="activitytitle" value='<?php echo $activitytitle; ?>'>
                        </div>
                        
                        <div class="form-group col-md-6">
                            <label for="start-date">Start Date</label> <?php $value = date("Y-m-d", strtotime($startDate));  ?>
                            <span class="colour" style="color : red"><b> *</b></span>
                            <input required type="date" class="form-control input-lg" id="start-date" name="startDate" placeholder="03:10:10" value="<?php echo $value; ?>">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="end-date">End Date</label> <?php $value = date("Y-m-d", strtotime($endDate)); ?>
                            <span class="colour" style="color : red"><b> *</b></span>
                            <input required type="date" class="form-control input-lg" id="end-date" name="endDate" placeholder="03:10:10" value="<?php echo $value; ?>">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="conf">Name of the Organising Institute/Industry</label>
                            <span class="colour" style="color : red"><b> *</b></span>
                            <input type="text" required class="form-control input-lg" id="conf" name="organized" rows="2" value="<?php echo $organized; ?>">
                        </div>

                        <!-- <div class="form-group col-md-6">
                            <label for="no_of_hours">Equivalent duration ( In hours ) *</label>
                            <input value='<?php echo $no_of_hours; ?>' class="form-control input-lg" type="text" name="no_of_hours" id="no_of_hours" placeholder="Hours" required>
                        </div> -->
                        <div class="form-group col-md-6">
                            <label for="category">Category of Program Organised</label>
                            <span class="colour" style="color : red"><b> *</b></span>
                            <select required name="category" id="category" class="form-control input-lg">
                                <option value="" disabled selected>Select your option:</option>
                                <option name="technical" <?php if ($category == "technical") echo "selected = 'selected'" ?> value="technical">Technical</option>
                                <option name="research" <?php if ($category == "research") echo "selected = 'selected'" ?> value="research">Research</option>
                                <option name="project_innovation_based" <?php if ($category == "project_innovation_based") echo "selected = 'selected'" ?> value="project_innovation_based">Project and Innovation Based</option>
                                <option name="entrepreneurship" <?php if ($category == "entrepreneurship") echo "selected = 'selected'" ?> value="entrepreneurship">Entrepreneurship</option>
                                <option name="life_skills" <?php if ($category == "life_skills") echo "selected = 'selected'" ?> value="life_skills">Life Skills</option>
                                <option name="yoga_and_stress_management" <?php if ($category == "yoga_and_stress_management") echo "selected = 'selected'" ?> value="yoga_and_stress_management">Yoga and Stress Management</option>
                                <option name="other" <?php if ($category == "other") echo "selected = 'selected'" ?> value="other">Other</option>
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="status_act">Level of Activity</label>
                            <span class="colour" style="color : red"><b> *</b></span>
                            <select required name="status_act" id="status_act" class="form-control input-lg">
                                <option value="" disabled selected>Select your option:</option>
                                <option <?php if ($status_act == "college") echo "selected = 'selected'" ?> value="college">College</option>
                                <option <?php if ($status_act == "national") echo "selected = 'selected'" ?> value="national">National</option>
                                <option <?php if ($status_act == "international") echo "selected = 'selected'" ?> value="international">International</option>
                                <!-- <option <?php if ($status_act == "others") echo "selected = 'selected'" ?> value="others">Others</option> -->
                            </select>
                        </div>

                        <!-- <div class="form-group col-md-6">
                            <label for="awards">Awards (If Any)</label>
                            <br> -->
                        <!-- <input class="form-control input-lg" type="text" name="awards" id="awards" value="<?php echo $awards; ?>"> -->
                        <!-- <input type="text" class="form-control input-lg" name="awards" id="awards" value="<?php echo $awards; ?>"">
                    </div>
                     <div class=" form-group col-md-6">
                            <label for="applicable-fdc">Is FDC applicable? </label><span class="colour"><b> *</b></span>
                            <select required onchange="myfunction1()" class="form-control input-lg applicable-fdc" id="applicable-fdc" name="applicablefdc">
                                <option <?php if ($fdc == "Not applicable") echo "selected = 'selected'" ?> value="No">No</option>
                                <option <?php if ($fdc == 'no' || $fdc == 'yes' || $fdc == 'No' || $fdc == 'Yes') echo "selected = 'selected'" ?> value="Yes">Yes</option>
                            </select>
                        </div> -->

                        <div class="form-group col-md-6 col-md-offset-1"></div>
                        <div class="form-group col-md-6">
                            <!-- <div>
                                <label for="Index">Permission : </label><br />
                                <input type="radio" id="r1" name="applicable" value="1" class="non-vac" <?php echo ($paperpath != NULL) ? 'checked' : '' ?>>Yes
                                <br>
                                <input type="radio" name="applicable" value="2" class="vac" <?php echo ($paperpath == 'NULL') ? 'checked' : '' ?>>Applicable, but not yet available <br>
                                <input type="radio" name="applicable" value="3" class="vac" <?php echo ($paperpath == 'not_applicable') ? 'checked' : '' ?>> No
                            </div>
                            <br> -->
                            <!-- <div class='second-reveal' id='f1'>
                                <div>
                                    <label for="card-image">Permission </label><span class="colour"><b> *</b></span>
                                    <input type="file" class="form-control input-lg" id="card-image" name="paper">
                                    <a <?php
                                        $f = 0;
                                        if ($paperpath != "not_applicable" && $paperpath != "NULL" && $paperpath != 'no status' && $paperpath != "") {
                                            echo "href='$paperpath'";
                                            $f = 1;
                                        } else {
                                            echo "style='display:none'";
                                        }
                                        ?> target="_blank">
                                        <h4><?php if ($f == 1) {
                                                echo "View Existing permission";
                                            } ?><h4>
                                    </a>
                                </div>
                            </div> -->


                            <div>
                                <label for="Index">Upload Certificate:Applicable? </label><span class="colour" style="color : red"><b> *</b></span><br />
                                <input type="radio" name="applicable1" id="r2" value="1" class="non-vac1" <?php echo ($certipath != NULL) ? 'checked' : '' ?>>Yes<br>
                                <input type="radio" name="applicable1" value="2" class="vac1" <?php echo ($certipath == 'NULL') ? 'checked' : '' ?>>Applicable, but not yet available <br>
                                <input type="radio" name="applicable1" value="3" class="vac1" <?php echo ($certipath == 'not_applicable') ? 'checked' : '' ?>> No

                            </div>
                            <br>
                            <div class='second-reveal1' id='f2'>
                                <div>

                                    <label for="card-image">Certificate </label><span class="colour"><b> *</b></span>
                                    <input type="file" class="form-control input-lg" id="card-image" name="certificate">
                                    <a <?php
                                        $f1 = 0;
                                        if ($certipath != "not_applicable" && $certipath != "NULL" && $certipath != 'no status' && $certipath != "") {
                                            echo "href='$certipath'";
                                            $f1 = 1;
                                        } else {
                                            echo "style='display:none'";
                                        }
                                        ?> target="_blank">
                                        <h4><?php if ($f1 == 1) {
                                                echo "View Existing Certificate";
                                            } ?><h4>
                                    </a>
                                </div>
                            </div>


                            <div>
                                <label for="Index">Upload Report:Applicable?</label><span class="colour" style="color : red"><b> *</b></span><br />
                                <input type="radio" name="applicable2" id="r3" value="1" class="non-vac2" <?php echo ($reportpath != NULL) ? 'checked' : '' ?>>Yes<br>
                                <input type="radio" name="applicable2" value="2" class="vac2" <?php echo ($reportpath == 'NULL') ? 'checked' : '' ?>>Applicable, but not yet available <br>
                                <input type="radio" name="applicable2" value="3" class="vac2" <?php echo ($reportpath == 'not_applicable') ? 'checked' : '' ?>> No
                            </div>
                            <br>
                            <div class='second-reveal2' id='f3'>
                                <div>

                                    <label for="card-image">Report </label><span class="colour"><b> *</b></span>
                                    <input type="file" class="form-control input-lg" id="card-image" name="report">
                                    <a <?php
                                        $f2 = 0;
                                        if ($reportpath != "not_applicable" && $reportpath != "NULL" && $reportpath != 'no status' && $reportpath != "") {
                                            echo "href='$reportpath'";
                                            $f2 = 1;
                                        } else {
                                            echo "style='display:none'";
                                        }
                                        ?> target="_blank">
                                        <h4><?php if ($f2 == 1) {
                                                echo "View Existing Report";
                                            } ?><h4>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <script>
                            window.onload = function() {
                                mycheck1();
                                mycheck2();
                                mycheck3();

                            }

                            function mycheck1() {
                                var radio1 = document.getElementById("r1");
                                var file1 = document.getElementById("f1");
                                if (radio1.checked == true) {
                                    file1.style.display = "block";
                                } else {
                                    file1.style.display = "none";
                                }
                            }

                            function mycheck2() {
                                var radio2 = document.getElementById("r2");
                                var file2 = document.getElementById("f2");
                                if (radio2.checked == true) {
                                    file2.style.display = "block";
                                } else {
                                    file2.style.display = "none";
                                }
                            }

                            function mycheck3() {
                                var radio3 = document.getElementById("r3");
                                var file3 = document.getElementById("f3");
                                if (radio3.checked == true) {
                                    file3.style.display = "block";
                                } else {
                                    file3.style.display = "none";
                                }
                            }
                        </script>


                        <div class="form-group col-md-12">
                            <?php
                            if ($_SESSION['type'] == 'hod') {
                                echo '<a href="2_dashboard_attend_hod.php" type="button" class="btn btn-warning btn-lg">Cancel</a>';
                            } else {
                                echo '<a href="2_dashboard_attend.php" type="button" class="btn btn-warning btn-lg">Cancel</a>';
                            }
                            ?>
                            <button name="update" type="submit" class="btn btn-success pull-right btn-lg">Submit</button>
                        </div>




                    </form>

                </div>
            </div>
        </div>
    </section>


</div>




<?php include_once('footer.php'); ?>