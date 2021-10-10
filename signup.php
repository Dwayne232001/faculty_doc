
<?php
ob_start();
session_start();
?>
<?php include_once('head.php'); ?>


<head>

<style>
input{
border-radius:5px;
 
}


input[type='text'] {
  width:300px;
height:30px 
}
input[type='email'] {
  width:300px;
height:30px 
}
input[type='password'] {
  width:300px;
height:30px 
}
.pagedesign{
	font-weight:bold;
	font-size:1.1em;
	margin-top:5px;
	margin-right:5px;
}
.error
{
	color:red;
	border:1px solid red;
	background-color:#ebcbd2;
	border-radius:10px;
	margin:5px;
	margin-left:25.0%;
	padding:5px;
	font-family:Arial, Helvetica, sans-serif;
	width:510px;
}

.noerror
{
	color:green;
	border:1px solid green;
	background-color:#d7edce;
	border-radius:10px;
	margin:5px;
	padding:5px;
	font-family:Arial, Helvetica, sans-serif;
	width:500px;
	height:40px;
}
body, html {
    height: 200%;
    margin: 0;
}

.bg {
    /* The image used */
    //background-image: url("images/blue-color-background-wallpaper-4.jpg");
	//background-color:grey;

    /* Full height */
    //height: 100%; 

    /* Center and scale the image nicely */
    //background-position: center;
    //background-repeat: no-repeat;
    //background-size: cover;
	background-image: linear-gradient(to right, #1A237E, #673A90);	

}
.error1
{
	color:#ff0000;
}
.box{
	margin: 0 auto;
	margin-top:0%;
	margin-left:25.5%;
}
.btn{
	width:80px;
}
</style>
</head>
<?php
$result='';
$result1='';
$success=0;
$flag=0;
if(isset($_POST['sign']))
{
if(empty($_POST['fn']))
{
$result=$result."Faculty name cannot be empty<br>";
$flag=1;
}
if(empty($_POST['em']))
{
	$result=$result."Email id is neccessary<br>";
	$flag=1;
}
/*if(empty($_POST['mobile']))
{
	$result=$result."Mobile number cannot be empty<br>";
	$flag=1;
}*/
if(empty($_POST['pass']))
{
	$result=$result."Password cannot be empty<br>";
	$flag=1;
}
if(!empty($_POST['fn']))
{
	if(!preg_match("/^[a-zA-Z ]*$/",$_POST['fn']))
	{
		$result=$result."Only Letters are allowed in Faculty Name<br>"; 
		$flag=1;
	}
}
include_once("includes/connection.php");

if(!empty($_POST['mobile']))
{
if(strlen($_POST['mobile'])!=10 || !preg_match('/^[6-9]\d{9}$/', $_POST['mobile']))
	{
		$result= $result."Invalid Mobile Number<br>"; 
	  $flag=1;
	}
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

if(!empty($_POST['em']))
{

if (!preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/",$_POST['em']))
	{
      $result= $result."Invalid Email<br>"; 
	  $flag=1;
}
else
{
	$formusername = $_POST['em'];
	$query="SELECT * from facultydetails where Email='$formusername'";
	$sql=mysqli_query($conn,$query);
		if(mysqli_num_rows($sql)>0)
		{
			//echo "<script>alert('Hello')</script>";
			      $result1 =$result1."Email-ID exists";
				  $flag=2;
		}
}
}


if($flag!=1 && $flag!=2)
{

	$fname=test_input($_POST['fn']);
	$eid=test_input($_POST['em']);
	$mob=test_input($_POST['mobile']);
	$pass=$_POST['pass'];
	$dept=$_POST['dept'];

	
	/*if($eid == 'hodextc@somaiya.edu')
	{
		$hashPassword = base64_encode($pass);
	}
	else
	{*/
		$options = array("cost"=>4);
		$hashPassword = password_hash($pass,PASSWORD_BCRYPT,$options);
	//}
	
$sql="INSERT INTO facultydetails(F_NAME,Mobile,Email,Password,Dept) values('$fname','$mob','$eid','$hashPassword','$dept')";
if(mysqli_query($conn,$sql))
{
	echo "<script>alert('Sign Up successful')</script>";
	$success=1;
	$_SESSION['success'] = $success;
}
else{	
echo '<div class="error">'.mysqli_error($conn).'</div>';
//echo '<script> alert("Error, Try  again ") </script>';
}
	


}
if($success==1)
{
	header("Location:index.php?alert=success1");
}

}

if(isset($_POST['cancel']))
{
	header("Location:index.php");
}

?>

<body class="bg">
<div class="" >

<!-- Content Wrapper. Contains page content -->

<!-- Main content -->
        <section class="content">
		<img src="images/somaiyalogo.png" height="100" style="margin-left:30px;" />

	<!--	<img src="images/trust.png" height="70" alt="Trust" style="margin-left:1050px;"/> -->

		
		<h2 align="center" style="color:white; font-family:Times New Roman; margin-top:-80px; word-spacing:3px;" >	K J Somaiya College of Engineering , Vidyavihar, Mumbai-77</h2>
		<h3 align="center" style="color:white; font-family:Times New Roman; margin-top:-5px" >	(Autonomous College Affiliated to University of Mumbai)</h3>
		<h3 align="center" style="color:orange; font-family:Times New Roman; margin-top:-2px; font-size:30px;" >Faculty Activities Details</h3>

		<div class="row" style="width:800px; margin:0 auto;"  >

			<div class="col-md-8">
              <div class="box box-primary" >
                <div class="box-header with-border">
                  <h3 class="box-title">Sign Up</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
				<span class="error1"><b>* Required field</b></span><br>
				 <form role="form" action="" method="post">
				     
						
						&nbsp;&nbsp;<b>Enter your name:&nbsp </b><span class="error1"><b> *</b></span><br>&nbsp <input required style="width:310px" type="text" placeholder="  FirstName  MiddleName  LastName" name="fn" value="<?php if(isset($_POST['sign']) && $flag==1){echo $_POST['fn'];} ?>">
						<br><br>
 					&nbsp;&nbsp;<b>Email ID(Enter somaiya id) :&nbsp </b><span class="error1"><b>*</b></span><br>&nbsp <input required style="width:310px" type = "email" placeholder="  Email id" name = "em"  value="<?php if(isset($_POST['sign']) && $flag==1){echo $_POST['em'];} ?>" ><br><br>
					<div class="input-group paddingTop chwidth">
					&nbsp;&nbsp;<b>Mobile Number :&nbsp </b><span class="error1"><b></b></span><br>&nbsp 
					<span class="input-group-addons">+91&nbsp </span><input style="width:286px"  type = "text" placeholder="  Mobile number" name = "mobile"  value="<?php if(isset($_POST['sign']) && $flag==1){echo $_POST['mobile'];} ?>" >
						
					</div>
					<br>
					<b>&nbsp Password : &nbsp </b><span class="error1"><b>*</b></span><br>&nbsp <input required style="width:310px" type="password" id="pwinput" placeholder="  Password" name="pass"  value="<?php if(isset($_POST['sign']) && $flag==1){echo $_POST['pass'];} ?>"> &nbsp;&nbsp;<input type="checkbox" id="pwcheck" />&nbsp Show Password <br><span id="pwtext"></span><br>
					<b>&nbsp Confirm Password : &nbsp </b><span class="error1"><b>*</b></span><br>&nbsp <input required style="width:310px" type="password" placeholder="  Confirm Password" name="pass2"  value="<?php if(isset($_POST['sign']) && $flag==1){echo $_POST['pass2'];} ?>">
					<br><br>
					
						<label for="dept">Select Department: <span class="error1"><b>*</b></span></label>
						<select required name='dept' id='dept' class='form-control'>
							<option value=''>Select your Department </option>
							<option value="comp">Computer Engineering</option>
							<option value="extc">Electronics and Telecommunication Engineering</option>
							<option value="etrx">Electronics Engineering</option>
							<option value="mech">Mechanical Engineering</option>
							<option value="it">IT Engineering</option>
							<option value="sci">Science and Humanities</option>
						
						</select>
					
					<br><br>
					
					<input type = "submit" class = "btn btn-primary"  value = "Sign Up" name = "sign">
						 <a href="../dd/index.php" type = "button" class="btn btn-primary" class="btn ">Cancel</a>
					
					<br><br>
					
                </div>
              </div>
			  <?php
			  if($flag==2)
			{
				echo '<div class="error">'.$result1.'</div>';
			}
		if($flag==1 && $flag!=2)
			{
				echo '<div class="error">'.$result.'</div>';
			}
						
				?>

             </div>
			 </div>
            </div>
          </section>
                
		</div> 
 </body>
<?php
function test_input($data)
{
	$data=trim($data);
	$data=stripslashes($data);
	$data=htmlspecialchars($data);
	
	return $data;
}
echo '<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script>
$(document).ready(function(){

    $("#pwinput").focus();

    $("#pwcheck").click(function(){
        var pw = $("#pwinput").val();
        if ($("#pwcheck").is(":checked"))
        {
            $("#pwtext").text(pw);
        }
        else
        {
            $("#pwtext").text("");
        }

    });
});
</script>';

?>
<?php






?>


   

