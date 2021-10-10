<?php
	ob_start();
	session_start();
	
	include("includes/connection.php");

	if(!isset($_SESSION['loggedInUser'])){
		//send the iser to login page
		header("location:index.php");
	}
	
	if(isset($_SESSION['type'])){
		if($_SESSION['type'] != 'hod' && $_SESSION['type'] != 'cod' && $_SESSION['type']!='com'){
		//if not hod then send the user to login page
		session_destroy();
		header("location:index.php");
	  }
	  }  
	  
	  $fid=$_SESSION['Fac_ID'];
	  
	  $queryrun="SELECT * FROM facultydetails where Fac_ID=$fid";
	  $resultrun = mysqli_query($conn, $queryrun);
	  while($row=mysqli_fetch_assoc($resultrun)){
	  $_SESSION['Dept']=$row['Dept'];
	  $_SESSION['type']=$row['type'];
	  }

    include_once('head.php');
    include_once('header.php'); 

    if($_SESSION['type'] == 'hod')
  {
	    include_once('sidebar_hod.php');

  }elseif ($_SESSION['type']=='cod' || $_SESSION['type']=='com' ) {
  		include_once('sidebar_cod.php');
  }
  else{
  	include_once('sidebar.php');
  }
include("includes/connection.php");
	echo "<div class='content-wrapper'>";
    echo "<section class='content'>";
    echo "<div class='row'>";
    echo "<br>";
    echo "<br>";
    echo "<br>";

    echo "<div class='box box-primary'>";
    echo "<div class='box-header with-border'>";
	echo "<center>";
	$sql= " SELECT * FROM facultydetails WHERE type='faculty'";
	$result= mysqli_query($conn,$sql);
	echo "<form action='chart_hod.php' method='POST'>";
	echo "<label>Select Faculty Name </label><br>";
	echo "<select id='search' name='name'>";
	while($row=mysqli_fetch_array($result))
	{
		echo"<option>".$row['F_NAME'].",".$row['Dept']."</option>";
	}
	echo "</select>";
	echo "<br>";
	echo "<br>";
	echo "<br>";
	echo "<input type='submit' name='submit'>";
	echo "</form>";
	echo "</center>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
	echo "</section>";
	echo "</div>";
	include_once('footer.php');
?>

<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script>
	$("#search").chosen();
</script>
</head>
<body>

</body>
</html>