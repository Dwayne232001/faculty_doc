<?php
ob_start();
session_start();
include_once('head.php');
include_once('header.php');
//check if user has logged in or not

if (!isset($_SESSION['loggedInUser'])) {
	//send the user to login page
	header("location:index.php");
}

$_SESSION['currentTab'] = "research";

if ($_SESSION['type'] != 'faculty') {
	header("location:index.php");
}

//connect to database
include_once("includes/connection.php");

//include custom functions files 
include_once("includes/functions.php");
include_once("includes/scripting.php");
include_once("includes/config.php");


$researchTitleError = $facultyNameError = $submittedToError = $amountError = $reportpath = $area = "";
$radioApproval = "";
$principleInvestigator = $coInvestigator = "";
$flag1 = $flag2 = $flag3 = $flag4 = $flag5 = 1;
//$currentTimestamp;
$success = 0;
$s = 1;
$p_id = 0;
$tenure = $month = $year = 0;
$proposedAmount = $sanctionedAmount = 0;
$Fac_ID = $_SESSION['Fac_ID'];
$error1 = "";

$queryrun = "SELECT * FROM facultydetails where Fac_ID=$Fac_ID";
$resultrun = mysqli_query($conn, $queryrun);
while ($row = mysqli_fetch_assoc($resultrun)) {
	$_SESSION['Dept'] = $row['Dept'];
	$_SESSION['type'] = $row['type'];
	$_SESSION['F_NAME'] = $row['F_NAME'];
	$facultyName = $row['F_NAME'];
}

$nameError	= '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST['add'])) {
		function cleanseTheData($data)
		{
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}

		$researchTitle_array = $_POST['researchTitle'];
		$startDate_array = $_POST['startDate'];
		$endDate_array = $_POST['endDate'];
		$submittedTo_array = $_POST['submittedTo'];
		$principleInvestigator_array = $_POST['princi'];
		// $coInvestigator_array = $_POST['coInvestigator'];
		$tenure_array = $_POST['tenure'];
		$sanctionedAmount_array = $_POST['sanctionedAmount'];
		$proposedAmount_array = $_POST['proposedAmount'];
		$area_array = $_POST['area'];


		for ($i = 0; $i < 1; $i++) {
			$researchTitle = mysqli_real_escape_string($conn, $researchTitle_array[$i]);
			//$startDate = mysqli_real_escape_string($conn,$startDate_array[$i]);
			//$endDate = mysqli_real_escape_string($conn,$endDate_array[$i]);
			$submittedTo = mysqli_real_escape_string($conn, $submittedTo_array[$i]);
			$principleInvestigator = mysqli_real_escape_string($conn, $principleInvestigator_array[$i]);
			// $coInvestigator = mysqli_real_escape_string($conn, $coInvestigator_array[$i]);
			$area = mysqli_real_escape_string($conn, $area_array[$i]);
			//$sanctionedAmount = mysqli_real_escape_string($conn,$sanctionedAmount_array[$i]);
			//$proposedAmount = mysqli_real_escape_string($conn,$proposedAmount_array[$i]);
			$tenure = mysqli_real_escape_string($conn, $tenure_array[$i]);




			if (empty($researchTitle)) {
				$researchTitleError = "Enter a valid Research Title!";
				$flag1 = 0;
			} else {
				$researchTitle = cleanseTheData($researchTitle);
				//echo $researchTitle."<br>";
			}
			/*$facultyName = cleanseTheData($_POST['facultyName']);
			if(empty($facultyName))
			{
				$facultyNameError = "Enter faculty name under who you did research!";
				$flag2 = 0;
			}*/
			if (empty($startDate_array[$i]))
				$flag2 = 0;
			else
				$startDate = $startDate_array[$i];
			$endDate = $endDate_array[$i]; //No need to clean here bcoz its date.

			$time = time();
			$start = new DateTime(date($startDate, $time));
			$end = new DateTime(date($endDate, $time));
			$days = date_diff($start, $end);
			$noofdays = $days->format('%d');
			$month = $start->format('n');
			$year = $start->format('Y');

			if (empty($endDate_array[$i])) {
				$date = date_create_from_format('Y-m-d', $startDate);
				date_add($date, date_interval_create_from_date_string("5 years"));
				$endDate = date_format($date, 'Y-m-d');
			}

			if ($startDate > $endDate) {
				$nameError = $nameError . "Start date cannot be greater than end date<br>";
				$error = "Start date cannot be greater than end date";
				$s = 0;
				$flag2 = 0;
			}

			//echo $endDate."<br>";
			if (empty($submittedTo)) {
				$submittedToError = "Enter the authority to whom research was submitted!";
				$flag3 = 0;
			} else {
				$submittedTo = cleanseTheData($submittedTo);
				//echo $submittedTo."<br>";
			}
			if (!empty($principleInvestigator)) {
				$principleInvestigator = cleanseTheData($principleInvestigator);
				//echo $principleInvestigator."<br>";
			}
			if (!empty($area)) {
				$area = cleanseTheData($area);
				//echo $principleInvestigator."<br>";
			}
			if (!empty($tenure)) {
				$tenure = cleanseTheData($tenure);
				//echo $principleInvestigator."<br>";
			}

			if (!empty($coInvestigator)) {
				$coInvestigator = cleanseTheData($coInvestigator);
				//echo $coInvestigator."<br>";
			}
			if (!empty($proposedAmount_array[$i])) {
				$proposedAmount = cleanseTheData($proposedAmount_array[$i]);
				//echo $proposedAmount."<br>";
			} else {
				$flag5 = 0;
			}
			// if (!empty($_POST['$radioApproval[]'])) {
			// 	$radioApproval = cleanseTheData($radioApproval_array[$i]);
			// }
			if (!empty($sanctionedAmount_array[$i])) {
				$sanctionedAmount = cleanseTheData($sanctionedAmount_array[$i]);
				//echo $sanctionedAmount."<br>";
			}
			// if (!empty($awardsWon)) {
			// 	$awardsWon = cleanseTheData($awardsWon_array[$i]);
			// 	//echo $awardsWon."<br>";
			// }
			// $radioApprovalAnswer = "";

			// if ($radioApproval == "yes")
			// 	$radioApprovalAnswer = "yes";
			// if ($radioApproval == "no") {
			// 	$radioApprovalAnswer = "no";
			// 	$sanctionedAmount = 0;
			// }
			//echo $radioApprovalAnswer."<br>";

			$coinvestarray = array();
			if (isset($_POST["co_name"]) && $s != 0) {
				for ($count2 = 0; $count2 < count($_POST["co_name"]); $count2++) {
					array_push($coinvestarray, $_POST["co_name"][$count2]);
				}
				$coInvestigator = implode(',', $coinvestarray);
			}

			if (($proposedAmount < 0 && $sanctionedAmount < 0)) {
				$amountError = "Proposed amount should not be less than zero";
				$flag4 = 0;
			}

			if (isset($_POST['applicable'])) {
				if ($_POST['applicable'] == 2) {
					$reportpath = 'NULL';
					$success = 1;
				} else if ($_POST['applicable'] == 3) {
					$reportpath = 'not_applicable';
					$success = 1;
				} else if ($_POST['applicable'] == 1) {
					if (isset($_FILES['report']) && $_FILES['report']['name'] != NULL) {
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
							//  else{
							//      echo "<h1> $targetName </h1>";
							//  }
						} else {
							print_r($errors);
						}
					} else {
						$s = 0;
						$error1 = "No file selected";
					}
				}
			}
			if ($principleInvestigator == "") {
				$principleInvestigator = 'NA';
			}
			if ($coInvestigator == "") {
				$coInvestigator = 'NA';
			}
			// if ($awardsWon == "") {
			// 	$awardsWon = 'NA';
			// }

			if ($flag1 == 1 && $flag2 == 1 && $flag3 == 1 && $flag4 == 1 && $flag5 == 1 && $s != 0) {
				$Fac_ID = $_SESSION['Fac_ID'];
				$sql = "INSERT INTO researchdetails (Fac_ID,researchTitle,facultyName,submittedTo,fromDate,toDate,proposedAmount,amountSanctioned,principleInvestigator,coInvestigator,reportPath,tenure,area,month,year,noofdays) VALUES ('$Fac_ID','$researchTitle','$facultyName','$submittedTo','$startDate', '$endDate','$proposedAmount','$sanctionedAmount','$principleInvestigator','$coInvestigator','" . $reportpath . "',$tenure,'$area',$month,$year,$noofdays)";
				echo $sql;
				if ($conn->query($sql) === TRUE) {
					$success = 1;
					header("location:researchView.php?aert=success");
				} else if ($s != 0) {
					header("location:researchView.php?alert=error");
				} else if ($s == 0) {
					echo "<script>alert('$error')</script>";
				}
			}
		}
	}
}
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

<?php
include("includes/connection.php");
$Fac_ID = $_SESSION['Fac_ID'];
$query = "SELECT * from facultydetails WHERE Fac_ID = '$Fac_ID' ";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$facultyName = $row['F_NAME'];
$deptName = $row['Dept'];
?>
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
							<h3 class="box-title"><b>Research Proposal/Projects/Consultancy Projects Form</b></h3>
							<br>
						</div>
					</div><!-- /.box-header -->
					<div style="text-align:right">
						<!--<a href="menu.php?menu=11 " style="text-align:right"> <u>Back to Research Menu</u></a> -->
					</div>
					<!-- form start -->


					<?php

					//for($k=0; $k<$_SESSION['count'] ; $k++)
					//{

					?>

					<?php
					for ($k = 0; $k < 1; $k++) {

					?>






						<form role="form" method="POST" class="row" action="" style="margin:10px;" enctype="multipart/form-data">
							<?php
							if ($flag1 != 1 || $flag2 != 1 || $flag3 != 1 || $flag4 != 1 || $flag5 != 1) {
								echo '<div class="error">' . $nameError . '</div>';
							}
							?>

							<div class="form-group col-md-6 col-md-offset-1"></div>
							<!-- <div class="form-group col-md-12">

								<label for="c_name">Faculty Role</label>
								<div class="table-repsonsive">
									<span id="error"></span>
									<table class="table table-bordered" id="c_name">
										<tr>
											<th>Click to select </th>
											<th><button type="button" name="add" class="btn btn-success btn-sm add"><span class="glyphicon glyphicon-plus"></span></button></th>
										</tr>
									</table>
								</div>
							</div><br> -->
							<form class="row" action="" style="margin:10px;">
								<div class="form-group col-md-6">
									<label for="fname">Department </label>
									<br><input type='text' class='form-control input-lg' name='facultyName' id='facultyName' readOnly value='<?php echo $deptName; ?>'>
								</div>
								<div class="form-group col-md-6">
									<label for="department">Faculty</label>
									<br><input type='text' class='form-control input-lg' name='facultyName' id='facultyName' readOnly value='<?php echo $facultyName; ?>'>
								</div>
							

							<div class="form-group col-md-6">
								<label for="princi">Principal Investigator</label>
								<span class="colour"><b> *</b></span>
								<div class="table-repsonsive">
									<span id="error"></span>
									<select name="princi[]" class="form-control item_unit" id="search">
										<option value="">Select Principal Investigator</option><?php echo fill_unit_select_box($connect); ?>
									</select></td>
								</div>
							</div><br>

							<div class="form-group col-md-6">

								<label for="c_name">Co-Principal Investigator</label>
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
								<label for="research-title">Name of Project </label><span class="colour"><b> *</b></span>
								<input type="text" id="research-title" class="form-control input-lg" name="researchTitle[]" required>
							</div>

							<div class="form-group col-md-6">
								<label for="area">Area of Project </label><span class="colour"><b> *</b></span>
								<input type="text" id="area" class="form-control input-lg" name="area[]" required>
							</div>

							<div class="form-group col-md-6 col-md-offset-1"></div>

							<div class="form-group col-md-6">
								<label for="submittedTo">Name of Agency/Programme/Scheme Submitted To </label><span class="colour"><b> *</b></span>
								<input <?php if (isset($_POST['submittedTo'])) echo "value = $submittedTo"; ?> required type="text" class="form-control input-lg" id="submittedTo" name="submittedTo[]">
							</div>

							<div class="form-group col-md-6">
								<label for="tenure">Tenure of Project (in Months)</label><span class="colour"><b> *</b></span>
								<input type="number" id="tenure" class="form-control input-lg" name="tenure[]" required>
							</div>

							<div class="form-group col-md-6">
								<label for="start-date">Start Date </label><span class="colour"><b> *</b></span>
								<input <?php if (isset($_POST['startDate'])) echo "value = $startDate"; ?> type="date" class="form-control input-lg" id="start-date" name="startDate[]" required>
							</div>

							<div class="form-group col-md-6">
								<label for="end-date">End Date </label><span class="colour"><b> *</b></span>
								<input <?php if (isset($_POST['endDate'])) echo "value = $endDate"; ?> type="date" class="form-control input-lg" id="end-date" name="endDate[]" required>
							</div>

							<!--
							 <div class="form-group col-md-6">
                         <label for="principleInvestigator">Principle Investigator </label>
					  <input  type="text" id="principleInvestigator" class="form-control input-lg"  name="principleInvestigator[]"  
					  value = '<?php if (isset($_POST['principleInvestigator'])) echo $principleInvestigator; ?>' >
                     </div>
                     <div class="form-group col-md-6">
                         <label for="coInvestigator">Co Investigator </label>
					  <input  type="text" id="coInvestigator" class="form-control input-lg"  name="coInvestigator[]"
					   value = '<?php if (isset($_POST['coInvestigator'])) echo $coInvestigator; ?>'>
                     </div>-->
							<div class="form-group col-md-6">
								<label for="proposedAmount">Amount Submitted</label><span class="colour"><b> *</b></span>
								<input <?php if (isset($_POST['proposedAmount'])) echo "value = $proposedAmount"; ?> type="number" step="0.01" class="form-control input-lg" id="proposedAmount" name="proposedAmount[]" required>
							</div>
							<!--<div class="form-group col-md-6">
                         <label for="radioApproval">Approved? *</label>
                         <select required class="form-control input-lg radioApproval" id="radioApproval" name="radioApproval[]">
                             <option <?php if ($radioApproval == "yes") echo "selected = 'selected'" ?> value = "yes">Yes</option>
                             <option <?php if ($radioApproval == "no") echo "selected = 'selected'" ?> value = "no">No</option>
                         </select>
                     </div>
					 -->
							<div class="form-group col-md-6" id="sanctionedAmountDiv">
								<label for="sanctionedAmount">Amount Granted</label><span class="colour"><b> *</b></span>
								<input <?php if (isset($_POST['sanctionedAmount'])) echo "value = $sanctionedAmount"; ?> type="number" step="0.01" class="form-control input-lg" id="sanctionedAmount" name="sanctionedAmount[]" required>
							</div>
							<!--
                     <div class="form-group col-md-6">
                         <label for="awardsWon">Awards Won, if any?</label>
                         <input type="text"  class="form-control input-lg" id="awardsWon" name="awardsWon[]">
                     </div> -->

							<div class="form-group col-md-6 col-md-offset-1"></div>
							<div class="form-group col-md-6">
								<div>
									<label for="course">Upload report: Applicable?<br></label><span class="colour"><b> *</b></span>
									<span class="error" style="border : none;"> <?php echo $error1 ?> </span>
									<br>&nbsp;<input required type='radio' name='applicable' class='non-vac' value='1' <?php if (isset($_POST['applicable'])) echo ($_POST['applicable'] == "1") ? 'checked' : '' ?>> Yes <br>
									&nbsp;<input type='radio' name='applicable' class='vac' value='2' <?php if (isset($_POST['applicable'])) echo ($_POST['applicable'] == "2") ? 'checked' : '' ?>> Applicable, but not yet available <br>
									&nbsp;<input type='radio' name='applicable' class='vac' value='3' <?php if (isset($_POST['applicable'])) echo ($_POST['applicable'] == "3") ? 'checked' : '' ?>> No <br>
								</div>
								<br>
								<div class='second-reveal' <?php if (isset($_POST['applicable'])) echo ($_POST['applicable'] == "1") ? 'style = "display : block" ' : '' ?>>

									<div>
										<label for="card-image">Report </label><span class="colour"><b> *</b></span>
										<input type="file" class="form-control input-lg" id="card-image" name="report">
									</div>
								</div>
							</div>

							<script>
								$('.radioApproval').each(function() {
									$('.radioApproval').on('change', myfunction);
								});

								function myfunction() {
									var x = this.value;

									if (x == 'yes') {

										$(this).parent().next()[0].style.display = "block";
									} else {
										$(this).parent().next()[0].style.display = "none";
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

<!-- <script>
	function handleSelection(source, dest) {
		// source.options[source.selectedIndex].disabled=true;
		dest.options[source.selectedIndex].disabled = true;
	}
	$(document).ready(function() {

		$(document).on('click', '.add', function() {
			var html = '';
			html += '<tr>';
			html += '<td><select name="co_name[]" class="form-control item_unit" id="search"><option value="">Select Faculty</option><?php echo fill_unit_select_box($connect); ?></select></td>';
			html += '<td><select name="roles[]" id="roles" class="form-control item_unit" onchange="handleSelection(this, roles)"><option value="" disabled selected>Select Role:</option><option value="principal_investigator">Principal Investigator(PI)</option>';
			html += '<option value="co_principal_investigator">Co-Principal Investigator(Co-PI)</option></select></td>';
			html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm remove"><span class="glyphicon glyphicon-minus"></span></button></td></tr>';
			$('#c_name').append(html);
		});

		$(document).on('click', '.remove', function() {
			$(this).closest('tr').remove();
		});

		$('#roles').change(function() {
			var val = "principal_investigator";
			var sel = ($(this).val());
			if (sel == val) {
				$('#roles').find('option:contains(principal_investigator)').remove();
			}

		});
		$('#roles').trigger('change');

	});
</script> -->

<?php include_once('footer.php'); ?>