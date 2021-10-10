<?php
ob_start();
session_start();
include_once('head.php'); 
 include_once('header.php'); 
//check if user has logged in or not

if(!isset($_SESSION['loggedInUser']))
{
    //send the iser to login page
    header("location:index.php");
}
//connect ot database
include_once("includes/connection.php");

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

if(isset($_SESSION['loggedInEmail']) && ($display == 1))
{
	$display = 0;
	//echo '<script>alert("Hello")</script>';
	$faculty_email = $_SESSION['loggedInEmail'];
	$query = "SELECT * from facultydetails where Email = '$faculty_email'";
    $result2 = mysqli_query($conn,$query);
    $row = mysqli_fetch_assoc($result2);
	$fn = $row['F_NAME'];
	$mobile = $row['Mobile'];
}

//check if the form was submitted
if(isset($_POST['sign']))
{
	$display = 0;
	if(empty($_POST['fn']))
	{
		$result=$result."Faculty name cannot be empty<br>";
		$f=1;
	}
	if(empty($_POST['em1']))
	{
		$result=$result."Email id is neccessary<br>";
		$f=1;
	}
	if(empty($_POST['mobile']))
	{
		$result=$result."Mobile number cannot be empty<br>";
		$f=1;
	}
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
		//$formusername = $_POST['em'];
		$query="SELECT Email from facultydetails where Email='$faculty_email'";
		$sql=mysqli_query($conn,$query);
		if(mysqli_num_rows($sql)==1)
		{
			//echo "<script>alert('Hello')</script>";
			//$result1 =$result1."User exists";
			$flag=2;
		}
		else
		{
			$result1 =$result1."Email does not exist or duplicate entry";
			$flag=3;
		}			
	}

	if($f!=1 && $flag==2 && $f!=2)
	{

		$fname=test_input($_POST['fn']);
		$eid=$_SESSION['loggedInEmail'];
		$eid1=test_input($_POST['em1']);
		$mob=test_input($_POST['mobile']);
	
		/*if($eid == 'hodextc@somaiya.edu')
		{
			$hashPassword = base64_encode($pass);
		}
		else
		{/*
			$options = array("cost"=>4);
			$hashPassword = password_hash($pass,PASSWORD_BCRYPT,$options);
		}*/
	
		$sql="UPDATE facultydetails set Mobile ='$mob' where Email='$eid'";
		if(mysqli_query($conn,$sql))
		{
			//echo "<script>alert('Sign Up successful')</script>";
			$success=1;
			$_SESSION['success'] = $success;
		}
		else
		{	
			echo '<div class="error">'.mysqli_error($conn).'</div>';
			//echo '<script> alert("Error, Try  again ") </script>';
		}
		
		$sq2="UPDATE facultydetails set Email ='$eid1',F_NAME = '$fname' where Email='$eid'";
		if(mysqli_query($conn,$sq2))
		{
			//echo "<script>alert('Sign Up successful')</script>";
			$success1=2;
			$_SESSION['success1'] = $success1;
		}
		else
		{	
			echo '<div class="error">'.mysqli_error($conn).'</div>';
			//echo '<script> alert("Error, Try  again ") </script>';
		}
}
if($success==1 && $success1==2)
{
	header("Location:list_of_activities_user.php");
	$_SESSION['username'] = $eid1;
	$_SESSION['loggedInEmail'] = $eid1;
	$_SESSION['loggedInUser'] = $fname;
}
}
if(isset($_POST['cancel']))
{
	header("Location:list_of_activities_user.php");
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
</style>
<div class="content-wrapper">  
	<section class="content">
		<div class="row">
        <!-- left column -->
            <div class="col-md-6 ">
						  <br/><br/><br/>

            <!-- general form elements -->
				<div class="box box-primary">
					<div class="box-header with-border">
						<div class="icon">
						<i style="font-size:20px" class="fa fa-edit"></i>
						<h3 class="box-title"><b>Update Profile Details</b></h3>
						<br>
						</div>
					</div><!-- /.box-header -->
					<!-- form start -->
					<form role="form" method="POST" class="row" action ="" style= "margin:10px;" >
					<input type = 'hidden' name ='id' value = '<?php echo $id; ?>'>
						<div class="form-group col-md-8">
							<label for="name">Your name *</label>
							<input required type="text" class="form-control input-lg"  placeholder="  FirstName  MiddleName  LastName" name="fn" value="<?php echo $fn; ?>">
						</div>
						<br>
						<div class="form-group col-md-8">
							<label for="email">Your email id *</label>
							<input required autofocus type="email" class="form-control input-lg"  placeholder="  New Email id" name="em1" value="<?php echo $faculty_email; ?>">
						</div> 
						<br>
						<div class="form-group col-md-8">
							<b>Your mobile Number *&nbsp </b>
							<input class="form-control input-lg" type = "text" placeholder="  Mobile number" name = "mobile" value="<?php echo $mobile; ?>">
						</div>	
						<br/>
						<div class="form-group col-md-12">	
						 <input type = "submit" class = "demo btn btn-success btn-lg"  value = "Update" name = "sign">
						 <input style="margin-left:14%;" type = "submit" class = "demo btn btn-warning btn-lg"  value = "Cancel" name = "cancel">			
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
   
   