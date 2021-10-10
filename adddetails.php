<?php
ob_start();
session_start();
include_once('head.php'); 
 include_once('header.php'); 
//check if user has logged in or not

if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
//connect ot database
include_once("includes/connection.php");

if(isset($_SESSION['type'])){
	if($_SESSION['type'] != 'hod'){
		session_destroy();
    //if not hod then send the user to login page
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


//include custom functions files 
include_once("includes/functions.php");
include_once("includes/scripting.php");



//setting variables
$result='';
$result1='';
$success=0;
$success1=0;
$flag=0;
$f=0;	
$fn = $mobile = "";
$display = 1;
//$faculty_email= $_SESSION['loggedInEmail'];
$faculty_email = "";

//echo '<script>alert("Hello")</script>';

/*if(isset($_SESSION['loggedInEmail']) && ($display == 1))
{
	$display = 0;
	//echo '<script>alert("Hello")</script>';
	$faculty_email = $_SESSION['loggedInEmail'];
	$query = "SELECT * from facultydetails where Email = '$faculty_email'";
    $result2 = mysqli_query($conn,$query);
    $row = mysqli_fetch_assoc($result2);
	$fn = $row['F_NAME'];
	$mobile = $row['Mobile'];
}*/

//check if the form was submitted
if(isset($_POST['sign']))
{
	$display = 0;
	
	if(!empty($_POST['fn']))
	{
		if(!preg_match("/^[a-zA-Z ]*$/",$_POST['fn']))
		{
			$result=$result."Only Letters are allowed in Faculty Name<br>"; 
			$f=1;
		}
	}
	
	include_once("includes/connection.php");

	if(!empty($_POST['mobile']))
	{
		if(strlen($_POST['mobile'])!=10 || !preg_match('/^[6-9]\d{9}$/', $_POST['mobile']))
		{
			$result= $result."Invalid Mobile Number<br>"; 
			$f=1;
		}
	}

	if(!empty($_POST['empid']))
	{
		if(strlen($_POST['empid'])!=6 )
		{
			$result= $result."Employee ID should be 6 digits<br>"; 
			$f=1;
		}
	}
	/*if(!empty($_POST['pass']) && !empty($_POST['pass2']))
	{
		if(strcmp($_POST['pass'],$_POST['pass2'])!=0)
		{
			$result= $result."Passwords do not match , Please Re enter<br>"; 
			$flag=1;
		}
	}
	if(!empty($_POST['pass']))
	{
		if(empty($_POST['pass2']))
		{
			$result= $result."Please Confirm Password<br>"; 
		$flag=1;
		}
	}*/

	if(!empty($_POST['em1']))
	{
		if (!preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/",$_POST['em1']))
		{
			$result= $result."Invalid Email Format<br>"; 
			$f=1;
		}
		else
		{
			$formusername = $_POST['em1'];
			$query="SELECT * from facultydetails where Email='$formusername'";
			$sql=mysqli_query($conn,$query);
			if(mysqli_num_rows($sql)>1)
			{
				//echo "<script>alert('Hello')</script>";
			    $result1 ="There is more than 1 entry of this Email";
				$f=2;
			}
			/*else if(mysqli_num_rows($sql)==1)
			{
				//echo "<script>alert('Hello')</script>";
			    $result1 ="Email exists";
				$f=2;
			}*/
		}
	}
	

	if($f!=1 && $f!=2)
	{

		$fname=test_input($_POST['fn']);
		$eid=$_SESSION['loggedInEmail'];
		$eid1=test_input($_POST['em1']);
		$mob=test_input($_POST['mobile']);
		$dept =test_input($_POST['Dept']);
		$empid = test_input($_POST['empid']);
		$pass= 'kjsce';
		$type = 'faculty';
		$options = array("cost"=>4);
		$hashPassword = password_hash($pass,PASSWORD_BCRYPT,$options);
		$altname = test_input($_POST['alt_name']);		
	
		$sql="INSERT INTO facultydetails(F_NAME,Mobile,Email,Password,Dept,type,Fac_ID,alt_name) values('$fname','$mob','$eid1','$hashPassword','$dept','$type','$empid','$altname')";
		if(mysqli_query($conn,$sql))
		{
			echo "<script>alert('Sign Up successful with default password as - kjsce, please notify faculty')</script>";
			$success=1;
		}
		
		else
		{	
			echo '<div class="error">'.mysqli_error($conn).'</div>';
			//echo '<script> alert("Error, Try  again ") </script>';
		}
}
	if($success==1 )
	{
		header("Location:viewdetails.php?alert=success");
		
	}
}




//close the connection
mysqli_close($conn);
?>






<?php 
if($_SESSION['type'] == 'hod')
{
	  include_once('sidebar_hod.php');

}elseif ($_SESSION['type']=='cod' || $_SESSION['type']=='com' ) {
		include_once('sidebar_cod.php');
}
else{
	include_once('sidebar.php');
}

?>
<style>
.error
{
	color:red;
	border:1px solid red;
	background-color:#ebcbd2;
	border-radius:10px;
	margin:5px;
	padding:0px;
	font-family:Arial, Helvetica, sans-serif;
	width:510px;
}
.colour
{
	color:#ff0000;
}
.demo {
	width:120px;
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
						  <br/><br/><br/>

            <!-- general form elements -->
				<div class="box box-primary">
					<div class="box-header with-border">
						<div class="icon">
						<i style="font-size:20px" class="fa fa-edit"></i>
						<h3 class="box-title"><b>Add Faculty Profile Details</b></h3>
						<br>		
						</div>
					</div><!-- /.box-header -->
					<!-- form start -->
					<form role="form" method="POST" class="row" action ="" style= "margin:10px;" >
					<input type = 'hidden' name ='id' value = '<?php echo $id; ?>'>

					<div class="form-group col-md-8">
							<label for="name">Faculty ID</label>
							<span class="colour"><b> *</b></span>
							<input required type="number" class="form-control input-lg"  placeholder="6 digit Employee ID" name="empid" value="">
					</div>

						<div class="form-group col-md-8">
							<label for="name">Faculty Name</label>
							<span class="colour"><b> *</b></span>
							<input required type="text" class="form-control input-lg"  placeholder="FirstName  MiddleName  LastName" name="fn" value="">
						</div>
						<br>

						<div class="form-group col-md-8">
							<label for="name">Alternate Faculty Name (If Any)</label>
							<input type="text" class="form-control input-lg"  placeholder="FirstName  MiddleName  LastName" name="alt_name" value="">
						</div>
						<br>

						<div class="form-group col-md-8">
							<label for="name">Faculty Department</label>
							<span class="colour"><b> *</b></span>
							<input required type="text" class="form-control input-lg"  placeholder="Department" name="Dept" value="">
						</div>
						<br>

						<div class="form-group col-md-8">
							<label for="email">Faculty Email Address</label>
							<span class="colour"><b> *</b></span>
							<input required autofocus type="email" class="form-control input-lg"  placeholder="New Email Address" name="em1" value="">
						</div> 
						<br>
						<div class="form-group col-md-8">
							<b>Faculty Mobile Number </b>
							<span class="colour"><b> *</b></span>
							<input class="form-control input-lg" type = "text" placeholder="Mobile Number" name = "mobile" value="">
						</div>	
					
					<div class="form-group col-md-8">  </div>
						<br/>
						<div class="form-group col-md-12">	
						 <input type = "submit" class = "demo btn btn-success btn-lg"  value = "Add" name = "sign">
	                         <a href="list_of_activities_user.php" type="button" class="demo pull-right btn btn-warning btn-lg">Cancel</a>
					 
									
						</div>
					</form>
                </div>				
            </div>
        </div> 
		   <?php
				if($flag==3)
				{
					echo '<div class="error">'.$result1.'</div>';
				}
				if($f==2)
				{
						echo '<div class="error">'.$result1.'</div>';
				}
				if($f==1 && $flag!=3)
				{
					echo '<div class="error">'.$result.'</div>';
				}	
			?>				
    </section>    
</div>
<?php
function test_input($data)
{
	$data=trim($data);
	$data=stripslashes($data);
	$data=htmlspecialchars($data);
	
	return $data;
}
?>
   
<?php include_once('footer.php'); ?>
   
   