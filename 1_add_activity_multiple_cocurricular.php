<?php
ob_start();
session_start();
include_once('head.php');
include_once('header.php');

if ($_SESSION['type'] != 'faculty') {
	header("location:index.php");
}

if ($_SESSION['type'] == 'hod') {
	include_once('sidebar_hod.php');
} elseif ($_SESSION['type'] == 'cod' || $_SESSION['type'] == 'com') {
	include_once('sidebar_cod.php');
} else {
	include_once('sidebar.php');
}

//check if user has logged in or not

if (!isset($_SESSION['loggedInUser'])) {
	//send the iser to login page
	header("location:index.php");
}
$_SESSION['currentTab'] = "co";
//connect ot database
include_once("includes/connection.php");

//include custom functions files 
include_once("includes/functions.php");
include_once("includes/scripting.php");
//include config file
include_once("includes/config.php");


//setting error variables
$nameError = "";
$emailError = "";
$activity_name = $startDate = $endDate = $purpose_of_activity  = $organized_by = $paperpath = $certipath = $reportpath = "";
$flag = 1;
$s = 1;
$p_id = 0;
$error1 = $error2 = $error3 = "";
$success = 0;
$fid = $_SESSION['Fac_ID'];
$queryrun = "SELECT * FROM facultydetails where Fac_ID=$fid";
$resultrun = mysqli_query($conn, $queryrun);
while ($row = mysqli_fetch_assoc($resultrun)) {
	$_SESSION['Dept'] = $row['Dept'];
	$_SESSION['type'] = $row['type'];
}
$faculty_name = $_SESSION['loggedInUser'];
$_SESSION['F_NAME'] = $_SESSION['loggedInUser'];

//check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (isset($_POST['add'])) {

		//the form was submitted

		$activityname_array = $_POST['title'];
		$event_array = $_POST['title_book'];
		$organizedby_array = $_POST['name_of_institute'];
		$level_array = $_POST['status_activities'];
		$purposeofactivity_array = $_POST['details_of_prize'];
		$startDate_array = $_POST['startdate'];
		$endDate_array = $_POST['enddate'];

		/*	$min_no_array=$_POST['min_no'];
		$serial_no_array=$_POST['serial_no'];
				$period_array = $_POST['period'];

		$od_approv_array=$_POST['od_approv'];
		$od_avail_array=$_POST['od_avail'];
		$fee_sac_array=$_POST['fee_sac'];
		$fee_avail_array=$_POST['fee_avail'];*/


		//check for any blank input which are required

		for ($i = 0; $i < 1; $i++) {
			$activityname = mysqli_real_escape_string($conn, $activityname_array[$i]);
			$startDate = mysqli_real_escape_string($conn, $startDate_array[$i]);
			$endDate = mysqli_real_escape_string($conn, $endDate_array[$i]);
			$organizedby = mysqli_real_escape_string($conn, $organizedby_array[$i]);
			$purposeofactivity = mysqli_real_escape_string($conn, $purposeofactivity_array[$i]);
			$event = mysqli_real_escape_string($conn, $event_array[$i]);
			$level = mysqli_real_escape_string($conn, $level_array[$i]);

			$time = time();
			$start = new DateTime(date($startDate, $time));
			$end = new DateTime(date($endDate, $time));
			$days = date_diff($start, $end);
			$noofdays = $days->format('%d');
			$month = $start->format('n');
			$year = $start->format('Y');

			$activityname = validateFormData($activityname);
			$activityname = "'" . $activityname . "'";

			$organizedby = validateFormData($organizedby);
			$organizedby = "'" . $organizedby . "'";

			$purposeofactivity = validateFormData($purposeofactivity);
			$purposeofactivity = "'" . $purposeofactivity . "'";

			$startDate = validateFormData($startDate);
			$startDate = "'" . $startDate . "'";

			$endDate = validateFormData($endDate);
			$endDate = "'" . $endDate . "'";

			$event = validateFormData($event);
			$event = "'" . $event . "'";

			$level = validateFormData($level);
			$level = "'" . $level . "'";

			if ($startDate > $endDate) {
				$nameError = $nameError . "Start date cannot be greater than end date<br>";
				$error = "Start date cannot be greater than end date";
				$s = 0;
				$flag = 0;
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
					if (isset($_FILES['paper']) && $_FILES['paper']['name'] != NULL && $_FILES['paper']['name'] != "") {
						$errors = array();
						$fileName = $_FILES['paper']['name'];
						$fileSize = $_FILES['paper']['size'];
						$fileTmp = $_FILES['paper']['tmp_name'];
						$fileType = $_FILES['paper']['type'];
						$temp = explode('.', $fileName);
						$fileExt = strtolower(end($temp));
						date_default_timezone_set('Asia/Kolkata');
						$targetName = $datapath . "papers/" . $_SESSION['F_NAME'] . "_papers_" . date("d-m-Y H-i-s", time()) . "." . $fileExt;

						if (empty($errors) == true) {
							if (file_exists($targetName)) {
								unlink($targetName);
							}
							$moved = move_uploaded_file($fileTmp, "$targetName");
							if ($moved == true) {
								$paperpath = $targetName;
								$success = 1;
							}
							//	 else{
							//not successful
							//header("location:error.php");
							//		 			 echo "<h1> $targetName </h1>";
							//	 }
						} else {
							print_r($errors);
							//header("location:else.php");
						}
					} else {
						$s = 0;
						$error3 = "No file selected";
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
								$certipath = $targetName;
								$success = 1;
							}
							//	 else{
							//		 			 echo "<h1> $targetName </h1>";
							//	 }
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
							}
							//	 else{
							//		 			 echo "<h1> $targetName </h1>";
							//	 }
						} else {
							print_r($errors);
						}
					} else {
						$s = 0;
						$error2 = "No file selected";
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
				$sql = "INSERT INTO co_curricular(Fac_ID,activity_name,Date_from,Date_to,purpose_of_activity,organized_by,certificate_path,event,month,year,level) VALUES ('$author',$activityname,$startDate,$endDate,$purposeofactivity,$organizedby,'" . $certipath . "',$event,$month,$year,$level)";
				echo $sql;
				if ($conn->query($sql) === TRUE) {
					$success = 1;
					header("location:2_dashboard_cocurricular.php?alert=success");
				} else if ($s != 0) {
					header("location:2_dashboard_cocurricular.php?alert=error");
				}
				else if ($s==0) {
					echo "<script>alert('$error')</script>";
				}
			}
		}
	}


	//close the connection
	mysqli_close($conn);
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
		width: 410px;
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
							<!-- <i style="font-size:20px" class="fa fa-trophy"></i> -->
							<h3 class="box-title"><b>Faculty Awards/Prizes/Recognition Won Form</b></h3>
							<br>
						</div>
					</div><!-- /.box-header -->
					<!-- form start -->

					<?php

					for ($k = 0; $k < 1; $k++) {

					?>
						<form role="form" method="POST" class="row" action="" style="margin:10px;" enctype="multipart/form-data">

							<?php
							if ($flag == 0) {
								echo '<div class="error">' . $nameError . '</div>';
							}
							?>

							<div class="form-group col-md-6">
								<label for="department_name">Department Name</label>
								<input required type="text" class="form-control input-lg" id="department_name" name="department_name[]" value="<?php echo strtoupper($_SESSION['Dept']); ?>" readonly>
							</div>


							<div class="form-group col-md-6">
								<label for="faculty-name">Faculty Name</label>
								<input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName[]" value="<?php echo $faculty_name; ?>" readonly>
							</div>

							<div class="form-group col-md-6">
								<label for="title">Title of Project/Thesis/Award/Recognition </label>
								<span class="colour"><b> *</b></span>
								<input type="text" required class="form-control input-lg" id="title" name="title[]" placeholder="" value="">
							</div>

							<div class="form-group col-md-6">
								<label for="title_book">Name of the Event</label>
								<span class="colour"><b> *</b></span>
								<input type="text" required class="form-control input-lg" id="title_book" name="title_book[]" placeholder="" value="">
							</div>

							<div class="form-group col-md-6">
								<label for="name_of_institute">Name of Institute/Organisation</label>
								<span class="colour"><b> *</b></span>
								<input type="text" required class="form-control input-lg" id="name_of_institute" name="name_of_institute[]" placeholder="" value="">
							</div>

							<div class="form-group col-md-6">
								<label for="level">Level of Activity </label>
								<span class="colour"><b> *</b></span>
								<select required name="status_activities[]" id="status_activities" class="form-control input-lg">
									<option value="" disabled selected>Select your option:</option>
									<option name="college" value="college">College</option>
									<option name="national" value="national">National</option>
									<option name="international" value="international">International</option>
								</select>
							</div>

							<div class="form-group col-md-6">
								<label for="start-date">Start Date</label>
								<span class="colour"><b> *</b></span>
								<input <?php if (isset($_POST['startdate'])) echo "value = $startDate"; ?> required type="date" class="form-control input-lg" id="start-date" name="startdate[]" placeholder="03:10:10">
							</div><br><br>

							<div class="form-group col-md-6">
								<label for="end-date">End Date</label>
								<span class="colour"><b> *</b></span>
								<input <?php if (isset($_POST['enddate'])) echo "value = $endDate"; ?> required type="date" class="form-control input-lg" id="end-date" name="enddate[]" placeholder="03:10:10">
							</div><br><br>

							<div class="form-group col-md-6">
								<label for="details_of_prize">Details of the Prize/Rank Won</label>
								<span class="colour"><b> *</b></span>
								<input type="text" required class="form-control input-lg" id="details_of_prize" name="details_of_prize[]" placeholder="" value="">
							</div>

							<div class="form-group col-md-12">

								<div>
									&nbsp;<label for="course">Upload certificate: Applicable?<br></label><span class="colour"><b> *</b></span>
									<span class="error" style="border : none;"> <?php echo $error1 ?> </span>
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

							<?php
						}
							?>
							<br />
							<div class="form-group col-md-12">
								<a href="2_dashboard_cocurricular.php" type="button" class="btn btn-warning btn-lg">Cancel</a>

								<button name="add" type="submit" class="btn btn-success pull-right btn-lg">Submit</button>
							</div>
						</form>
				</div>
			</div>
		</div>
	</section>


</div>




<?php include_once('footer.php'); ?>