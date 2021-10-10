<?php
session_start();
if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
	include_once("includes/connection.php");
	include_once('head.php'); 
	include_once('header.php'); 
	$_SESSION['currentTab']="Online";
	if($_SESSION['type'] == 'hod')
{
      include_once('sidebar_hod.php');

}elseif ($_SESSION['type']=='cod' || $_SESSION['type']=='com' ) {
        include_once('sidebar_cod.php');
}
else{
    include_once('sidebar.php');
}
 
	//$sqlses = $_SESSION['sql'] ;
	//$mysqli=mysqli_query($conn,$sqlses);
	
 
?>

<!DOCTYPE HTML>
<html>
<head>
</head>
<body>
<section class="content">
  <div class="row">
    <!-- left column -->
    <div class="col-xs-12">
      <!-- general form elements -->
	  			  <br/><br/><br/>

      <div class="box box-primary">
        <div class="box-header with-border">
          <h1 class="box-title" style="display:block ; margin-left:17% ">Charts</h1>
        </div><!-- /.box-header -->
		<div style="text-align:right">
			<a href="menu.php?menu=5 "> <u>Back to Online Course Attended Activities Menu</u></a>
			<h2 hidden style="margin-left: 25%; "> Courses Organised</h2>
			<div class="col-md-3" style="display:block ; margin-left:25% " >
				<label for="type">Select Type:*</label>
				<select required name='type' id='type' class='form-control input-lg'>
					<option value="">Select your choice</option>
					<option value="online">Online</option>
					<option value="offline">Offline</option>
				</select>
				<br>
				<input type="submit" class="btn btn-primary" name="submit" value = "Submit" onclick="validate()">
				<br>	
			</div>
		</div>
	</div>
	  </div>
	</div>
</section>
<script type="text/javascript">
	function validate(){
		var e = document.getElementById("type");
		var str = e.options[e.selectedIndex].value;
		if(str == ""){
			window.alert("Error-Please select a value");
		}else{
			var wheretogo = "chart_faculty.php?types="+str;
			window.location.href = wheretogo;
		}
	}
</script>
</body>

<?php include_once('footer.php'); ?> 
</html>
