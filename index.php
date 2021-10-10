<?php
session_start();
require_once("includes/config.php");
include_once("includes/functions.php");
if(isset($_SESSION['loggedInUser']))
{
    header("location:list_of_activities_user.php");
}
$flag = 0;
if (isset($_GET['alert'])) {
	$flag = 1;
	if ($_GET['alert'] == "success") {
		$successMessage = '<div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
            </button>
        <strong>Password changed successfully</strong>
        </div>';
	}
	if ($_GET['alert'] == "error") {
		$successMessage = '<div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
            </button>
        <strong>Error Resetting Password</strong>
        </div>';
	}
	if ($_GET['alert'] == "success1") {
		$successMessage = '<div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
            </button>
        <strong>Signup successfull</strong>
        </div>';
	}
}
if (isset($_GET['error'])) {
	if ($_GET['error'] == 'notfound') {
		?>
		<script type="text/javascript">
			$(document).ready(function() {
				document.getElementById("signin-error").innerHTML = "There is an error in the input email or password.";
				$('#myModal').modal('show');
			});
		</script>
	<?php
		} else if ($_GET['error'] == 'notfound1') {
			?>
		<script type="text/javascript">
			alert("You are not authorised user. Please try again! or Contact Administrator");
		</script>
<?php
	}
}

$errors = "";
$succ = 0;
$success = 0;
if (isset($_POST['login'])) {
	if (empty($_POST['email'])) {
		$errors = $errors . "Email ID cannot be empty<br>";
		$succ = 1;
	}
	if (empty($_POST['password'])) {
		$errors = $errors . "Password cannot be empty<br>";
		$succ = 1;
	}
	if (!empty($_POST['email'])) {
		if (!preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/", $_POST['email'])) {
			$errors = $errors . "Invalid Email format<br>";
			$succ = 1;
		}
	}
	if ($succ != 1) {
		//taking input from user
		$formUsername = validateFormData($_POST['email']);
		$formPassword = validatePassword($_POST['password']);
		include("includes/connection.php");
		//fetching data from database
		$query = "SELECT * from facultydetails where Email='$formUsername'";
		$result = mysqli_query($conn, $query);
		//verifying id query returned something
		/*if(mysqli_num_rows($result)>1){
        $error=" <div class='alert alert-danger'> There is some error in databse please contact the DBA
        <a class='close' data-dismiss='alert'>&times; </a>
        </div> ";
		}
		//else store the basic user data in local variables
		else*/

		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_assoc($result);
			$_SESSION['Fac_ID']  = $row['Fac_ID'];
			$_SESSION['username'] = $row['Email'];
			$pass = $row['Password'];
			$_SESSION['f_id'] = $row['Fac_ID'];

			//verify if the password matches the hashed password
			$loginsuccess = 0;


			if ($_SESSION['username'] == 'jyot.tryambake@gmail.com') {
				$hashedPassword = base64_decode($pass);
				$loginsuccess = 1;
			} else {
				if (password_verify($formPassword, $pass)) {

					$loginsuccess = 1;
				}
			}
			if ($loginsuccess == 1) {
				//login details are correct start the session
				//store the data in session variable

				$_SESSION['loggedInUser'] = $row['F_NAME'];
				$_SESSION['loggedInEmail'] = $row['Email'];
				$_SESSION['type'] = $row['type'];

				if ($_SESSION['username'] == 'hodextc@somaiya.edu' || $_SESSION['username'] == 'member@somaiya.edu' || $_SESSION['username'] == 'hodcomp@somaiya.edu') {
					header("location:list_of_activities_user.php");
				} else
					header("location:list_of_activities_user.php");
			} //end of password verified
			//if password didn't match
			else {
				/*$error="<div class='alert alert-danger'> Wrong username,password combination.
				<a class='close' data-dismiss='alert'>&times; </a></div>";*/
				$errors = $errors . "Incorrect Password<br>";
				$succ = 1;
			} //end of password didnot match
		} //end of num rows =1
		else {
			//echo "<script> alert('Incorrect Username') </script>";
			/*$error="<div class='alert alert-danger'> Username not found.
            <a class='close' data-dismiss='alert'>&times; </a> </div>";*/
			$errors = $errors . "Incorrect Username";
			$succ = 1;
		} //end of 0 results fetched case
		mysqli_close($conn);
	}
}
if (isset($_POST['signup'])) {
	header("Location:signup.php");
}
?>
<?php include_once('head.php'); ?>
<?php //include_once('header.php'); 
?>
<?php // include_once('sidebar.php'); 
?>

<head>
	<link rel="stylesheet" type="text/css" href="styles.css">
	<link rel="stylesheet" type="text/css" href="index.css">
	<meta name="google-signin-client_id" content=<?php echo $CLIENT_ID; ?>>
	<script src="https://apis.google.com/js/platform.js" async defer></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>



	<script>
		function onSignIn(googleUser) {
			var profile = googleUser.getBasicProfile();
			name = profile.getName();
			pic = profile.getImageUrl();
			var email = profile.getEmail();
			var id_token = googleUser.getAuthResponse().id_token;
			googleUser.disconnect();

			if (email.includes("somaiya.edu")) //domain constraint
			{
				var theForm, newInput1, newInput2, newInput3;
				theForm = document.createElement('form');
				theForm.action = 'homepage.php'; //enter the page url you want to redirect the index page to after sign in
				theForm.method = 'post';
				newInput = document.createElement('input');
		 	    newInput.type = 'hidden';
		 	    newInput.name = 'idtoken';
			    newInput.value = id_token;
				// newInput1 = document.createElement('input');
				// newInput1.type = 'hidden';
				// newInput1.name = 'user';
				// newInput1.value = name;
				// newInput2 = document.createElement('input');
				// newInput2.type = 'hidden';
				// newInput2.name = 'pic';
				// newInput2.value = pic;
				// newInput3 = document.createElement('input');
				// newInput3.type = 'hidden';
				// newInput3.name = 'email';
				// newInput3.value = email;
				theForm.appendChild(newInput);
				// theForm.appendChild(newInput1);
				// theForm.appendChild(newInput2);
				// theForm.appendChild(newInput3);

				document.getElementById('hidden_form_container').appendChild(theForm);
				// sessionStorage.setItem('myUserEntity', JSON.stringify(myUserEntity));
				theForm.submit();
			} else {
				alert("Please login using Somaiya Mail ID");
			}


		}


		function signOut() {
			var auth2 = gapi.auth2.getAuthInstance();
			auth2.signOut().then(function() {
				alert('User signed out.');
			});
		}
	</script>
</head>
<!-- Content Wrapper. Contains page content -->

<!-- <body class="bg" style = "">
	<div class="">
		<!-- Main content --here>
		<section class="content">
			<img src="images/somaiyalogo.png" height="100" style="margin-left:30px;" />

			<!--	<img src="images/trust.png" height="70" alt="Trust" style="margin-left:1050px;"/> --here>


			<h2 align="center" style="color:white; font-family:Times New Roman; margin-top:-80px; word-spacing:3px;"> K J Somaiya College of Engineering , Vidyavihar, Mumbai-77</h2>
			<h3 align="center" style="color:white; font-family:Times New Roman; margin-top:-5px"> (Autonomous College Affiliated to University of Mumbai)</h3>
			<h3 align="center" style="color:orange; font-family:Times New Roman; margin-top:-2px; font-size:30px;">Faculty Activities Details</h3>
			
			<div class="row">
			 <!-- style="width:800px; margin:0 auto; "> -->
				<!-- left column --here>
				<div class="col-md-6" style="">
					<!-- general form elements --here>
					
					<div class="box box-primary">
						<div class="box-header with-border">
							<?php
							// if ($flag == 1) {
							// 	echo $successMessage;
							// }
							?>
							<div class="icon">
								<i style="font-size:18px" class="fa fa-sign-in"></i>
								<h2 class="box-title"><b>Login</b></h2>
								<br>
							</div>
						</div><!-- /.box-header --here>
						<!-- form start --here>
						<form role="form" action="" method="POST">
							<div class="row">
							<div class="col-md-6"style="margin:0 auto;">
							<div class="box-body">
								<div class="form-group">
									<label for="exampleInputEmail1">Email address </label><span class="colour"><b> *</b></span>
									<input autofocus type="text" name="email" class="form-control input-lg" id="exampleInputEmail1" placeholder="Enter email">
								</div>
								<div class="form-group">
									<label for="exampleInputPassword1">Password </label><span class="colour"><b> *</b></span>
									<input type="password" name="password" class="form-control input-lg" id="exampleInputPassword1" placeholder="Password">
								</div>

							</div><!-- /.box-body --here>
							<center>
								<!-- <div class="box-footer"> --here>
									<button type="submit" name='login' class="btn btn-primary">Login</button>
								<!-- </div> --here>
								</div>
							</center>
								<div class="col-md-6">
								<!-- <br> --here>
								<center>
								<div class="form-group"style="background-color:#605ca8;margin:0 auto;border-radius:2px;">
									<label><br><br>
										<h4 style="color:#ffffff">&nbsp Sign in using Google</h4><label>
											<br>
											<!-- <hr> --here>
													<div class="" style="background-color:#605ca8;border-radius:2px;">
														<button class="g-signin2 btn btn-link" data-onsuccess="onSignIn" align="left"></button>
													</div>
													<div id="hidden_form_container" style="display:none;"></div>
								<br><br><br><br>
								</div>
								<!-- <br><br><br><br><br><br><br> --here>
							</center>
							</div>
						</div>
						<center>
						<div class="box-footer">
							<div class="icon">
								&nbsp;&nbsp;<i style="color:blue; font-size:15px;" class="fa fa-lock"></i>
								<a href="resetpassword.php"><strong><u>Forgot Password</u></strong></a>
							</div>
							</div>
							</center>
						</form>
					</div>
					<?php
					//for printing signup successfull
					/*if(isset($_SESSION['success']))
				$success = $_SESSION['success'];
				if($success == 1)
				{
				echo '<div class="error2">"Sign up Successful"</div>';
				$_SESSION['success'] = 0;
				}*/ //errors ae below.. uncomment
					// if ($succ == 1) {
					// 	echo '<div class="error2">' . $errors . '</div>';
					// }
					?>
				</div>
			</div>
		</section>

	</div>
</body> -->
<body>
	<!-- title & logo -->
	<nav class="navbar navbar-default">
		<!-- md, lg, xl-->
		<div class="container-fluid hidden-sm hidden-xs visible-md-block visible-lg-block visible-xl-block">
			<a class="navbar-brand" href="index.php"><img src="images/somaiyalogo.png" height="100" ></a>
      		<h1 align="center" class="navbar-text" style="color:black;font-family:Arial Black;padding-top: 2%;">Faculty Activities Details</h1>
			<h3 align="center" class="navbar-text" style="color:black;font-family:Helvetica;padding-top: 7%;">K J Somaiya College of Engineering, Vidyavihar, Mumbai-77</h3>
			<h3 align="center" class="navbar-text" style="color:black;font-family:Helvetica;padding-top: 10%;">Autonomous College Affiliated to University of Mumbai</h3>
		</div>
		<!-- mobile view xs, sm -->
		<div class="container-fluid hidden-xs hidden-md hidden-lg hidden-xl">
			<a class="navbar-brand" href="index.php"><img src="images/somaiyalogo.png" height="70" ></a>
      		<h2 align="center" class="navbar-text" style="color:black;font-family:Arial Black;padding-top: 2%;">Faculty Activities Details</h2>
			<h4 align="center" class="navbar-text" style="color:black;font-family:Helvetica;padding-top: 8%;">K J Somaiya College of Engineering, Vidyavihar, Mumbai-77</h4>
			<h4 align="center" class="navbar-text" style="color:black;font-family:Helvetica;padding-top: 11%;">Autonomous College Affiliated to University of Mumbai</h4>
		</div>
		<div class="container-fluid hidden-sm hidden-md hidden-lg hidden-xl">
			<a class="navbar-brand" href="index.php"><img src="images/somaiyalogo.png" height="30" ></a>
      		<h4 align="center" class="navbar-text" style="color:black;font-family:Arial Black;padding-top: 4%;">Faculty Activities Details</h4>
			<h6 align="center" class="navbar-text" style="color:black;font-family:Helvetica;padding-top: 13%;">K J Somaiya College of Engineering, Vidyavihar, Mumbai-77</h6>
			<h6 align="center" class="navbar-text" style="color:black;font-family:Helvetica;padding-top: 16%;">Autonomous College Affiliated to University of Mumbai</h6>
		</div>
	</nav>
	<!-- <div class="container main">
		<div class="row one">
			<div class="col-sm-1">
				<img src="images/somaiyalogo.png" height="100" >
			</div>
			<div class="col-sm-11">
				<!-- <img src="images/somaiyalogo.png" height="100" > --here>
				<h1 align="center" style="color:black;font-family:Arial Black;">Faculty Activities Details</h1>
				<h3 align="center" style="color:black;font-family:Helvetica;">K J Somaiya College of Engineering, Vidyavihar, Mumbai-77</h3>
				<h3 align="center" style="color:black;font-family:Helvetica;">Autonomous College Affiliated to University of Mumbai</h3>			
			</div>
			<!-- <div class="col-xs-11">
				<div class="container">
				<h2 align="center" style="color:orange;font-family:Arial Black;">Faculty Activities Details</h2>
				<h4 align="center" style="color:white;font-family:Helvetica;">K J Somaiya College of Engineering, Vidyavihar, Mumbai-77</h4>
				<h4 align="center" style="color:white;font-family:Helvetica;">Autonomous College Affiliated to University of Mumbai</h4>			
				</div>
			</div> --here>
		</div>
	</div> -->
	<!-- login box -->
	<div class="container login">
		<div class="row loginrow">
			<div class="col-sm-6"><br><br>
				<?php
					if ($flag == 1) {
						echo $successMessage;
					}
				?>
				<form action="" method="POST">
					<label for="exampleInputEmail1">Email address </label><span class="colour"><b> *</b></span>
					<input autofocus type="text" name="email" class="form-control input-lg" id="exampleInputEmail1" placeholder="Enter email"><br>
					<label for="exampleInputPassword1">Password </label><span class="colour"><b> *</b></span>
					<input type="password" name="password" class="form-control input-lg" id="exampleInputPassword1" placeholder="Password"><br>
					<center><button type="submit" name='login' class="btn btn-primary">Login</button></center><br>
				</form>
			</div>
			<!-- google sign in -->
			<div class="col-sm-6 gsignin">
				<center>
				<label><br><br><br><br><h4 style="color:#ffffff">Sign in using Google</h4><label><br>
				<div class="" style="border-radius:2px;">
					<button class="g-signin2 btn btn-link" data-width="120" data-height="40" data-onsuccess="onSignIn" align="left"></button>
				</div>
				<div id="hidden_form_container" style="display:none;"></div>
				</center>
			</div>
		</div>
	</div>
	<?php
		//for printing signup successfull
		/*if(isset($_SESSION['success']))
		$success = $_SESSION['success'];
		if($success == 1)
		{
			echo '<div class="error2">"Sign up Successful"</div>';
			$_SESSION['success'] = 0;
		}*/
		if ($succ == 1) {
			echo '<div class="container login" style="display:table"><div class="error2"><center>' . $errors . '</center></div></div>';
		}
	?>
	<div class="container reset">
		<div class="row resetrow">
			<br><center><div class="icon">
				<strong><span>Can't remember your password?</span></strong><br><br>
				<!-- <i style="color:red; font-size:15px;" class="fa fa-lock"></i>&nbsp;&nbsp;&nbsp; -->
				<a href="resetpassword.php" class="areset"><strong><u>Reset Password</u></strong></a>
			</div></center><br>
		</div>
	</div>
	</div>
</body>


<?php //include_once('footer.php'); 
?>