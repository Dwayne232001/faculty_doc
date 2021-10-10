<?php
ob_start();
session_start();
//check if user has logged in or not

if (!isset($_SESSION['loggedInUser'])) {
	//send the iser to login page
	header("location:index.php");
}

$_SESSION['currentTab'] = "research";

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
include_once("includes/config.php");
if (isset($_POST['rid'])) {
	$_SESSION['id'] = $_POST['rid'];
	$_POST['rid'] = $_SESSION['id'];
}

//setting error variables
$nameError = "";
$researchTitle = $facultyName = $fromDate = $toDate = $submittedTo = $proposedAmount = $radioApproval = $amountSanctioned = $principleInvestigator = $coInvestigator = $awardsWon = $reportpath = "";

if (isset($_POST['rid'])) {
	$_SESSION['research_Id'] = $_POST['rid'];
}
$research_Id = $_SESSION['research_Id'];
$query = "SELECT * from researchdetails where research_Id = $research_Id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$Fac_ID = $row['Fac_ID'];
$facultyName = $row['facultyName'];
$researchTitle = $row['researchTitle'];
// $_SESSION['F_NAME'] = $facultyName;
$fromDate = $row['fromDate'];
$toDate = $row['toDate'];
$submittedTo = $row['submittedTo'];
$principleInvestigator = $row['principleInvestigator'];
$coInvestigator = $row['coInvestigator'];
$proposedAmount = $row['proposedAmount'];
$tenure = $row['tenure'];
$amountSanctioned = $row['amountSanctioned'];
$area = $row['area'];
$currentTimestamp = $row['currentTimestamp'];
$reportpath = $row['reportPath'];
$coordiarray = explode(',', $coInvestigator);
$query2 = "SELECT * from facultydetails where Fac_ID = $Fac_ID";
$result2 = mysqli_query($conn, $query2);
if ($result2) {
	$row = mysqli_fetch_assoc($result2);
	$F_NAME = $row['F_NAME'];
}

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

<?php
$researchTitleError = $facultyNameError = $submittedToError = $amountError = "";
$flag1 = $flag2 = $flag3 = $flag4 = 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST['edit'])) {
		function cleanseTheData($data)
		{
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}

		$researchTitle = cleanseTheData($_POST['researchTitle']);
		if (empty($researchTitle)) {
			$researchTitleError = "Enter a valid Research Title!";
			$flag1 = 0;
		}
		/*$facultyName = cleanseTheData($_POST['facultyName']);
		if(empty($facultyName))
		{
			$facultyNameError = "Enter faculty name under who you did research!";
			$flag2 = 0;
		}*/
		$fromDate = $_POST['fromDate'];
		$toDate = $_POST['toDate'];

		$tenure = $_POST['tenure'];
		$area = $_POST['area'];

		if ((strtotime($_POST['fromDate'])) > (strtotime($_POST['toDate']))) {
			$nameError = $nameError . "From Date cannot be greater than to date<br>";
			$flag2 = 0;
		}
		$time = time();
		$start = new DateTime(date($fromDate, $time));
		$end = new DateTime(date($toDate, $time));
		$days = date_diff($start, $end);
		$noofdays = $days->format('%d');
		$month = $start->format('n');
		$year = $start->format('Y');

		// $coInvestigator = cleanseTheData($_POST['coInvestigator']);
		$principleInvestigator = cleanseTheData($_POST['princi']);

		if (!isset($_POST['co_name'])) {
			$coInvestigator = "NA";
		} else {

			$coInvestigator = "";
			$coautharray = array();
			if (isset($_POST["co_name"])) {
				for ($count2 = 0; $count2 < count($_POST["co_name"]); $count2++) {
					$co_name = $_POST["co_name"][$count2];
					array_push($coautharray, $co_name);
				}
				$coInvestigator = implode(',', $coautharray);
			} else {
				$coInvestigator = $coordinated;
			}
		}

		$submittedTo = cleanseTheData($_POST['submittedTo']);
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
		if (empty($submittedTo)) {
			$submittedToError = "Enter the authority to whom research was submitted!";
			$flag3 = 0;
		}
		$proposedAmount = $_POST['proposedAmount'];
		// $radioApproval = $_POST['radioApproval'];
		$amountSanctioned = $_POST['amountSanctioned'];
		// $awardsWon = cleanseTheData($_POST['awardsWon']);
		// if ($radioApproval === "no") {
		// 	$amountSanctioned = 0;
		// }
		if (($proposedAmount < 0 && $amountSanctioned < 0)) {
			$amountError = "Proposed amount should not be less than zero";
			$flag4 = 0;
		}
		if ($flag1 == 1 && $flag2 == 1 && $flag3 == 1 && $flag4 == 1) {
			//$succ = 0;
			//$success = 0;

			$sql = "UPDATE researchdetails SET researchTitle = '$researchTitle',
										fromDate = '$fromDate',
										toDate = '$toDate',
										submittedTo = '$submittedTo',
										principleInvestigator = '$principleInvestigator',
										coInvestigator = '$coInvestigator',
										proposedAmount = '$proposedAmount',
										currentTimestamp = CURRENT_TIMESTAMP,
										amountSanctioned = '$amountSanctioned',
										reportPath = '" . $reportpath . "',
										noofdays = $noofdays,
										tenure = $tenure,
										area = '$area',
										month = $month,
										year = $year
							   WHERE research_Id = $research_Id ";

			echo $sql;
			if ($conn->query($sql) === TRUE) {
				if ($_SESSION['type'] == 'hod') {
					header("location:researchViewHOD.php?alert=update");
				} else {
					header("location:researchView.php?alert=update");
				}
			} else {
				if ($_SESSION['type'] == 'hod') {
					header("location:researchViewHOD.php?alert=error");
				} else {
					header("location:researchView.php?alert=error");
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

<script src="jquery/jquery-3.2.1.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("input[name$='radioApproval']").click(function() {
			var test = $(this).val();
			if (test == "yes") {
				$("#sanctionedAmountDiv").show();
			} else {
				$("#sanctionedAmountDiv").hide();
			}
		});
	});
</script>
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
							<h3 class="box-title"><b>Research Details Edit Form</b></h3>
							<br>
						</div>
					</div><!-- /.box-header -->
					<div style="text-align:right">
						<!--	<a href="menu.php?menu=11 " style="text-align:right"> <u>Back to Research Menu</u></a> -->
					</div>
					<!-- form start -->

					<form class="row" action="" style="margin:10px;" enctype="multipart/form-data">
						<?php
						if ($flag1 != 1 || $flag2 != 1 || $flag3 != 1 || $flag4 != 1) {
							echo '<div class="error">' . $nameError . '</div>';
						}
						?>

					</form>
					<form role="form" method="POST" class="row" action="" style="margin:10px;" enctype="multipart/form-data">
						<div class="form-group col-md-6">
							<label for="fname">Department </label>
							<br><input type='text' class='form-control input-lg' name='facultyName' id='facultyName' readOnly value='<?php echo $_SESSION['Dept']; ?>'>
						</div>
						<div class="form-group col-md-6">

							<label for="faculty-name">Faculty</label>
							<input type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $F_NAME; ?>" readonly>
						</div>

						<div class="form-group col-md-6">
							<label for="princi">Principal Investigator</label>
							<span class="colour"><b> *</b></span>
							<select name="princi" class="form-control input-lg" id="search">
								<option value="<?php echo $principleInvestigator ?>" selected><?php echo $principleInvestigator ?></option>
								<?php echo fill_unit_select_box($connect); ?>
							</select>
						</div>
						<div class="form-group col-md-6">

							<label for="c_name">Co-Principal Investigator </label>
							<div class="table-repsonsive">
								<span id="error"></span>
								<table class="table table-bordered" id="c_name">
									<tr>
										<input type="text" required class="form-control input-lg" id="coordinated" name="coordinated" rows="2" value="<?php echo $coInvestigator; ?>">
									</tr>
									<tr>
										<th>Click to Edit </th>
										<th><button type="button" name="add" class="btn btn-success btn-sm add"><span class="glyphicon glyphicon-plus"></span></button></th>
									</tr>
								</table>
							</div>
						</div><br>
						<div class="form-group col-md-6">
							<label for="research-title">Name of Project</label><span class="colour"><b> *</b></span>
							<!--   <input required type="text" class="form-control input-lg" id="paper-title" name="paperTitle[]">-->
							<input type="text" id="research-title" class="form-control input-lg" name="researchTitle" value='<?php echo $researchTitle ?>' required>
						</div>

						<div class="form-group col-md-6">
							<label for="area">Area of Project</label><span class="colour"><b> *</b></span>
							<input type="text" class="form-control input-lg" id="area" name="area" value='<?php echo $area ?>' required>
						</div>

						<div class="form-group col-md-6">
							<label for="submittedTo">Name of Agency/Programme/Scheme Submitted To </label><span class="colour"><b> *</b></span>
							<input required type="text" class="form-control input-lg" id="submittedTo" name="submittedTo" value='<?php echo $submittedTo ?>'>
						</div>

						<div class="form-group col-md-6">
							<label for="tenure">Tenure of Project(In Months)</label><span class="colour"><b> *</b></span>
							<input type="text" class="form-control input-lg" id="tenure" name="tenure" value='<?php echo $tenure ?>' required>
						</div>

						<div class="form-group col-md-6">
							<label for="start-date">Start Date</label><span class="colour"><b> *</b></span>
							<input type="date" class="form-control input-lg" id="start-date" name="fromDate" value='<?php echo $fromDate ?>' required>
						</div>

						<div class="form-group col-md-6">
							<label for="end-date">End Date</label><span class="colour"><b> *</b></span>
							<input type="date" class="form-control input-lg" id="end-date" name="toDate" value='<?php echo $toDate ?>' required>
						</div>

						<div class="form-group col-md-6">
							<label for="proposedAmount">Amount Submitted</label><span class="colour"><b> *</b></span>
							<input type="text" class="form-control input-lg" id="proposedAmount" name="proposedAmount" value='<?php echo $proposedAmount ?>' required>
						</div>

						<!-- <div class="form-group col-md-6">
                         <label for="radioApproval">Approved? *</label>
                         <br>
                         Yes &nbsp;&nbsp; <input type="radio" class="radio-inline" name="radioApproval" value="yes" <?php if ($radioApproval_db == "yes") {
																														echo 'checked';
																													} ?>>
                         <br>No &nbsp;&nbsp; <input type="radio" class="radio-inline" name="radioApproval" value="no" <?php if ($radioApproval_db == "no") {
																															echo 'checked';
																														} ?>>
                     </div> -->
						<div class="form-group col-md-6" id="sanctionedAmountDiv">
							<label for="sanctionedAmount">Amount Granted</label><span class="colour"><b> *</b></span>
							<input type="text" class="form-control input-lg" id="sanctionedAmount" name="amountSanctioned" value='<?php echo $amountSanctioned ?>' required>
						</div>

						<!-- <div class="form-group col-md-6">
                         <label for="awardsWon">Awards Won, if any?</label>
                         <input type="text"  class="form-control input-lg" id="awardsWon" name="awardsWon" value="<?php echo $awardsWon ?>">
                     </div> -->


						<div class="form-group col-md-6 col-md-offset-1"></div>
						<div class="form-group col-md-6">

							<div>
								<label for="Index">Report : </label><span class="colour"><b> *</b></span><br />
								<input type="radio" name="applicable2" id="r3" value="1" class="non-vac2" <?php echo ($reportpath != NULL) ? 'checked' : '' ?>>Yes<br>
								<input type="radio" name="applicable2" value="2" class="vac2" <?php echo ($reportpath == 'NULL') ? 'checked' : '' ?>>Applicable, but not yet available <br>
								<input type="radio" name="applicable2" value="3" class="vac2" <?php echo ($reportpath == 'not_applicable') ? 'checked' : '' ?>> No
							</div>
							<br>
							<div class='second-reveal2' id='f3'>
								<div class=>

									<label for="card-image">Report </label><span class="colour"><b> *</b></span>
									<input type="file" class="form-control input-lg" id="card-image" name="report">
									<a <?php
										$f2 = 0;
										if ($reportpath != "not_applicable" && $reportpath != "NULL" && $reportpath != "no status" && $reportpath != "") {
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

						<?php
						//}
						?>
						<br />
						<div class="form-group col-md-12">
							<?php if ($_SESSION['type'] == 'hod') { ?>
								<a href="researchViewHOD.php" type="button" class="btn btn-warning btn-lg">Cancel</a>
							<?php } else { ?>
								<a href="researchView.php" type="button" class="btn btn-warning btn-lg">Cancel</a>
							<?php } ?>
							<button name="edit" type="submit" class="btn btn-success pull-right btn-lg">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>


</div>
<script>
	window.onload = function() {
		mycheck3();
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

<script>
	$(document).ready(function() {
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


<?php include_once('footer.php'); ?>