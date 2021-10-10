<?php
session_start();
//check if user has logged in or not

if (!isset($_SESSION['loggedInUser'])) {
	//send the user to login page
	header("location:index.php");
}
//connect to database

$_SESSION['currentTab'] = "Online";
include_once("includes/connection.php");
$fid = $_SESSION['Fac_ID'];
$queryrun = "SELECT * FROM facultydetails where Fac_ID=$fid";
$resultrun = mysqli_query($conn, $queryrun);
while ($row = mysqli_fetch_assoc($resultrun)) {
	$_SESSION['Dept'] = $row['Dept'];
	$_SESSION['type'] = $row['type'];
}
//include custom functions files 
include_once("includes/functions.php");
include_once("includes/config.php");

//setting error variables
$nameError = "";
$flag = 1;
$emailError = "";
$courseName = $startDate = $endDate = $paperType = $paperLevel = $paperCategory = $location = $coAuthors = "";

$Fac_ID = null;
date_default_timezone_set("Asia/Kolkata");
if (isset($_POST['rid'])) {
	$id = $_POST['rid'];
	$_SESSION['id'] = $_POST['rid'];
}
$id = $_SESSION['id'];
$query = "SELECT * from online_course_attended where OC_A_ID = $id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$Fac_ID = $row['Fac_ID'];
// $type_of_course_db = $row['type_of_course'];
$courseName_db = $row['Course_Name'];
$startDate = $row['Date_From'];
$endDate = $row['Date_To'];
$organised = $row['Organised_by'];
// $purpose = $row['Purpose'];
// $status_of_activity_db = $row['status_of_activity'];
$duration = $row['noofweeks'];
$credit_audit_db = $row['credit_audit'];
//echo $purpose."dhasjdjkanskdnkasnjkdnkjasnd".$endDate;
// $fdc_db = $row['FDC_Y_N'];
$certipath = $row['certificate_path'];
// $reportpath = $row['report_path'];
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

		$courseName = validateFormData($_POST['courseName']);
		$courseName = "'" . $courseName . "'";

		if ((strtotime($_POST['startDate'])) > (strtotime($_POST['endDate']))) {
			$nameError = $nameError . "Start Date cannot be greater than end date<br>";
			$flag = 0;
		}

		$startDate = validateFormData($_POST['startDate']);
		$endDate = validateFormData($_POST['endDate']);

		$time = time();
		$start = new DateTime(date($startDate, $time));
		$end = new DateTime(date($endDate, $time));
		$days = date_diff($start, $end);
		$noofdays = $days->format('%d');
		$month = $start->format('n');
		$year = $start->format('Y');
		$no_of_weeks = $no_of_days / 7;

		$startDate = "'" . $startDate . "'";
		$endDate = "'" . $endDate . "'";

		$organised = validateFormData($_POST['organised']);
		$organised = "'" . $organised . "'";

		// $type = validateFormData($_POST['type']);
		// $type = "'" . $type . "'";
		// $status = validateFormData($_POST['status']);
		// $status = "'" . $status . "'";
		// $duration = validateFormData($_POST['duration']);
		// $duration = "'" . $duration . "'";
		$creau = validateFormData($_POST['creau']);
		$creau = "'" . $creau . "'";
		//following are not required so we can directly take them as it is

		// $purpose = validateFormData($_POST["purpose"]);
		// $purpose = "'" . $purpose . "'";

		// $applicablefdc = $_POST["applicablefdc"];
		// $fdc = $applicablefdc;

		// if ($applicablefdc == 'Yes') {
		// 	$fdc = "Yes";
		// } else if ($applicablefdc == 'No') {
		// 	$fdc = "Not applicable";
		// 	//$fdc = "'".$fdc."'";

		// }
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
					$fileExt = strtolower(end(explode('.', $fileName)));
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
					$fileExt = strtolower(end(explode('.', $fileName)));
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
		//checking if there was an error or not
		$query = "SELECT Fac_ID from facultydetails where Email='" . $_SESSION['loggedInEmail'] . "';";
		$result = mysqli_query($conn, $query);
		if ($result) {
			$row = mysqli_fetch_assoc($result);
			$author = $row['Fac_ID'];
		}
		$succ = 0;
		$success1 = 0;

		// $replace_str = array('"', "'", '', '');
		// if (isset($_POST['purpose'])) {
		// 	$purpose = str_replace($replace_str, "", $purpose);
		// 	$purpose = str_replace("rn", '', $purpose);
		// } else
		// 	$purpose  = '';

		if ($flag != 0) {
			$sql = "UPDATE online_course_attended set Course_Name = $courseName,
							   Date_from = $startDate,
							   Date_to = $endDate, 
							   Organised_by = $organised,
							   credit_audit=$creau,
							   certificate_path ='" . $certipath . "',
							   noofdays = $noofdays,
							   noofweeks = $no_of_weeks,
							   month = $month,
							   year = $year
							   WHERE OC_A_ID = '" . $_SESSION['id'] . "'";
			$result = mysqli_query($conn, $sql);
			if ($result == true) {
				$success = 1;
			}

			if ($success == 1) {
				if ($_SESSION['type'] == 'hod') {
					header("location:2_dashboard_hod_online_attended.php?alert=update");
				} else {
					header("location:2_dashboard_online_attended.php?alert=update");
				}
			} else {
				if ($_SESSION['type'] == 'hod') {
					header("location:2_dashboard_hod_online_attended.php?alert=error");
				} else {
					header("location:2_dashboard_online_attended.php?alert=error");
				}
			}
		}
	}
}
//close the connection
mysqli_close($conn);
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
</style>

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<!-- left column -->
			<div class="col-md-8">
				<!-- general form elements -->
				<br /><br /><br />

				<div class="box box-primary">
					<div class="box-header with-border">
						<div class="icon">
							<i style="font-size:20px" class="fa fa-edit"></i>
							<h3 class="box-title"><b>Online/Offline Course Attended Edit Form</b></h3>
							<br>
						</div>
					</div><!-- /.box-header --><br>
					<br>
					<!-- form start -->
					<form role="form" method="POST" class="row" action="" style="margin:10px;" enctype="multipart/form-data">
						<?php
						if ($flag == 0) {
							echo '<div class="error">' . $nameError . '</div>';
							//echo '<script type="text/javascript">alert("INFO:  '.$nameError.'");</script>';				
						}
						?>
						<div class="form-group col-md-12">
							<label for="department">Department </label>
							<br><input type='text' class='form-control input-lg' name='department[]' id='department' readOnly value='<?php echo $_SESSION['Dept']; ?>'>
						</div>

						<div class="form-group col-md-6">

							<label for="faculty-name">Faculty Name</label>
							<input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $F_NAME; ?>" readonly>
						</div>
						<!-- <div class="form-group col-md-6">
							<label for="type">Type Of Course*</label>
							<select required class="form-control input-lg" value='<?php echo $type_of_course; ?>' id="type" name="type">
								<option <?php if ($type_of_course_db == "online") echo "selected = 'selected'" ?> value="online">Online</option>
								<option <?php if ($type_of_course_db == "offline") echo "selected = 'selected'" ?> value="offline">Offline</option>
							</select>
						</div> -->

						<input type='hidden' name='id' value='<?php echo $id; ?>'>
						<div class="form-group col-md-6">
							<label for="courseName">Name of course *</label>
							<input required type="text" class="form-control input-lg" id="courseName" name="courseName" value='<?php echo $courseName_db; ?>'>
						</div>

						<div class="form-group col-md-6">
							<label for="start-date">Start Date *</label>
							<input <?php echo "value = $startDate"; ?> required type="date" class="form-control input-lg" id="start-date" name="startDate" placeholder="03:10:10">
						</div>

						<div class="form-group col-md-6">
							<label for="end-date">End Date *</label>
							<input <?php echo "value = $endDate"; ?> required type="date" class="form-control input-lg" id="end-date" name="endDate" placeholder="03:10:10">
						</div>

						<div class="form-group col-md-6">
							<label for="organised">Course organised by *</label>
							<input value='<?php echo $organised ?>' required type="text" class="form-control input-lg" id="organised" name="organised">
						</div>

						<!-- <div class="form-group col-md-6">
							<label for="purpose">Purpose of Course * </label>
							<input type="text" required class="form-control input-lg" id="purpose" name="purpose" rows="2" value="<?php echo $purpose; ?>">
						</div> -->

						<!-- <div class="form-group col-md-6">
							<label for="status">Status Of Activity *</label>
							<select required class="form-control input-lg" id="status" value='<?php echo $status_of_activity; ?>' name="status">
								<option <?php if ($status_of_activity_db == "local") echo "selected = 'selected'" ?> value="local">Local</option>
								<option <?php if ($status_of_activity_db == "state") echo "selected = 'selected'" ?> value="state">State</option>
								<option <?php if ($status_of_activity_db == "national") echo "selected = 'selected'" ?> value="national">National</option>
								<option <?php if ($status_of_activity_db == "international") echo "selected = 'selected'" ?> value="international">International</option>
							</select>
						</div> -->
						<!-- <div class="form-group col-md-6">
							<label for="duration">Duration in weeks</label>
							<input <?php echo "value = $duration"; ?> type="text" class="form-control input-lg" id="duration" name="duration">
						</div> -->
						<div class="form-group col-md-6">
							<label for="creau">Certificate/Audit *</label>
							<select required class="form-control input-lg" id="creau" value='<?php echo $credit_audit; ?>' name="creau">
								<option <?php if ($credit_audit_db == "credit") echo "selected = 'selected'" ?> value="credit">Credit</option>
								<option <?php if ($credit_audit_db == "audit") echo "selected = 'selected'" ?> value="audit">Audit</option>
							</select>
						</div>
						<!-- <div class="form-group col-md-6">
							<label for="applicable-fdc">Is FDC applicable? </label><span class="colour"><b> *</b></span>
							<select required onchange="myfunction1()" class="form-control input-lg applicable-fdc" id="applicable-fdc" name="applicablefdc">
								<option <?php if ($fdc_db == "Not applicable") echo "selected = 'selected'" ?> value="No">No</option>
								<option <?php if ($fdc_db == 'no' || $fdc_db == 'yes' || $fdc_db == 'No' || $fdc_db == 'Yes') echo "selected = 'selected'" ?> value="Yes">Yes</option>
							</select>
						</div> -->
						<div class="form-group col-md-6">
						</div>

						<div class="form-group col-md-6 col-md-offset-1"></div>
						<div class="form-group col-md-6">
							<div>
								<label for="Index">Certificate : </label><br />
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
										if ($certipath != "not_applicable" && $certipath != "NULL" && $certipath != "no status" && $certipath != "") {
											echo "href='$certipath'";
											$f1 = 1;
										} else {
											echo "style='display:none'";
										}
										?> target="_blank">
										<h4><?php if ($f1 == 1) {
												echo "View Existing Certificate";
											} ?></h4>
									</a>
								</div>
							</div>
							<!-- <div>
								<label for="Index">Report : </label><br />
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
											} ?></h4>
									</a>
								</div>
							</div> -->
						</div>
						<script>
							window.onload = function() {
								mycheck2();
								mycheck3();
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
						<br />
						<div class="form-group col-md-12">
							<?php
							if ($_SESSION['type'] == 'hod') {
								echo '<a href="2_dashboard_hod_online_attended.php" type="button" class="btn btn-warning btn-lg">Cancel</a>';
							} else {
								echo '<a href="2_dashboard_online_attended.php" type="button" class="btn btn-warning btn-lg">Cancel</a>';
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