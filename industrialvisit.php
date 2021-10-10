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
$_SESSION['currentTab'] = "iv";
$fid = $_SESSION['Fac_ID'];

//connect ot database
include_once("includes/connection.php");

//include custom functions files 
include_once("includes/functions.php");
include_once("includes/scripting.php");

//include config file
include_once("includes/config.php");

//setting error variables

$ind = $city = $purpose = $audience = $staff = $permissionpath = $certipath = $reportpath = $attendancepath = $startdate = $enddate = $ivtype = $details = "";
$part = $month = $year = 0;
$flag = 1;
$nameError = "";
$success = 0;
$s = 1;
$p_id = 0;
$error1 = $error2 = $error3 = $error4 = "";
$fid = $_SESSION['Fac_ID'];
$count1 = 1;
$faculty_name = $_SESSION['loggedInUser'];

$query2 = "SELECT * from facultydetails where Fac_ID = $fid";
$result2 = mysqli_query($conn, $query2);
if ($result2) {
	$row = mysqli_fetch_assoc($result2);
	$F_NAME = $row['F_NAME'];
	$_SESSION['Dept'] = $row['Dept'];
	$_SESSION['type'] = $row['type'];
	$_SESSION['F_NAME'] = $row['F_NAME'];
}

//check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST['submit'])) {
		$ind = $_POST['name_of_industry'];
		$city = $_POST['loc_of_industry'];
		// $purpose = $_POST['purpose'];
		$resource = $_POST['res_person'];
		$audience = $_POST['target_audience'];
		$startDate_array = $_POST['startDate'];
		$endDate_array = $_POST['endDate'];
		$part = $_POST['part'];
		// $details = $_POST['details'];
		// $ivtype = $_POST['ivtype'];

		for ($i = 0; $i < 1; $i++) {
			$startDate = mysqli_real_escape_string($conn, $startDate_array[$i]);
			$endDate = mysqli_real_escape_string($conn, $endDate_array[$i]);
		}
		$time = time();
		$start = new DateTime(date($startDate));
		$end = new DateTime(date($endDate));
		$days = date_diff($start, $end);
		// $interval = $start->diff($end);
		// $noofdays = $interval->days;
		$noofdays = $days->format('%d');
		$noofweeks = $noofdays / 7;
		$month = $start->format('n');
		$year = $start->format('Y');

		if ($start > $end) {
			$nameError = $nameError . "Start date cannot be greater than end date<br>";
			$error = "Start date cannot be greater than end date";
			$s = 0;
			$flag = 0;
		}

		if (isset($_POST['applicable'])) {
			// console.log($_POST['applicable']);
			if ($_POST['applicable'] == 2) {
				$permissionpath = 'NULL';
			} else if ($_POST['applicable'] == 3) {
				$permissionpath = 'not_applicable';
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
					$targetName = $datapath . "permissions/" . $_SESSION['F_NAME'] . "_permissions_" . date("d-m-Y H-i-s", time()) . "." . $fileExt;

					if (empty($errors) == true) {
						if (file_exists($targetName)) {
							unlink($targetName);
						}
						$moved = move_uploaded_file($fileTmp, "$targetName");
						if ($moved == true) {
							$permissionpath = $targetName;
						}
						//	 else{
						//		 echo "<h1> $targetName </h1>";
						//	 }
					} else {
						print_r($errors);
						//header("location:else.php");
					}
				} else {
					$s = 0;
					$error4 = "No file selected";
				}
			}
		}

		if (isset($_POST['applicable1'])) {
			if ($_POST['applicable1'] == 2) {
				$certipath = 'NULL';
			} else if ($_POST['applicable1'] == 3) {
				$certipath = 'not_applicable';
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
						}
						// else{
						//		echo "<h1> $targetName </h1>";
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
			} else if ($_POST['applicable2'] == 3) {
				$reportpath = 'not_applicable';
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
						}
						//		 else{
						//		 echo "<h1> $targetName </h1>";
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
		if (isset($_POST['applicable3'])) {
			if ($_POST['applicable3'] == 2) {
				$attendancepath = 'NULL';
			} else if ($_POST['applicable3'] == 3) {
				$attendancepath = 'not_applicable';
			} else if ($_POST['applicable3'] == 1) {
				if (isset($_FILES['attendance']) && $_FILES['attendance']['name'] != NULL && $_FILES['attendance']['name'] != "") {
					$errors = array();
					$fileName = $_FILES['attendance']['name'];
					$fileSize = $_FILES['attendance']['size'];
					$fileTmp = $_FILES['attendance']['tmp_name'];
					$fileType = $_FILES['attendance']['type'];
					$temp = explode('.', $fileName);
					$fileExt = strtolower(end($temp));
					date_default_timezone_set('Asia/Kolkata');
					$targetName = $datapath . "attendance/" . $_SESSION['F_NAME'] . "_attendance_" . date("d-m-Y H-i-s", time()) . "." . $fileExt;

					if (empty($errors) == true) {
						if (file_exists($targetName)) {
							unlink($targetName);
						}
						$moved = move_uploaded_file($fileTmp, "$targetName");
						if ($moved == true) {
							$attendancepath = $targetName;
						} //else{
						//		 echo "<h1> $targetName </h1>";
						//	 }
					} else {
						print_r($errors);
					}
				} else {
					$s = 0;
					$error3 = "No file selected";
				}
			}
		}
		$coauthorname = "";
		if (isset($_POST["co_name"])) {
			for ($count2 = 0; $count2 < count($_POST["co_name"]); $count2++) {
				$co_name = $_POST["co_name"][$count2];
				if ($coauthorname == "") {
					$coauthorname = $co_name;
				} else {
					$coauthorname = $coauthorname . ", " . $co_name;
				}
			}
			if ($coauthorname == "") {
				$coauthorname = 'NA';
			}
		}
	}
	// if ($details == "") {
	// 	$details = 'NA';
	// }
	if ($flag != 0 && $s != 0) {
		$sql = "INSERT INTO iv_organized(f_id,ind,city,t_audience,permission,certificate,report,attendance,t_from,t_to,part,staff,noofdays,noofweeks,month,year,resource) VALUES($fid,'$ind','$city', '$audience','" . $permissionpath . "','" . $certipath . "','" . $reportpath . "','" . $attendancepath . "','$startDate','$endDate','$part','$coauthorname',$noofdays,$noofweeks,$month,$year,'$resource')";
		echo $sql;
		if ($conn->query($sql) === TRUE) {
			header("location:2_dashboard_iv.php?alert=success");
		} else if ($s != 0) {
			header("location:2_dashboard_iv.php?alert=error");
		}
		else if ($s==0) {
			echo "<script>alert('$error')</script>";
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
			<div class="col-md-8 " id="form">
				<!-- general form elements -->
				<br /><br /><br />

				<div class="box box-primary">
					<div class="box-header with-border">
						<div class="icon">
							<i style="font-size:20px" class="fa fa-edit"></i>
							<h3 class="box-title"><b>Industrial Visit Organised</b></h3>
							<br>
						</div>
					</div><!-- /.box-header -->
					<div style="text-align:right">
					</div>
					<!-- form start -->

					<?php

					for ($k = 0; $k < 1; $k++) {
					?>

						<form id="insert_form" role="form" method="POST" enctype="multipart/form-data" class="row" action="" style="margin:10px;">

						<?php
							if ($flag == 0) {
								echo '<div class="error">' . $nameError . '</div>';
							}
							?>
							<div class="form-group col-md-6">
								<label for="department_name">Department Name</label>
								<input required type="text" class="form-control input-lg" id="department_name" name="department_name" value="<?php echo strtoupper($_SESSION['Dept']); ?>" readonly>
							</div>

							<div class="form-group col-md-6">
								<label for="Organiser">Organiser</label>
								<input required type="text" class="form-control input-lg" id="Organiser" name="Organiser" value="<?php echo $faculty_name; ?>" readonly>
							</div>


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
							</div>

							<div class="form-group col-md-6">
								<label for="name_of_industry">Name of Industry </label><span class="colour"><b> *</b></span>
								<!--   <input required type="text" class="form-control input-lg" id="paper-title" name="paperTitle">-->
								<input required type="text" class="form-control input-lg" placeholder="Company/Field/Institue" name="name_of_industry" value='<?php if (isset($_POST['ind'])) echo $ind; ?>'>
							</div>
							<div class="form-group col-md-6">
								<label for="loc_of_industry">Location of Indutry</label><span class="colour"><b> *</b></span>
								<input required type="text" name="loc_of_industry" class="form-control input-lg" placeholder="Company/Field/Institue" value='<?php if (isset($_POST['city'])) echo $city; ?>'>
							</div>

							<div class="form-group col-md-6">
								<label for="start-date">Start Date </label><span class="colour"><b> *</b></span>
								<input <?php if (isset($_POST['startDate'])) echo "value = $startDate"; ?> required type="date" class="form-control input-lg" id="start-date" name="startDate[]" placeholder="03:10:10">
							</div>

							<div class="form-group col-md-6">
								<label for="end-date">End Date </label><span class="colour"><b> *</b></span>
								<input <?php if (isset($_POST['endDate'])) echo "value = $endDate"; ?> required type="date" class="form-control input-lg" id="end-date" name="endDate[]" placeholder="03:10:10">
							</div>

							<!-- <div class="form-group col-md-6">
								<label for="days/weeks/hours">Days/Weeks/Hours </label>
								<span class="colour"><b> *</b></span>
								<select required name="status_activities[]" id="status_activities" class="form-control input-lg">
									<option value="" disabled selected>Select your option:</option>
									<option name="hours" value="hours">Hours</option>
									<option name="days" value="days">Days</option>
									<option name="weeks" value="weeks">Weeks</option>
								</select>
							</div>

							<div class="form-group col-md-6">
								<label for="no_of_hours">Equivalent duration</label>
								<span class="colour"><b> *</b></span>
								<input class="form-control input-lg" type="text" name="no_of_hours[]" id="no_of_hours" placeholder="" value="">
							</div> -->


							<div class="form-group col-md-6">
								<label for="res_person">Name of Resource Person</label><span class="colour"><b> *</b></span>
								<input type="text" name="res_person" class="form-control input-lg" value='<?php if (isset($_POST['purpose'])) echo $purpose; ?>'>
							</div>

							<div class="form-group col-md-6">
								<label for="target_audience">Target Audience </label><span class="colour"><b> *</b></span>
								<input type="text" name="target_audience" class="form-control input-lg" value='<?php if (isset($_POST['audience'])) echo $audience; ?>'>
							</div>

							<div class="form-group col-md-6">
								<label for="part">Number of Participants</label><span class="colour"><b> *</b></span>
								<input required type="number" class="form-control input-lg" name="part" value='<?php if (isset($_POST['part'])) echo $part; ?>'>
							</div>

							<!-- <div class="form-group col-md-6">
                         <label>IV Type</label><span class="colour"><b> *</b></span>
                         <select name="ivtype" class="form-control input-lg">
                         	<option value="Sponsored" <?php if (isset($_POST['ivtype'])) if ($_POST['ivtype'] == "Sponsored") echo "checked"; ?>>Sponsored</option>
                         	<option value="Not Sponsored" <?php if (isset($_POST['ivtype'])) if ($_POST['ivtype'] == 'Not Sponsored') echo "checked"; ?>'>Not Sponsored</option>
                         </select>
                     </div>

                     <div class="form-group col-md-6">
                         <label for="end-date">Extra Details </label><span class="colour"><b> *</b></span>
                         <input type="text" class="form-control input-lg" name="details" value = '<?php if (isset($_POST['details'])) echo $details; ?>'>
                     </div>

                     <div class="form-group col-md-6">
                     	<br>
						 <label for="c-name">Select Staff </label><span class="colour"><b> *</b></span>
							    <div class="table-repsonsive">
							     <table class="table table-bordered" id="c_name">
							      <tr>
							       <th><label>Click to Select</label></th>
							       <th><button type="button" name="add" class="btn btn-success btn-sm add"><span class="glyphicon glyphicon-plus"></span></button></th>
							      </tr>
							     </table>
							    </div>
                     </div> -->


							<div class="form-group col-md-6 col-md-offset-1"></div>

							<div class="form-group col-md-6">

								<div>

									&nbsp;<label for="course">Upload Permission: Applicable?<br></label><span class="colour"><b> *</b></span>
									<span class="error" style="border : none;"> <?php echo $error4 ?> </span>
									<br> &nbsp;<input required type='radio' name='applicable' class='non-vac' value='1' <?php if (isset($_POST['applicable'])) echo ($_POST['applicable'] == "1") ? 'checked' : '' ?>> Yes <br>
									&nbsp;<input type='radio' name='applicable' class='vac' value='2' <?php if (isset($_POST['applicable'])) echo ($_POST['applicable'] == "2") ? 'checked' : '' ?>> Applicable, but not yet available <br>

									&nbsp;<input type='radio' name='applicable' class='vac' value='3' <?php if (isset($_POST['applicable'])) echo ($_POST['applicable'] == "3") ? 'checked' : '' ?>> No <br>
								</div>
								<br>
								<div class='second-reveal' <?php if (isset($_POST['applicable'])) echo ($_POST['applicable'] == "1") ? 'style = "display : block" ' : '' ?>>
									<div>

										<label for="card-image">Permission </label><span class="colour"><b> *</b></span>
										<input type="file" class="form-control input-lg" id="card-image" name="paper">
									</div>
								</div>

								<div>

									&nbsp;<label for="course">Upload Certificate: Applicable?<br></label><span class="colour"><b> *</b></span>
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

								<div>

									&nbsp;<label for="course">Upload Report: Applicable?<br></label><span class="colour"><b> *</b></span>
									<span class="error" style="border : none;"> <?php echo $error2 ?> </span>
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

								<div>

									&nbsp;<label for="course">Upload Attendance: Applicable?<br></label><span class="colour"><b> *</b></span>
									<span class="error" style="border : none;"> <?php echo $error3 ?> </span>
									<br> &nbsp;<input required type='radio' name='applicable3' class='non-vac4' value='1' <?php if (isset($_POST['applicable3'])) echo ($_POST['applicable3'] == "1") ? 'checked' : '' ?>> Yes <br>
									&nbsp;<input type='radio' name='applicable3' class='vac4' value='2' <?php if (isset($_POST['applicable3'])) echo ($_POST['applicable3'] == "2") ? 'checked' : '' ?>> Applicable, but not yet available <br>

									&nbsp;<input type='radio' name='applicable3' class='vac4' value='3' <?php if (isset($_POST['applicable3'])) echo ($_POST['applicable3'] == "3") ? 'checked' : '' ?>> No <br>
								</div>
								<br>
								<div class='second-reveal4' <?php if (isset($_POST['applicable3'])) echo ($_POST['applicable3'] == "1") ? 'style = "display : block" ' : '' ?>>
									<div>

										<label for="card-image">Attendance </label><span class="colour"><b> *</b></span>
										<input type="file" class="form-control input-lg" id="card-image" name="attendance">
									</div>
								</div>
							</div>
						<?php
					}
						?>
						<br />
						<div class="form-group col-md-12">
							<a href="list_of_activities_user.php" type="button" class="demo btn btn-warning btn-lg">Cancel</a>

							<button name="submit" type="submit" class="demo btn btn-success pull-right btn-lg">Submit</button>
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