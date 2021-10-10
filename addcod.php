<?php
ob_start();
session_start();
if (!isset($_SESSION['loggedInUser'])) {
	//send them to login page
	header("location:index.php");
}

include("includes/connection.php");

$fid = $_SESSION['Fac_ID'];

$queryrun = "SELECT * FROM facultydetails where Fac_ID=$fid";
$resultrun = mysqli_query($conn, $queryrun);
while ($row = mysqli_fetch_assoc($resultrun)) {
	$_SESSION['Dept'] = $row['Dept'];
	$_SESSION['type'] = $row['type'];
}

include_once('head.php');
include_once('header.php');

if ($_SESSION['type'] != 'hod') {
	header("Location: index.php");
}

if ($_SESSION['type'] == 'hod') {
	include_once('sidebar_hod.php');
} elseif ($_SESSION['type'] == 'cod' || $_SESSION['type'] == 'com') {
	include_once('sidebar_cod.php');
} else {
	include_once('sidebar.php');
}

//include custom functions files 
include("includes/functions.php");
include("includes/scripting.php");

$query1 = $sql = "";
$success = 1;
$test = $test1 = $test2 = 0;
$val1 = $val2 = 0;
$fac1 = $fac2 = 0;
$mobile1 = $mobile2 = 0;

if (isset($_POST["submit"])) {
	if (isset($_POST["codname"]) && $_POST["codname"] != "") {
		$codname = $_POST["codname"];
		$sql = "SELECT Fac_ID,Mobile from facultydetails WHERE F_NAME = '$codname'";
		$result = mysqli_query($conn, $sql);
		if ($result == true) {
			while ($row = mysqli_fetch_assoc($result)) {
				$val = $row['Fac_ID'];
				$facmobile = $row['Mobile'];
				$fac1 = $row['Fac_ID'];
			}
			$query = "INSERT INTO co_ordinator (fac_id,cod_name,date_from,cod_status) values($val,'$codname',now(),'active')";
			$result1 = mysqli_query($conn, $query);
			if ($result1 == true) {
				$fid1 = (($conn->insert_id) - 1);
				$sql1 = "UPDATE co_ordinator SET cod_status='inactive' where cod_id=$fid1";
				$result2 = mysqli_query($conn, $sql1);
				if ($result2 == true) {
					$facname = "Co-ordinator, " . $codname;
					$sql2 = "UPDATE facultydetails SET F_NAME='$facname',Mobile=$facmobile WHERE Dept='" . $_SESSION['Dept'] . "' AND type='cod' ";
					$result1 = mysqli_query($conn, $sql2);
					if ($result1 == true) {
						$success = 1;
						echo "<script>alert('Coordinator added successfully..');</script>";
					} else {
						$success = 0;
					}
				} else {
					echo mysqli_error($result2);
				}
			} else {
				echo mysqli_error($result1);
			}
		} else {
			echo mysqli_error($result);
		}
	}
	if (isset($_POST["com1name"]) && isset($_POST["com2name"]) && $_POST["com1name"] != "" && $_POST["com2name"] != "") {
		$test = 2;
	} else if (!isset($_POST["com2name"]) && !isset($_POST["com1name"]) && $_POST["com1name"] == "" && $_POST["com2name"] == " ") {
		$test = 0;
	} else if ((isset($_POST['com1name']) && $_POST['com1name'] != "") || (isset($_POST['com2name']) && $_POST['com2name'] != "")) {
		$test = 1;
	}
	if ($test == 2) {
		$co1name = $_POST["com1name"];
		$co2name = $_POST["com2name"];
		$com1name = "Committee, " . $co1name;
		$com2name = "Committee, " . $co2name;
		$sql1 = "SELECT * FROM facultydetails WHERE F_NAME ='$co1name' ";
		$res1 = mysqli_query($conn, $sql1);
		while ($row = mysqli_fetch_assoc($res1)) {
			$val1 = $row["Fac_ID"];
			$mobile1 = $row["Mobile"];
			$fac1 = $row["Fac_ID"];
		}
		$sql2 = "SELECT * FROM facultydetails WHERE F_NAME = '$co2name' ";
		$res2 = mysqli_query($conn, $sql2);
		while ($row = mysqli_fetch_assoc($res2)) {
			$val2 = $row["Fac_ID"];
			$mobile2 = $row["Mobile"];
			$fac2 = $row["Fac_ID"];
		}
		$query1 = "INSERT INTO committee (fac_id,com_name,date_from,com_status) values($val1,'$co1name',now(),'active'),($val2,'$co2name',now(),'active')";
		mysqli_query($conn, $query1);
		$fid2 = (($conn->insert_id) - 1);
		$fid3 = $fid2 - 1;
		$sql1 = "UPDATE committee SET com_status='inactive' where com_id=$fid2";
		$result1 = mysqli_query($conn, $sql1);
		$sql2 = "UPDATE committee SET com_status='inactive' where com_id=$fid3";
		$result2 = mysqli_query($conn, $sql2);
		echo "1" . $fac1 . "2" . $fac2;
		if ($result1 == true && $result2 == true) {
			if ($_SESSION['Dept'] == 'comp') {
				$sql3 = "UPDATE facultydetails SET F_NAME='$com1name',Mobile=$mobile1 WHERE Email='com1comp@somaiya.edu' ";
				$sql4 = "UPDATE facultydetails SET F_NAME='$com2name',Mobile=$mobile2 WHERE Email='com2comp@somaiya.edu' ";
			} elseif ($_SESSION['Dept'] == 'IT') {
				$sql3 = "UPDATE facultydetails SET F_NAME='$com1name',Mobile=$mobile1 WHERE Email='com1.it@somaiya.edu' ";
				$sql4 = "UPDATE facultydetails SET F_NAME='$com2name',Mobile=$mobile2 WHERE Email='com2.it@somaiya.edu' ";
			} elseif ($_SESSION['Dept'] == 'extc') {
				$sql3 = "UPDATE facultydetails SET F_NAME='$com1name',Mobile=$mobile1 WHERE Email='com1extc@somaiya.edu' ";
				$sql4 = "UPDATE facultydetails SET F_NAME='$com2name',Mobile=$mobile2 WHERE Email='com2extc@somaiya.edu' ";
			} elseif ($_SESSION['Dept'] == 'mech') {
				$sql3 = "UPDATE facultydetails SET F_NAME='$com1name',Mobile=$mobile1 WHERE Email='com1mech@somaiya.edu' ";
				$sql4 = "UPDATE facultydetails SET F_NAME='$com2name',Mobile=$mobile2 WHERE Email='com2comp@somaiya.edu' ";
			} elseif ($_SESSION['Dept'] == 'etrx') {
				$sql3 = "UPDATE facultydetails SET F_NAME='$com1name',Mobile=$mobile1 WHERE Email='com2etrx@somaiya.edu' ";
				$sql4 = "UPDATE facultydetails SET F_NAME='$com2name',Mobile=$mobile2 WHERE Email='com2etrx@somaiya.edu' ";
			} else {
				$sql3 = "UPDATE facultydetails SET F_NAME='$com1name',Mobile=$mobile1 WHERE Email='com2sci@somaiya.edu' ";
				$sql4 = "UPDATE facultydetails SET F_NAME='$com2name',Mobile=$mobile2 WHERE Email='com2sci@somaiya.edu' ";
			}
			$result3 = mysqli_query($conn, $sql3);
			$result4 = mysqli_query($conn, $sql4);
			$result5 = mysqli_query($conn, $sql5);
			$result6 = mysqli_query($conn, $sql6);
			if ($result3 == true && $result4 == true) {
?>
				<script>
					var ab = "<?php echo $com1name; ?>";
					var bc = "<?php echo $com2name; ?>";
				</script>
			<?php
				echo "<script>
						alert('Email IDs assigned are :\\n'+
							 ab + ' : com1comp@somaiya.edu\\n'+
							 bc + ' : com2comp@somaiya.edu')
							 window.location.replace('list_of_activities_user.php');
							 </script>";
			} else {
				echo $conn->error;
			}
		} else {
			mysqli_error($result1);
			mysqli_error($result2);
		}
	} else if ($test == 1) {
		if (isset($_POST['com1name']) && $_POST['com1name'] != "") {
			$co2name = $_POST["com1name"];
		} elseif (isset($_POST['com2name']) && $_POST['com2name'] != "") {
			$co2name = $_POST["com2name"];
		}
		$com2name = "Committee, " . $co2name;
		$sql2 = "SELECT * FROM facultydetails WHERE F_NAME = '$co2name' ";
		$res2 = mysqli_query($conn, $sql2);
		while ($row = mysqli_fetch_assoc($res2)) {
			$val2 = $row["Fac_ID"];
			$mobile2 = $row["Mobile"];
		}
		$query1 = "INSERT INTO committee (fac_id,com_name,date_from,com_status) values($val2,'$co2name',now(),'active')";
		mysqli_query($conn, $query1);
		$fid2 = (($conn->insert_id) - 1);
		$fid3 = $fid2 - 1;
		$sql1 = "UPDATE committee SET com_status='inactive' where com_id=$fid2";
		$result1 = mysqli_query($conn, $sql1);
		$sql2 = "UPDATE committee SET com_status='inactive' where com_id=$fid3";
		$result2 = mysqli_query($conn, $sql2);
		if ($result2 == true) {
			if ($_SESSION['Dept'] == 'comp') {
				$sql4 = "UPDATE facultydetails SET F_NAME='$com2name',Mobile=$mobile2 WHERE Email='com2comp@somaiya.edu' ";
			} elseif ($_SESSION['Dept'] == 'IT') {
				$sql4 = "UPDATE facultydetails SET F_NAME='$com2name',Mobile=$mobile2 WHERE Email='com2.it@somaiya.edu' ";
			} elseif ($_SESSION['Dept'] == 'extc') {
				$sql4 = "UPDATE facultydetails SET F_NAME='$com2name',Mobile=$mobile2 WHERE Email='com2extc@somaiya.edu' ";
			} elseif ($_SESSION['Dept'] == 'mech') {
				$sql4 = "UPDATE facultydetails SET F_NAME='$com2name',Mobile=$mobile2 WHERE Email='com2mech@somaiya.edu' ";
			} elseif ($_SESSION['Dept'] == 'etrx') {
				$sql4 = "UPDATE facultydetails SET F_NAME='$com2name',Mobile=$mobile2 WHERE Email='com2etrx@somaiya.edu' ";
			} else {
				$sql4 = "UPDATE facultydetails SET F_NAME='$com2name',Mobile=$mobile2 WHERE Email='com2sci@somaiya.edu' ";
			}
			$result4 = mysqli_query($conn, $sql4);
			if ($result4 == true) {
			?>
				<script>
					var bc = "<?php echo $com2name; ?>";
				</script>
<?php
				echo "<script>
						alert('Email ID assigned is :\\n'+
							 bc + ' : com2comp@somaiya.edu')
							 window.location.replace('list_of_activities_user.php');
							 </script>";
			} else {
				echo mysqli_error($result4);
			}
		} else {
			mysqli_error($result2);
		}
	} else if ($test == 0) {
		header("location:list_of_activities_user.php");
	}
}

function fill_unit_select_box($connect)
{
	$output = '';
	$query = "SELECT * FROM facultydetails where type='faculty' AND Dept='" . $_SESSION['Dept'] . "' ORDER BY F_NAME ASC";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach ($result as $row) {
		$output .= '<option value="' . $row["F_NAME"] . '">' . $row["F_NAME"] . '</option>';
	}
	return $output;
}

?>
<div class='content-wrapper'>
	<section class='content'>
		<div class='row'>
			<br><br><br>
			<div class='box box-primary'>
				<div class='box-header with-border'>
					<center>
						<form action='' method='POST'>
							<div class="form-group col-md-12">
								<div class="table-repsonsive">
									<span id="error"></span>
									<table class="table table-bordered" id="c_name">
										<tr>
											<th>Select Faculty Name </th>
											<th>Corresponding Role </th>
										</tr>
									</table>
								</div>
							</div>
							<div class="form-group col-md-12">
								<input type='submit' name='submit' class="btn btn-success">
								</diiv>
						</form>
					</center>
				</div>
			</div>
		</div>
	</section>
</div>
<?php
include_once('footer.php');
?>

<head>
	<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" rel="stylesheet"> -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script>
		var countnum = 0;
		var flag = 0;
		window.onload = function() {
			myfunction();
		}

		function myfunction() {
			var html = '';
			html += '<tr> ';
			html += '<td><select name="codname" class="form-control item_unit" id="search"><option value="">Select Co-ordinator</option><?php echo fill_unit_select_box($connect); ?></select></td>';
			html += '<td><input type="text" name="cod" class="form-control item_unit" id="role" readonly value="Co-ordinator"></td>';
			html += '</tr><tr>';
			html += '<th>Click to add Committee Member <span>&emsp;&emsp;</span><button type="button" name="add" class="btn btn-success btn-sm add"><span class="glyphicon glyphicon-plus"></span></button></th></tr>';
			html += '</tr><tr>';
			html += '<tr id="com_name" ></tr>'
			html += '<td><select name="com1name" class="form-control item_unit" id="search"><option value="">Select Committee Member</option><?php echo fill_unit_select_box($connect); ?></select></td>';
			html += '<td><input type="text" name="com1" class="form-control item_unit" id="role" readonly value="Committee Member"></td>';
			html += '</tr><tr>';
			html += '<td><select name="com2name" class="form-control item_unit" id="search"><option value="">Select Committee Member</option><?php echo fill_unit_select_box($connect); ?></select></td>';
			html += '<td><input type="text" name="com2" class="form-control item_unit" id="role" readonly value="Committee Member"></td>';
			html += '</tr>';
			$('#c_name').append(html);
		}

		$(document).ready(function() {

			$(document).on('click', '.add', function() {
				var html = '';
				html += '<table class="table table-bordered">'
				html += '<td><select name="co_name[]" class="form-control item_unit" style="width:80%; display:inline-block;"; id="search"><option value="">Select Committee Member</option><?php echo fill_unit_select_box($connect); ?></select>';
				html += '<span>&emsp;</span><button type="button" name="remove" class="btn btn-danger btn-sm remove"><span class="glyphicon glyphicon-minus"></span></button></td>';
				html += '<td><input type="text" name="com2" class="form-control item_unit" id="role" readonly value="Committee Member"></td><br>';
				html += '</table>'
				$('#com_name').append(html);
			});

			$(document).on('click', '.remove', function() {
				$(this).closest('tr').remove();
			});
		});
	</script>
</head>