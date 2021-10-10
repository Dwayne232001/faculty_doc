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

$faculty_email= $_SESSION['loggedInEmail'];

//setting variables
$result='';
$flag=0;


if(isset($_POST['submit']) )
	{
		$passwd1=$_POST['pass'];
		$passwd2=$_POST['pass2'];
			
		if(empty($_POST['pass']))
		{
			$result=$result."Password cannot be empty<br>";
			$flag=1;
		}
		
		if(!empty($_POST['pass']) && !empty($_POST['pass2']))
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
		}
		
		if($flag == 0)
		{
			if($passwd1 == $passwd2)
			{
				$str="0123456789qwertyuiopasdfghjklzxxcvbnm";
				$str=str_shuffle($str);
				$str=substr($str,0,10);
				$options = array("cost"=>4);
				$hashPassword = password_hash($passwd1,PASSWORD_BCRYPT,$options);	
				$sql1="select Email from facultydetails where Email='$faculty_email'";
				$result1=$conn->query($sql1);
				if(($result1->num_rows)==1)
				{
					$sql2="UPDATE facultydetails set Password='$hashPassword' where Email='$faculty_email'";
					if(mysqli_query($conn,$sql2))
					{
						header("Location:list_of_activities_user.php");
					}
				
					else
					{
						header("Location:list_of_activities_user.php");
					}
				}
				else
				{
							$result = $result."Email does not exist or duplicate entry";
							$flag=1;
				}			
			}
		}	
	}
	if(isset($_POST['cancel']) )
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
            <!-- general form elements -->
						  <br/><br/><br/>

				<div class="box box-primary">
					<div class="box-header with-border">
						<div class="icon">
						<i style="font-size:20px" class="fa fa-edit"></i>
						<h3 class="box-title"><b>Reset Password</b></h3>
						<br>
						</div>
					</div><!-- /.box-header -->
					<!-- form start -->
					<div class="form-group col-md-6">
                        <label for="faculty-name">Your Registered Email Id:</label>
                        <input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName" value="<?php echo $faculty_email; ?>" readonly>
                    </div><br/> <br/> <br/> <br/>  
					<form role="form" method="POST" class="row" action ="" style= "margin:10px;" >
						<div class="form-group col-md-8">
							<label for="password">Enter New Password</label><span class="colour"><b> *</b></span>
							<input  id="pwinput" type="password" class="form-control input-lg"  placeholder="  New Password" name="pass"><br> <input type="checkbox" id="pwcheck" onclick="myFunction()"/>&nbsp Show Password<br><span id="pwtext"></span>
						</div>
						<br>
						<div class="form-group col-md-8">
							<label for="password">Confirm Password</label><span class="colour"><b> *</b></span>
							<input  type="password" class="form-control input-lg"  placeholder="  Confirm Password" name="pass2">
						</div> 
						<br>
						<br/>
						<div class="form-group col-md-12">	
						 <input type = "submit" class = "demo btn btn-success btn-lg"  value = "Reset" name = "submit">
						 <input style="margin-left:14%;" type = "submit" class = "demo btn btn-warning btn-lg"  value = "Cancel" name = "cancel">			
						</div>
					</form>
                </div>				
            </div>
        </div> 
		   <?php
				if($flag==1)
				{
					echo '<div class="error">'.$result.'</div>';
				}
			?>				
    </section>    
</div>

<script>
	function myFunction() {
  var x = document.getElementById("pwinput");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
</script>
<?php include_once('footer.php'); ?>
   
   