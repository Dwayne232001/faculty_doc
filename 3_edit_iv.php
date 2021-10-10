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

$fid = $_SESSION['Fac_ID'];

$queryrun = "SELECT * FROM facultydetails where Fac_ID=$fid";
$resultrun = mysqli_query($conn, $queryrun);
while ($row = mysqli_fetch_assoc($resultrun)) {
	$_SESSION['Dept'] = $row['Dept'];
	$_SESSION['type'] = $row['type'];
}

//include custom functions files 
include_once("includes/functions.php");
include_once("includes/scripting.php");

//include config file
include_once("includes/config.php");

//setting error variables
$attendancepath = $reportpath = $certipath = $permissionpath = "";
$part = 0;
$success = 0;
$fid = $_SESSION['Fac_ID'];
$count1 = 1;
$faculty_name = $_SESSION['loggedInUser'];
if (isset($_POST['id'])) {
	$id = $_POST['id'];
	$_SESSION['id'] = $_POST['id'];
}
$id = $_SESSION['id'];
$query = "SELECT * FROM iv_organized where id=$id ";
$res = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($res);
$ind = $row['ind'];
$city = $row['city'];
$purpose = $row['purpose'];
$audience = $row['t_audience'];
$startdate = $row['t_from'];
$enddate = $row['t_to'];
$resource = $row['resource'];
$part = $row['part'];
$details = $row['details'];
$ivtype = $row['ivtype'];
$certipath = $row['certificate'];
$permissionpath = $row['permission'];
$reportpath = $row['report'];
$attendancepath = $row['attendance'];
$staff = $row['staff'];
$Fac_ID = $row['f_id'];
$query2 = "SELECT * from facultydetails where Fac_ID = '$Fac_ID'";
$result2 = mysqli_query($conn, $query2);
if ($result2) {
	$row = mysqli_fetch_assoc($result2);
	$F_NAME = $row['F_NAME'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST['update'])) {
		$id = $_SESSION['id'];
		$ind = $_POST['ind'];
		$city = $_POST['city'];
		$audience = $_POST['audience'];
		$startdate = $_POST['startdate'];
		$enddate = $_POST['enddate'];
		$part = $_POST['part'];
		$staff = $_POST['staff'];

		$time = time();
		$start = new DateTime(date($startdate, $time));
		$end = new DateTime(date($enddate, $time));
		$days = date_diff($start, $end);
		$noofdays = $days->format('%d');

		if (isset($_POST['applicable'])) {
			// console.log($_POST['applicable']);
			if ($_POST['applicable'] == 2) {
				$permissionpath = 'NULL';
			} else if ($_POST['applicable'] == 3) {
				$permissionpath = 'not_applicable';
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
							$permissionpath = $targetName;
						} else {
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
			} else if ($_POST['applicable1'] == 3) {
				$certipath = 'not_applicable';
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
			} else if ($_POST['applicable2'] == 3) {
				$reportpath = 'not_applicable';
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
				$attendancepath = 'NULL';
			} else if ($_POST['applicable3'] == 3) {
				$attendancepath = 'not_applicable';
			} else if ($_POST['applicable3'] == 1) {
				if (isset($_FILES['attendance'])) {
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
						} else {
							echo "<h1> $targetName </h1>";
						}
					} else {
						print_r($errors);
					}
				}
			}
		}
		if ($details == "") {
			$details = 'NA';
		}
		$success = 0;
		$sql = "UPDATE iv_organized SET 
							ind='$ind',
							city='$city',
							purpose='$purpose',
							t_audience='$audience',
							permission='" . $permissionpath . "',
							certificate='" . $certipath . "',
							report='" . $reportpath . "',
							attendance='" . $attendancepath . "',
							t_from='$startdate',
							t_to='$enddate',
							resource = '$resource',
							part='$part',
							staff='$staff',
							noofdays = $noofdays,
							tdate=now()
						WHERE id='" . $_SESSION['id'] . "' ";
		$result1 = mysqli_query($conn, $sql);
		echo $sql;
		if ($result1 == true) {
			if ($_SESSION['type'] == 'hod') {
				header("location:2_dashboard_iv_hod.php?alert=success");
			} else {
				header("location:2_dashboard_iv.php?alert=success");
			}
		} else {
			if ($_SESSION['type'] == 'hod') {
				header("location:2_dashboard_iv_hod.php?alert=error");
			} else {
				header("location:2_dashboard_iv.php?alert=error");
			}
		}
	}
}

//close the connection
mysqli_close($conn);

function fill_unit_select_box($connect)
{
	$output = '';
	$query = "SELECT * FROM facultydetails ORDER BY F_NAME ASC";
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
</style>
<div class="content-wrapper">

	<section class="content">
		<div class="row">
			<!-- left column -->
			<div class="col-md-8 ">
				<!-- general form elements -->
				<br /><br /><br />

				<div class="box box-primary">
					<div class="box-header with-border">
						<div class="icon">
							<i style="font-size:20px" class="fa fa-edit"></i>
							<h3 class="box-title"><b>Industrial Visit Form</b></h3>
							<br>
						</div>
					</div><!-- /.box-header -->
					<div style="text-align:right">
						<!--	<a href="menu.php?menu=1 " style="text-align:right"> <u>Back to Paper Publication/Presentation Menu</u>&nbsp &nbsp </a> -->
					</div>
					<!-- form start -->

					<?php

					for ($k = 0; $k < 1; $k++) {
					?>

						<form id="insert_form" role="form" method="POST" enctype="multipart/form-data" class="row" action="" style="margin:10px;">
							<div class="form-group col-md-6">
								<label for="department_name">Department Name</label>
								<input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $_SESSION['Dept']; ?>" readonly>
							</div>
							<div class="form-group col-md-6">
								<label for="faculty-name">Faculty Name</label>
								<input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $faculty_name; ?>" readonly>
							</div>
							<div class="form-group col-md-6">
								<label for="paper-title">Industry </label><span class="colour"><b> *</b></span>
								<!--   <input required type="text" class="form-control input-lg" id="paper-title" name="paperTitle">-->
								<input required type="text" class="form-control input-lg" name="ind" value="<?php echo "$ind"; ?>">
							</div>
							<div class="form-group col-md-6">
								<label>Location of Industry</label><span class="colour"><b> *</b></span>
								<input required type="text" name="city" class="form-control input-lg" value="<?php echo "$city"; ?>">
							</div>
							<div class="form-group col-md-6">
								<label>Resource Person</label><span class="colour"><b> *</b></span>
								<input required type="text" name="resource" class="form-control input-lg" value="<?php echo "$resource"; ?>">
							</div>

							<div class="form-group col-md-6">
								<label for="conf">Audience </label><span class="colour"><b> *</b></span>
								<input required type="text" name="audience" class="form-control input-lg" value="<?php echo "$audience"; ?>">
							</div>

							<div class="form-group col-md-6">
								<label for="start-date">Start Date </label><span class="colour"><b> *</b></span>
								<input <?php echo "value = $startdate"; ?> required type="date" class="form-control input-lg" id="start-date" name="startdate" placeholder="03:10:10">
							</div>

							<div class="form-group col-md-6">
								<label for="end-date">End Date </label><span class="colour"><b> *</b></span>
								<input <?php echo "value = $enddate"; ?> required type="date" class="form-control input-lg" id="end-date" name="enddate" placeholder="03:10:10">
							</div>

							<div class="form-group col-md-6">
								<label>No of participants</label><span class="colour"><b> *</b></span>
								<input required type="number" <?php echo "value = $part"; ?> class="form-control input-lg" name="part">
							</div>

							<!-- <div class="form-group col-md-6">
								<label>IV Type</label><span class="colour"><b> *</b></span>
								<select name="ivtype" class="form-control input-lg">
									<option value="Sponsored" <?php if ($ivtype == 'Sponsored') {
																	echo "checked";
																} ?>>Sponsored</option>
									<option value="Not Sponsored" <?php if ($ivtype == 'Not Sponsored') {
																		echo "checked";
																	} ?>>Not Sponsored</option>
								</select>
							</div> -->

							<!-- <div class="form-group col-md-6">
								<label for="end-date">Extra Details </label><span class="colour"><b> *</b></span>
								<input type="text" class="form-control input-lg" name="details" value="<?php echo $details ?>">
							</div> -->

							<div class="form-group col-md-6">
								<label for="end-date">Staff </label><span class="colour"><b> *</b></span>
								<input type="text" class="form-control input-lg" name="staff" value="<?php echo $staff ?>">
							</div>

							<div class="form-group col-md-6 col-md-offset-1"></div>

							<div class="form-group col-md-6">
								<div>
									<label for="Index">Permission : </label><br />
									<input type="radio" name="applicable" id="r1" value="1" class="non-vac" <?php echo ($permissionpath != NULL) ? 'checked' : '' ?>>Yes<br>
									<input type="radio" name="applicable" value="2" class="vac" <?php echo ($permissionpath == 'NULL') ? 'checked' : '' ?>>Applicable, but not yet available <br>
									<input type="radio" name="applicable" value="3" class="vac" <?php echo ($permissionpath == 'not_applicable') ? 'checked' : '' ?>> No
								</div>
								<br>
								<div class='second-reveal' id='f1'>
									<div>

										<label for="card-image">Permission Letter </label><span class="colour"><b> *</b></span>
										<input type="file" class="form-control input-lg" id="card-image" name="paper">
										<a <?php
											$f = 0;
											if ($permissionpath != "not_applicable" && $permissionpath != "NULL" && $permissionpath != 'no status' && $permissionpath != "") {
												echo "href='$permissionpath'";
												$f = 1;
											} else {
												echo "style='display:none' ";
											}
											?> target="_blank">
											<h4><?php if ($f == 1) {
													echo "View Existing Permission Letter";
												} ?><h4>
										</a>
									</div>
								</div>

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
												} ?><h4>
										</a>
									</div>
								</div>

								<div>
									<label for="Index">Attendance : </label><br />
									<input type="radio" name="applicable3" id="r4" value="1" class="non-vac4" <?php echo ($attendancepath != NULL) ? 'checked' : '' ?>>Yes<br>
									<input type="radio" name="applicable3" value="2" class="vac4" <?php echo ($attendancepath == 'NULL') ? 'checked' : '' ?>>Applicable, but not yet available <br>
									<input type="radio" name="applicable3" value="3" class="vac4" <?php echo ($attendancepath == 'not_applicable') ? 'checked' : '' ?>> No

								</div>
								<br>
								<div class='second-reveal4' id='f4'>
									<div>

										<label for="card-image">Attendance </label><span class="colour"><b> *</b></span>
										<input type="file" class="form-control input-lg" id="card-image" name="attendance">
										<a <?php
											$f4 = 0;
											if ($attendancepath != "not_applicable" && $attendancepath != "NULL" && $attendancepath != 'no status' && $attendancepath != "") {
												echo "href='$attendancepath'";
												$f4 = 1;
											} else {
												echo "";
											}
											?> target="_blank">
											<h4><?php if ($f4 == 1) {
													echo "View Existing Attendance";
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
									mycheck4();
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
							</script>
						<?php
					}
						?>
						<br />
						<div class="form-group col-md-12">
							<?php
							if ($_SESSION['type'] == 'hod') {
								echo '<a href="2_dashboard_iv_hod.php" type="button" class="demo btn btn-warning btn-lg">Cancel</a>';
							} else {
								echo '<a href="2_dashboard_iv.php" type="button" class="demo btn btn-warning btn-lg">Cancel</a>';
							}
							?>


							<button name="update" type="submit" class="demo btn btn-success pull-right btn-lg">Submit</button>
						</div>
						</form>
				</div>
			</div>
		</div>
	</section>
</div>

<?php include_once('footer.php'); ?>