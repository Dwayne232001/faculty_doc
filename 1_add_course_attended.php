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
$type_of_course = $status_of_activity = $duration = $credit_audit = $course = $startDate = $endDate = $organised = $purpose = $certipath = $reportpath = "";
$flag = 1;
$success = 0;
$fid = $_SESSION['Fac_ID'];
$s = 1;
$p_id = 0;
$error1 = $error2 = "";

$queryrun = "SELECT * FROM facultydetails where Fac_ID=$fid";
$resultrun = mysqli_query($conn, $queryrun);
while ($row = mysqli_fetch_assoc($resultrun)) {
	$_SESSION['Dept'] = $row['Dept'];
	$_SESSION['type'] = $row['type'];
	$faculty_name = $row['F_NAME'];
	$deptName = $row['Dept'];
}
$faculty_name = $_SESSION['loggedInUser'];
$_SESSION['F_NAME'] = $faculty_name;
$_SESSION['currentTab'] = "Online";
//check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (isset($_POST['add'])) {

		//the form was submitted

		$course_array = $_POST['course'];
		$startDate_array = $_POST['startDate'];
		$endDate_array = $_POST['endDate'];
		$organised_array = $_POST['organised'];
		// $purpose_array = $_POST['purpose'];
		// $applicablefdc_array = $_POST['applicablefdc'];

		// $fdc_array = $_POST['fdc'];

		// /*  MY CODE */
		// $type_of_course_array = $_POST['type'];
		// $status_of_activity_array = $_POST['status'];
		// $duration_array = $_POST['duration'];
		$credit_audit_array = $_POST['creau'];


		//check for any blank input which are required

		for ($i = 0; $i < 1; $i++) {
			$course = mysqli_real_escape_string($conn, $course_array[$i]);

			$startDate = mysqli_real_escape_string($conn, $startDate_array[$i]);
			$endDate = mysqli_real_escape_string($conn, $endDate_array[$i]);
			$organised = mysqli_real_escape_string($conn, $organised_array[$i]);
			// $purpose = mysqli_real_escape_string($conn, $purpose_array[$i]);

			// /*  MY CODE */
			// $type_of_course = mysqli_real_escape_string($conn, $type_of_course_array[$i]);
			// $status_of_activity = mysqli_real_escape_string($conn, $status_of_activity_array[$i]);
			// $duration = mysqli_real_escape_string($conn, $duration_array[$i]);
			$credit_audit = mysqli_real_escape_string($conn, $credit_audit_array[$i]);

			// $applicablefdc = mysqli_real_escape_string($conn, $applicablefdc_array[$i]);

			$time = time();
			$start = new DateTime(date($startDate, $time));
			$end = new DateTime(date($endDate, $time));
			$days = date_diff($start, $end);
			$noofdays = $days->format('%d');
			$month = $start->format('n');
			$year = $start->format('Y');
			$noofweeks = $noofdays / 7;

			// $fdc = mysqli_real_escape_string($conn, $fdc_array[$i]);
			// $_SESSION['fdc'] = $fdc;


			$course = validateFormData($course);
			$course = "'" . $course . "'";

			$startDate = validateFormData($startDate);
			$startDate = "'" . $startDate . "'";


			$endDate = validateFormData($endDate);
			$endDate = "'" . $endDate . "'";


			if ($startDate > $endDate) {
				$nameError = $nameError . "Start date cannot be greater than end date<br>";
				$error = "Start date cannot be greater than end date";
				$s = 0;
				$flag = 0;
			}


			// $purpose = validateFormData($purpose);
			// $purpose = "'" . $purpose . "'";

			// $type_of_course = validateFormData($type_of_course);
			// $type_of_course = "'" . $type_of_course . "'";

			// $status_of_activity = validateFormData($status_of_activity);
			// $status_of_activity = "'" . $status_of_activity . "'";

			$organised = validateFormData($organised);
			$organised = "'" . $organised . "'";

			// $duration = validateFormData($duration);
			// $duration = "'" . $duration . "'";

			$credit_audit = validateFormData($credit_audit);
			$credit_audit = "'" . $credit_audit . "'";



			// if ($applicablefdc == 'Yes') {
			// 	$fdc = 'Yes';
			// 	$fdc = "'" . $fdc . "'";
			// } else if ($applicablefdc == 'No') {
			// 	$fdc = 'Not applicable';
			// 	$fdc = "'" . $fdc . "'";
			// }
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
							// else{
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
							//	 			 echo "<h1> $targetName </h1>";
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

			$replace_str = array('"', "'", '', '');
			if (isset($_POST['purpose']))
				$purpose = str_replace($replace_str, "", $purpose);
			else
				$purpose  = '';


			if ($flag != 0 && $s != 0) {
				// MY QUERY
				$sql = "INSERT INTO online_course_attended(Fac_ID,Course_Name, Date_from, Date_to,Organised_by, noofweeks,credit_audit,certificate_path,noofdays,month,year) VALUES ('$author',$course,$startDate,$endDate,$organised,'$noofweeks',$credit_audit,'" . $certipath . "',$noofdays,$month,$year)";
				// echo $sql;
				if ($conn->query($sql) === TRUE) {
					header("location:2_dashboard_online_attended.php?alert=success");
				} 
				else if ($s==0) {
					echo "<script>alert('$error')</script>";
				}
				else {
					echo "Error: " . $sql . "<br>" . $conn->error;
					header("location:2_dashboard_online_attended.php?alert=error");
				}
			}
		} //end of for

	}
}
//close the connection
mysqli_close($conn);
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
							<h3 class="box-title"><b>Online Courses Completed Form</b></h3>
							<br>
						</div>
					</div>
					<div style="text-align:right">
					</div>
					<!-- /.box-header -->
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
							<?php
							$replace_str = array('"', "'", '', '');
							if (isset($_POST['purpose']))
								$purpose = str_replace($replace_str, "", $purpose);
							else
								$purpose  = '';

							?>
							<div class="form-group col-md-6">
								<label for="department">Department </label>
								<br><input type='text' class='form-control input-lg' name='department[]' id='department' readOnly value='<?php echo strtoupper($deptName); ?>'>
							</div>
							<div class="form-group col-md-6">
								<label for="faculty_name">Faculty</label>
								<br><input type='text' class='form-control input-lg' name='faculty_name[]' id='faculty_name' readOnly value='<?php echo $faculty_name; ?>'>
							</div>
							<!-- <div class="form-group col-md-6">
                          <label for="type">Type Of Course*</label>
                          <select required class="form-control input-lg" id="type" name="type[]">
                              <option <?php if (isset($_POST['type'])) if ($type_of_course == "online") echo "selected = 'selected'" ?> value = "online">Online</option>
                              <option <?php if (isset($_POST['type'])) if ($type_of_course == "offline") echo "selected = 'selected'" ?> value = "offline">Offline</option>
                          </select>
                      </div> -->
							<div class="form-group col-md-6">
								<label for="course">Name of Course</label><span class="colour"><b> *</b></span>
								<!--   <input required type="text" class="form-control input-lg" id="paper-title" name="course[]">-->
								<input <?php if (isset($_POST['course'])) echo "value = $course"; ?> required type="text" class="form-control input-lg" name="course[]">
							</div>


							<div class="form-group col-md-6">
								<label for="organised">Course Offered By</label><span class="colour"><b> *</b></span>
								<input <?php if (isset($_POST['organised'])) echo "value = $organised"; ?> required type="text" class="form-control input-lg" id="organised" name="organised[]">
							</div>

							<div class="form-group col-md-6">
								<label for="startDate">Duration From</label><span class="colour"><b> *</b></span>
								<input <?php if (isset($_POST['startDate'])) echo "value = $startDate"; ?> required type="date" class="form-control input-lg" id="startDate" name="startDate[]" placeholder="03:10:10">
							</div>

							<div class="form-group col-md-6">
								<label for="endDate">Duration To</label><span class="colour"><b> *</b></span>
								<input <?php if (isset($_POST['endDate'])) echo "value = $endDate"; ?> required type="date" class="form-control input-lg" id="endDate" name="endDate[]" placeholder="03:10:10">
							</div>

							<div class="form-group col-md-6">
								<label for="creau">Mode of Course</label><span class="colour"><b> *</b></span>
								<select required class="form-control input-lg" id="creau" name="creau[]">
									<option value="" disabled selected>Select your option:</option>
									<option <?php if (isset($_POST['creau'])) if ($credit_audit == "audit") echo "selected = 'selected'" ?> name="audit[]" value="audit">Audit</option>
									<option <?php if (isset($_POST['creau'])) if ($credit_audit == "credit") echo "selected = 'selected'" ?> name="credit[]" value="credit">Certificate</option>
								</select>
							</div>

							<!-- <div class="form-group col-md-6">
						
					    <label for="purpose">Purpose of Course</label><span class="colour"><b> *</b></span>
                         <input type="text" class="form-control input-lg" id="purpose" name="purpose[]" <?php if (isset($_POST['purpose'])) echo "value = $purpose"; ?>>
						</div>
                  
                      <div class="form-group col-md-6">
                          <label for="status">Status Of Activity</label><span class="colour"><b> *</b></span>
                          <select required class="form-control input-lg" id="status" name="status[]">
                            <option <?php if (isset($_POST['status_of_activity'])) if ($status_of_activity == "local") echo "selected = 'selected'" ?> name="local" value="local">Local</option>
                            <option <?php if (isset($_POST['status_of_activity'])) if ($status_of_activity == "state") echo "selected = 'selected'" ?> name="state" value="state">State</option>
                            <option <?php if (isset($_POST['status_of_activity'])) if ($status_of_activity == "national") echo "selected = 'selected'" ?> name="national" value="national">National</option>
                            <option <?php if (isset($_POST['status_of_activity'])) if ($status_of_activity == "international") echo "selected = 'selected'" ?> name="international" value="international">InterNational</option>
                        </select>
                      </div>
                      <div class="form-group col-md-6">
                          <label for="duration">Enter the duration of the course in hrs/week</label><span class="colour"><b> *</b></span>
                          <input required <?php if (isset($_POST['duration'])) echo "value = $duration"; ?> required type="text" class="form-control input-lg" id="duration" name="duration[]">
                      </div>
                      <div class="form-group col-md-6">
                          <label for="creau">Credit/Audit</label><span class="colour"><b> *</b></span>
                          <select required class="form-control input-lg" id="creau" name="creau[]">
                              <option  <?php if (isset($_POST['creau'])) if ($credit_audit == "credit") echo "selected = 'selected'" ?> value = "credit">Credit</option>
                              <option  <?php if (isset($_POST['creau'])) if ($credit_audit == "audit") echo "selected = 'selected'" ?> value = "audit">Audit</option>
                          </select>
                      </div>
					  
					  
					  
					 	 <div class="form-group col-md-6">
                         <label for="applicable-fdc">Is FDC applicable? </label><span class="colour"><b> *</b></span>
                         <select required class="form-control input-lg applicable-fdc" id="applicable-fdc" name="applicablefdc[]">
                              <option <?php if (isset($_POST['fdc'])) if ($fdc == "Not applicable") echo "selected = 'selected'" ?> value ="No">No</option>
                             <option <?php if (isset($_POST['fdc'])) if ($fdc == 'no' || $fdc == 'yes' || $fdc == 'No' || $fdc == 'Yes') echo "selected = 'selected'" ?> value ="Yes">Yes</option>
                         </select>
                     </div> -->


							<div class="form-group col-md-6 col-md-offset-1"></div>

							<div class="form-group col-md-6">
								<div>

									&nbsp;<label for="course">Upload Certificate: Applicable?<br></label><span class="colour"><b> *</b></span>
									<span class="error" style="border : none;"> <?php echo $error1 ?> </span>
									<br> &nbsp;<input required type='radio' name='applicable1' class='non-vac1' value='1' <?php if (isset($_POST['applicable1'])) echo ($_POST['applicable1'] == "1") ? 'checked' : '' ?>> Yes <br>
									&nbsp;<input type='radio' name='applicable1' class='vac1' value='2' <?php if (isset($_POST['applicable'])) echo ($_POST['applicable1'] == "2") ? 'checked' : '' ?>> Applicable, but not yet available <br>

									&nbsp;<input type='radio' name='applicable1' class='vac1' value='3' <?php if (isset($_POST['applicable'])) echo ($_POST['applicable1'] == "3") ? 'checked' : '' ?>> No <br>
								</div>
								<br>
								<div class='second-reveal1' <?php if (isset($_POST['applicable1'])) echo ($_POST['applicable1'] == "1") ? 'style = "display : block" ' : '' ?>>
									<div>
										<label for="card-image">Certificate </label><span class="colour"><b> *</b></span>
										<input type="file" class="form-control input-lg" id="card-image" name="certificate">
									</div>
								</div>
								<!-- <div >

						&nbsp;<label for="course">Upload report : Applicable ?<br></label><span class="colour"><b> *</b></span>
						<span class="error" style = "border : none;"> <?php echo $error2 ?> </span>
						<br>	&nbsp;<input required type='radio' name='applicable2' class='non-vac2' value='1'  <?php if (isset($_POST['applicable2'])) echo ($_POST['applicable2'] == "1") ? 'checked' : '' ?>	> Yes <br>
						&nbsp;<input type='radio' name='applicable2' class='vac2' value='2'  <?php if (isset($_POST['applicable2'])) echo ($_POST['applicable2'] == "2") ? 'checked' : '' ?>	> Applicable, but not yet available <br>
										 
						&nbsp;<input type='radio' name='applicable2' class='vac2' value='3'  <?php if (isset($_POST['applicable2'])) echo ($_POST['applicable2'] == "3") ? 'checked' : '' ?>	> No <br>
					</div>
					<br>
					<div class='second-reveal2' <?php if (isset($_POST['applicable2'])) echo ($_POST['applicable2'] == "1") ? 'style = "display : block" ' : '' ?>>
						 <div>
							 
                    	     <label for="card-image">Report </label><span class="colour"><b> *</b></span>
		        	         <input  type="file"   class="form-control input-lg" id="card-image" name="report">
	        	        </div> 
					</div> -->
							</div>

						<?php
					}
						?>
						<br />
						<div class="form-group col-md-12">


							<button name="add" type="submit" class="btn pull-right btn-success btn-lg">Submit</button>
							<a href="list_of_activities_user.php?" type="button" class="btn btn-warning btn-lg">Cancel</a>
						</div>
						</form>
				</div>
			</div>
		</div>
	</section>
</div>
<?php include_once('footer.php'); ?>