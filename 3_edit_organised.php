<?php
session_start();
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
include_once("includes/config.php");

$Fac_ID = $_SESSION['Fac_ID'];
if (isset($_POST['rid'])) {
    $_SESSION['id'] = $_POST['rid'];
    $_POST['rid'] = $_SESSION['id'];
}

//setting error variables
$nameError = "";
$emailError = "";
$activitytitle = $startDate = $endDate = $activitytype = $location = $coordinated = $resource = $role_of_faculty = $time_activities = $status_act = $sponsors = $sponsor_details = $approval_details = $last_updated = "";
$no_of_participants = $paperpath = $certipath = $reportpath = $brochurepath = 0;
$no_hours = 0;
// echo "Hello";
if (isset($_POST['rid'])) {
    $id = $_POST['rid'];
    // echo $id;
    $query = "SELECT * FROM organised WHERE A_ID = $id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $Fac_ID = $row['Fac_ID'];
    // echo "<h1>" . $query . "</h1>";

    $activitytitle = $row['Act_title'];
    $startDate = $row['Date_from'];
    $endDate = $row['Date_to'];
    $activitytype = $row['Act_type'];
    $organized = $row['Organized_by'];
    $coordinated = $row['Coordinated_by'];
    $location = $row['Location'];
    $resource = $row['Resource'];
    // $role_of_faculty=$row['Role_Of_Faculty'];
    // $time_activities=$row['Time_Activities'];
    $no_of_participants = $row['No_Of_Participants'];
    $no_hours = $row['Equivalent_Duration'];
    $status_act = $row['Status_Of_Activity'];
    $res_org = $row['ResourceOrg'];
    $targetaud = $row['TargetAud'];
    $category = $row['Category'];
    // $sponsors=$row['Sponsorship'];
    // $sponsor_details=$row['Sponsor_Details'];
    // $approval_details=$row['Approval_Details'];
    $last_updated = $row['LastUpdated'];
    $paperpath = $row['Permission_path'];
    $certipath = $row['Certificate_path'];
    $reportpath = $row['Report_path'];
    $brochurepath = $row['Brochure_path'];
    $coordiarray = explode(',', $coordinated);
    // echo json_encode($coordiarray);
}


$query2 = "SELECT * from facultydetails where Fac_ID = $Fac_ID";
$result2 = mysqli_query($conn, $query2);
if ($result2) {
    $row = mysqli_fetch_assoc($result2);
    $F_NAME = $row['F_NAME'];
}


//check if the form was submitted
if (isset($_POST['update'])) {
    $time = time();
    echo '<script type="text/javascript">alert("$activitytitle") </script>';
    //the form was submitted
    $clientName = $clientEmail = $clientPhone = $clientAddress = $clientCompany = $clientNotes = "";

    //check for any blank input which are required

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

    $coordinated = $_POST['coordinated'];
    $resource = $_POST['resource'];
    //following are not required so we can directly take them as it is

    // if (!$_POST['role_of_faculty']) {
    //     $nameError = "Please enter a Role_Of_Faculty<br>";
    // } else {
    //     $role_of_faculty = validateFormData($_POST['role_of_faculty']);
    //     $role_of_faculty = "'" . $role_of_faculty . "'";
    // }
    // if (!$_POST['time_activities']) {
    //     $nameError = "Please enter a Time_Activities<br>";
    // } else {
    //     $time_activities = validateFormData($_POST['time_activities']);
    //     $time_activities = "'" . $time_activities . "'";
    // }
    if (!$_POST['no_of_participants']) {
        $nameError = "Please enter a No_Of_Participants<br>";
    } else {
        $no_of_participants = validateFormData($_POST['no_of_participants']);
        $no_of_participants = "'" . $no_of_participants . "'";
    }
    // if (!$_POST['no_hours']) {
    //     $nameError = "Please enter a Equivalent_Duration<br>";
    // } else {
    //     $no_hours = validateFormData($_POST['no_hours']);
    //     $no_hours = "'" . $no_hours . "'";
    // }
    if (!$_POST['status_act']) {
        $nameError = "Please enter a Status_Of_Activity<br>";
    } else {
        $status_act = validateFormData($_POST['status_act']);
        $status_act = "'" . $status_act . "'";
    }

    if (!$_POST['resource_org']) {
        $nameError = "Please enter a Status_Of_Activity<br>";
    } else {
        $res_org = validateFormData($_POST['resource_org']);
        $res_org = "'" . $res_org . "'";
    }

    if (!$_POST['target_aud']) {
        $nameError = "Please enter a Status_Of_Activity<br>";
    } else {
        $targetaud = validateFormData($_POST['target_aud']);
        $targetaud = "'" . $targetaud . "'";
    }

    if (!$_POST['category']) {
        $category = "Please enter category<br>";
    } else {
        $category = validateFormData($_POST['category']);
        $category = "'" . $category . "'";
    }

    $days = date_diff($start, $end);
    $no_of_days = $days->format('%d') + 1;
    $month = $start->format('n');
    $year = $start->format('Y');
    $no_of_weeks = $no_of_days / 7;
    $no_of_hours = $no_of_days * 8;

    if (!isset($_POST['co_name']) && $s != 0) {
        $coauthorname = "NA";
    }
    $coauthorname = "";
    $coautharray = array();
    if (isset($_POST["co_name"])) {
        for ($count2 = 0; $count2 < count($_POST["co_name"]); $count2++) {
            $co_name = $_POST["co_name"][$count2];
            array_push($coautharray, $co_name);
        }
        $coauthorname = implode(',', $coautharray);
    } else {
        $coauthorname = $coordinated;
    }
    // echo $coauthorname;
    // if ($approval_details != "") {
    //     $approval_details = validateFormData($approval_details);
    //     $approval_details = "$approval_details";
    // } else {
    //     $approval_details = 'NA';
    // }

    // $sponsors = validateFormData($sponsors);
    // $sponsors = "'" . $sponsors . "'";

    // if ($sponsor_details != "") {
    //     $sponsor_details = validateFormData($sponsor_details);
    //     $sponsor_details = "$sponsor_details";
    // } else {
    //     $sponsor_details = 'NA';
    // }


    // $sponsors = $_POST['sponsors'];
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
                echo "hello";
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
    if (isset($_POST['applicable3'])) {
        if ($_POST['applicable3'] == 2) {
            $brochurepath = 'NULL';
            $success = 1;
        } else if ($_POST['applicable3'] == 3) {
            $brochurepath = 'not_applicable';
            $success = 1;
        } else if ($_POST['applicable3'] == 1) {
            if (isset($_FILES['brochure'])) {
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
                        echo "<h1> $targetName </h1>";
                    }
                } else {
                    print_r($errors);
                }
            }
        }
    }

    $days = date_diff($start, $end);
    $noofdays = $days->format('%d');
    //checking if there was an error or not


    $sql = "update organised set Act_title = $activitytitle,
                               Act_type = $activitytype,
                               Organized_by = $organized,
                               Date_from = $startDate,
                               Date_to = $endDate, 
                               Location = $location,
                               Resource = '$resource',
                               Coordinated_by = '$coauthorname',
                               No_Of_Participants=$no_of_participants,
                               Status_Of_Activity=$status_act,
                               Permission_path='" . $paperpath . "',
                               Certificate_path='" . $certipath . "',
                               Report_path='" . $reportpath . "',
                               Brochure_path='" . $brochurepath . "',
                               noofdays = $no_of_days,
                               noofweeks = $no_of_weeks,
                               month = $month,
                               Category = $category,
                               TargetAud = $targetaud,
                               ResourceOrg = $res_org,
                               year = $year,
                               Equivalent_Duration = $no_of_hours
                               WHERE A_ID ='" . $_SESSION['id'] . "'";

    echo $sql;
    if ($conn->query($sql) === TRUE) {
        if ($_SESSION['type'] == 'hod') {
            header("location:2_dashboard_organised_hod.php?alert=update");
        } else {
            header("location:2_dashboard_organised.php?alert=update");
        }
    } else {
        if ($_SESSION['type'] == 'hod') {
            header("location:2_dashboard_organised_hod.php?alert=error");
        } else {
            header("location:2_dashboard_organised.php?alert=error");
        }
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





<?php include_once('head.php'); ?>
<?php include_once('header.php'); ?>
<?php include_once("includes/scripting.php"); ?>

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
                            <h3 class="box-title"><b>Edit STTP/Workshop/FDP Activities Organised</b></h3>
                            <br>
                        </div>
                    </div><!-- /.box-header -->
                    <br>

                    <!-- form start -->
                    <form role="form" method="POST" class="row" action="" style="margin:10px;" enctype="multipart/form-data">
                        <input type='hidden' name='id' value='<?php echo $id; ?>'>
                        <div class="form-group col-md-6">
                            <label for="department_name">Department</label>
                            <input required type="text" class="form-control input-lg" id="dept" name="dept" value="<?php echo $_SESSION['Dept']; ?>" readonly>
                        </div>
                        <div class="form-group col-md-6">

                            <label for="faculty-name">Faculty Name</label>
                            <input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $F_NAME; ?>" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="paper-title">Title</label>
                            <span class="colour" style="color : red"><b> *</b></span>
                            <input required type="text" class="form-control input-lg" id="paper-title" name="activitytitle" value='<?php echo $activitytitle; ?>'>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="paper-type">Activity Type</label>
                            <span class="colour" style="color : red"><b> *</b></span>
                            <select required class="form-control input-lg" id="paper-type" name="activitytype">
                                <option <?php if ($activitytype == "STTP") echo "selected = 'selected'" ?> value="STTP">Short Term Training Programme(STTP)</option>
                                <option <?php if ($activitytype == "FDP") echo "selected = 'selected'" ?> value="FDP">Faculty Development Programme(FDP)</option>
                                <option <?php if ($activitytype == "WS") echo "selected = 'selected'" ?> value="WS">Workshop(WS)</option>
                                <option <?php if ($activitytype == "TR") echo "selected = 'selected'" ?> value="TR">Training(TR)</option>
                                <option <?php if ($activitytype == "S") echo "selected = 'selected'" ?> value="S">Seminar(S)</option>
                                <option <?php if ($activitytype == "IN") echo "selected = 'selected'" ?> value="IN">Internship(IN)</option>
                                <option <?php if ($activitytype == "EL") echo "selected = 'selected'" ?> value="EL">Expert Lecture(EL)</option>
                                <option <?php if ($activitytype == "Webinar") echo "selected = 'selected'" ?> value="Webinar">Webinar</option>
                                <option <?php if ($activitytype == "Others") echo "selected = 'selected'" ?> value="Others">Others</option>

                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="conf">Organized Under</label>
                            <span class="colour" style="color : red"><b> *</b></span>
                            <input type="text" required class="form-control input-lg" id="organised" name="organized" rows="2" value="<?php echo $organized; ?>">
                        </div>

                        <!-- <div class="form-group col-md-6">
                            <label for="conf">Co-ordinated by *</label>
                            <input type="text" required class="form-control input-lg" id="coordinated" name="coordinated" rows="2" value="<?php echo $coordinated; ?>">
                        </div> -->
                        <div class="form-group col-md-6">

                            <label for="c_name">Co-Organiser</label>
                            <div class="table-repsonsive">
                                <span id="error"></span>
                                <table class="table table-bordered" id="c_name">
                                    <tr>
                                        <input type="text" required class="form-control input-lg" id="coordinated" name="coordinated" rows="2" value="<?php echo $coordinated; ?>" disabled>
                                    </tr>
                                    <tr>
                                        <th>Click to Edit </th>
                                        <th><button type="button" name="add" class="btn btn-success btn-sm add"><span class="glyphicon glyphicon-plus"></span></button></th>
                                    </tr>
                                </table>
                            </div>
                        </div><br>

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
                            <label for="location">Venue</label>
                            <span class="colour" style="color : red"><b> *</b></span>
                            <input required type="text" class="form-control input-lg" id="location" name="location" value='<?php echo $location; ?>'>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="resource">Resource Person</label>
                            <span class="colour" style="color : red"><b> *</b></span>
                            <input required type="text" class="form-control input-lg" id="resource" name="resource" value='<?php echo $resource; ?>'>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="resource_org">Resource Organization</label>
                            <span class="colour" style="color : red"><b> *</b></span>
                            <input required type="text" class="form-control input-lg" id="resource_org" name="resource_org" value='<?php echo $res_org; ?>'>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="target_aud">Target Audience</label>
                            <span class="colour" style="color : red"><b> *</b></span>
                            <input required type="text" class="form-control input-lg" id="target_aud" name="target_aud" value='<?php echo $targetaud; ?>'>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="category">Category</label>
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

                        <!-- <div class="form-group col-md-6">
                            <label for="role_of_faculty">Role of Faculty: *</label>
                            <br>
                            <input type="text" id="role_of_faculty" class="form-control input-lg" name="role_of_faculty" required value="<?php echo $role_of_faculty ?>">
                        </div>

                        <div class="form-group col-md-6">
                            <label id="time_activities">Full-Time/Part-Time: *</label>
                            <select required name="time_activities" id="time_activites" class="form-control input-lg">
                                <option value="" disabled selected>Select your option:</option>
                                <option <?php if ($time_activities == "full-time") echo "selected = 'selected'" ?> value="full-time">Full-Time</option>
                                <option <?php if ($time_activities == "part-time") echo "selected = 'selected'" ?> value="part-time">Part-Time</option>
                            </select>
                        </div> -->

                        <div class="form-group col-md-6">
                            <label for="no_of_participants">Number of participants:</label>
                            <span class="colour" style="color : red"><b> *</b></span>
                            <input type="number" name="no_of_participants" id="no_of_participants" class="form-control input-lg" value='<?php echo $no_of_participants; ?>' min="1">
                        </div>

                        <!-- <div class="form-group col-md-6">
                            <label for="no_hours">Equivalent duration(In hours) *</label>
                            <input type="text" name="no_hours" id="no_hours" placeholder="Enter the total hours" class="form-control input-lg" value='<?php echo $no_hours; ?>'>
                        </div> -->

                        <div class="form-group col-md-6">
                            <label for="status_act">Level:</label>
                            <span class="colour" style="color : red"><b> *</b></span>
                            <select required name="status_act" id="status_act" class="form-control input-lg">
                                <option value="" disabled selected>Select your option:</option>
                                <option <?php if ($status_act == "college") echo "selected = 'selected'" ?> value="college">College</option>
                                <option <?php if ($status_act == "national") echo "selected = 'selected'" ?> value="national">National</option>
                                <option <?php if ($status_act == "international") echo "selected = 'selected'" ?> value="international">InterNational</option>
                                <option <?php if ($status_act == "others") echo "selected = 'selected'" ?> value="others">Others</option>
                            </select>
                        </div>



                        <!-- <div class="form-group col-md-6">
                            <label for="sponsors">Sponsored/Not-sponsored: </label><span class="colour"><b> *</b></span>
                            <select required onchange="myfunction()" class="form-control input-lg" id="sponsors" name="sponsors">
                                <option <?php if ($sponsors == "not-sponsored") echo "selected = 'selected'" ?> value="not-sponsored">Not sponsored</option>
                                <option <?php if ($sponsors == "sponsored") echo "selected = 'selected'" ?> value="sponsored">Sponsored</option>
                            </select>
                        </div>



                        <div id="sponsor_details" class="form-group col-md-6" style="display:none">
                            <label for="sponsor_details">Sponsor Details:</label><span class="colour"><b> *</b></span>
                            <input <?php echo "value = '$sponsor_details'"; ?> type="text" class="form-control input-lg" id="sponsor_details" name="sponsor_details">
                        </div>

                        <div id="approval_details" class="form-group col-md-6" style="display:none">
                            <label for="approval_details">Approval Details: </label>
                            <br>
                            <input type="text" name="approval_details[]" id="approval_details" class="form-control input-lg">
                        </div> -->


                        <div class="form-group col-md-6 col-md-offset-1"></div>

                        <div class="form-group col-md-6">

                            <div>
                                <label for="Index">Permission Letter : </label><span class="colour" style="color : red"><b> *</b></span><br />
                                <input type="radio" id="r1" name="applicable" value="1" class="non-vac" <?php echo ($paperpath != NULL) ? 'checked' : '' ?>>Yes
                                <br>
                                <input type="radio" name="applicable" value="2" class="vac" <?php echo ($paperpath == 'NULL') ? 'checked' : '' ?>>Applicable, but not yet available <br>
                                <input type="radio" name="applicable" value="3" class="vac" <?php echo ($paperpath == 'not_applicable') ? 'checked' : '' ?>> No
                            </div>
                            <br>
                            <div class='second-reveal' id='f1'>
                                <div>

                                    <label for="card-image">Permission Letter </label><span class="colour"><b> *</b></span>
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
                                                echo "View Existing permission letter";
                                            } ?><h4>
                                    </a>
                                </div>
                            </div>


                            <div>
                                <label for="Index">Certificate : </label><span class="colour" style="color : red"><b> *</b></span><br />
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
                                <label for="Index">Report : </label>
                                <span class="colour" style="color : red"><b> *</b></span><br />
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

                            <div>
                                <label for="Index">Brochure : </label><span class="colour" style="color : red"><b> *</b></span><br />
                                <input type="radio" name="applicable3" id="r4" value="1" class="non-vac4" <?php echo ($brochurepath != NULL) ? 'checked' : '' ?>>Yes<br>
                                <input type="radio" name="applicable3" value="2" class="vac4" <?php echo ($brochurepath == 'NULL') ? 'checked' : '' ?>>Applicable, but not yet available <br>
                                <input type="radio" name="applicable3" value="3" class="vac4" <?php echo ($brochurepath == 'not_applicable') ? 'checked' : '' ?>> No
                            </div>
                            <br>
                            <div class='second-reveal4' id='f4'>
                                <div>

                                    <label for="card-image">Brochure </label><span class="colour"><b> *</b></span>
                                    <input type="file" class="form-control input-lg" id="card-image" name="brochure">
                                    <a <?php
                                        $f2 = 0;
                                        if ($brochurepath != "not_applicable" && $brochurepath != "NULL" && $brochurepath != 'no status' && $brochurepath != "") {
                                            echo "href='$brochurepath'";
                                            $f2 = 1;
                                        } else {
                                            echo "style='display:none'";
                                        }
                                        ?> target="_blank">
                                        <h4><?php if ($f2 == 1) {
                                                echo "View Existing Brochure";
                                            } ?><h4>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <script>
                            window.onload = function() {
                                mycheck4();
                                mycheck1();
                                mycheck2();
                                mycheck3();
                                myfunction();
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

                            function mycheck4() {
                                var radio4 = document.getElementById("r4");
                                var file4 = document.getElementById("f4");
                                if (radio4.checked == true) {
                                    file4.style.display = "block";
                                } else {
                                    file4.style.display = "none";
                                }
                            }

                            function myfunction() {
                                var x = document.getElementById("sponsors").value;

                                if (x == 'sponsored') {
                                    //document.getElementById("demo").innerHTML = "You selected: " + x;
                                    //console.log(document.getElementById("presented-by"));
                                    document.getElementById("sponsor_details").style.display = 'block';
                                    document.getElementById("approval_details").style.display = 'block';
                                } else {
                                    //document.getElementById("demo").innerHTML = "You selected: " + x;
                                    document.getElementById("sponsor_details").style.display = 'none';
                                    document.getElementById("approval_details").style.display = 'none';
                                }
                            }
                        </script>


                        <div class="form-group col-md-12">
                            <?php
                            if ($_SESSION['type'] == 'hod') {
                                echo '<a href="2_dashboard_organised_hod.php" type="button" class="btn btn-warning btn-lg">Cancel</a>';
                            } else {
                                echo '<a href="2_dashboard_organised.php" type="button" class="btn btn-warning btn-lg">Cancel</a>';
                            }
                            ?> <button name="update" type="submit" class="btn btn-success pull-right btn-lg">Submit</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            // var coordiarr = new Array();
            // <?php foreach ($coordiarray as $key => $val) { ?>
            //     coordiarr.push('<?php echo $val; ?>');
            // <?php } ?>


            $(document).on('click', '.add', function() {
                var html = '';
                <?php for ($x = 0; $x < sizeof($coordiarray); $x++) { ?>
                    html += '<tr>';
                    html += '<td><select name="co_name[]" class="form-control item_unit" id="search"><option value="<?php echo $coordiarray[$x]; ?>"><?php echo $coordiarray[$x]; ?></option><?php echo fill_unit_select_box($connect); ?></select></td>';
                    html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm remove"><span class="glyphicon glyphicon-minus"></span></button></td></tr>';

                <?php } ?>
                $('#c_name').append(html);
            });

            $(document).on('click', '.remove', function() {
                $(this).closest('tr').remove();
            });
        });
    </script>

</div>




<?php include_once('footer.php'); ?>