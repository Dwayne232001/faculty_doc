<?php
ob_start();
session_start();
include_once('head.php');
include('includes/connection.php');
include('includes/functions.php');
require_once('PHPMailer_5.2.4/class.phpmailer.php');
$result = '';
$flag = 0;
$err = '';
$successMessage = "";


if (isset($_POST['sendotp'])) {
	$email = $_POST['email'];
	$_SESSION['email'] = $email;
	if (empty($email)) {
		$result = $result . "Email id is neccessary<br>";
		$flag = 1;
	} else {
		//send mail with a key
		$str = "0123456789qwertyuiopasdfghjklzxxcvbnm";
		$str = str_shuffle($str);
		$key = substr($str, 0, 10);
		// $key = 12345;
		$q = "UPDATE facultydetails SET token='$key' WHERE Email='$email';";
		$result3 = $conn->query($q);
		//mailer code
		// echo $result3;
		if ($result3) {
			$mail = new PHPMailer(true);
			$subject = "Faculty Documentation Portal Password Reset";
			$pwdlink = "localhost/fdp/dd/resetpassword.php?key=$key";
			$message = nl2br(" This mail was sent because a password reset request was generated on the KJSCE Faculty Documentation Portal.\n
		To reset your password, <a href='$pwdlink'>click here</a>\n\nIf you did not request a password reset, please ignore this mail.");
			$mailid = $email;
			$from_email = 'thisisatest1811@gmail.com';   //enter sender email here
			$from_name = 'FDP Portal Test';    //enter sender name here
			$password = "thisisatestemail1811";     //enter sender password here
			// $cc_mail = "thisisatest1811@gmail.com";      //enter cc mail here
			try {
				$mail->IsSMTP();
				$mail->isHTML(true);
				$mail->SMTPDebug = 0;
				$mail->SMTPAuth = true;
				$mail->SMTPSecure = "ssl";  //or 'tls'
				$mail->Host = "smtp.gmail.com";
				$mail->Port = '465'; //or 587
				$mail->AddAddress($mailid);
				$mail->Username = $from_email;
				$mail->Password = $password;
				$mail->SetFrom($from_email, $from_name);
				$mail->AddReplyTo($from_email, $from_name);
				$mail->Subject = $subject;
				$mail->Body = $message;
				$mail->AltBody = $message;
				if ($mail->Send()) {
					$successMessage = "A link has been sent to your email. Please click on it to reset your password";
				} else {
					echo "Mail error" . $mail->ErrorInfo;
					header("Location:index.php?alert=error");
				}
				
			} catch (phpmailerException $ex) {
				$msg = "" . $ex->errorMessage() . "";
				echo "Mail error: " . $msg;
				ob_flush();
				header("Location:index.php?alert=error");
				ob_end_flush();
				die();
			}
		} else {
			$result = $result . "Email does not exist";
			$flag = 1;
		}
	}
}
if (isset($_POST['submit'])) {
	$passwd1 = $_POST['pass'];
	$passwd2 = $_POST['pass2'];
	$email = $_SESSION['email'];

	if (empty($_POST['pass'])) {
		$result = $result . "Password cannot be empty<br>";
		$flag = 1;
	}

	if (!empty($_POST['pass']) && !empty($_POST['pass2'])) {
		if (strcmp($_POST['pass'], $_POST['pass2']) != 0) {
			$result = $result . "Passwords do not match , Please Re enter<br>";
			$flag = 1;
		}
	}

	if (!empty($_POST['pass'])) {
		if (empty($_POST['pass2'])) {
			$result = $result . "Please Confirm Password<br>";
			$flag = 1;
		}
	}

	if ($flag == 0) {
		if ($passwd1 == $passwd2) {
			$str = "0123456789qwertyuiopasdfghjklzxxcvbnm";
			$str = str_shuffle($str);
			$str = substr($str, 0, 10);
			$options = array("cost" => 4);
			$hashPassword = password_hash($passwd1, PASSWORD_BCRYPT, $options);
			$sql1 = "select Email from facultydetails where Email='$email'";
			$result1 = $conn->query($sql1);
			if (($result1->num_rows) == 1) {
				$sql2 = "UPDATE facultydetails set Password='$hashPassword' where Email='$email'";
				if (mysqli_query($conn, $sql2)) {
					header("Location:index.php?alert=success");
				} else {
					header("Location:index.php?alert=error");
				}
			} else {
				$result = $result . "Email does not exist or duplicate entry";
				$flag = 1;
			}
		}
	}
}
if (isset($_POST['cancel'])) {
	header("Location:index.php");
}
?>

<style>
	input {
		border-radius: 5px;
	}


	input[type='text'] {
		width: 300px;
		height: 30px
	}

	input[type='email'] {
		width: 300px;
		height: 30px
	}

	input[type='password'] {
		width: 300px;
		height: 30px
	}

	.pagedesign {
		font-weight: bold;
		font-size: 1.1em;
		margin-top: 5px;
		margin-right: 5px;
	}

	.error {
		color: red;
		border: 1px solid red;
		background-color: #ebcbd2;
		border-radius: 10px;
		margin: 3px;
		padding: 5px;
		font-family: Arial, Helvetica, sans-serif;
		width: 500px;
	}

	.noerror {
		color: green;
		border: 1px solid green;
		background-color: #d7edce;
		border-radius: 10px;
		margin: 3px;
		padding: 5px;
		font-family: Arial, Helvetica, sans-serif;
		width: 500px;
		height: 40px;
	}

	body,
	html {
		height: 200%;
		margin: 0;
	}

	.bg {
		/* The image used */
		//background-image: url("images/blue-color-background-wallpaper-4.jpg");

		/* Full height */
		height: 100%;

		/* Center and scale the image nicely */
		background-position: center;
		background-repeat: no-repeat;
		background-size: cover;
		background-image: linear-gradient(to right, #1A237E, #673A90);

	}

	.box {
		margin: 0 auto;
		margin-top: 0%;
		margin-left: 26.2%;
	}

	.error2 {
		color: red;
		border: 1px solid red;
		background-color: #ebcbd2;
		border-radius: 10px;
		margin: 5px;
		margin-left: 36%;
		padding: 5px;
		font-family: Arial, Helvetica, sans-serif;
		width: 375px;
	}

	.box-footer .btn {
		width: 150px;
	}
</style>

<body class="bg">
	<div class="">
		<section class="content">
			<img src="images/somaiyalogo.png" height="100" style="margin-left:30px;" />

			<!-- <img src="images/trust.png" height="70" alt="Trust" style="margin-left:1050px;"/> -->


			<h2 align="center" style="color:white; font-family:Times New Roman; margin-top:-80px; word-spacing:3px;"> K J Somaiya College of Engineering , Vidyavihar, Mumbai-77</h2>
			<h3 align="center" style="color:white; font-family:Times New Roman; margin-top:-5px"> (Autonomous College Affiliated to University of Mumbai)</h3>
			<h3 align="center" style="color:orange; font-family:Times New Roman; margin-top:-2px; font-size:30px; ">Faculty Activities Details</h3>

			<div class="row" style="width:800px; margin:0 auto;">
				<div class="col-md-8">

					<div class="box box-primary">
						<div class="box-header with-border">

							<div class="icon">
								<span class="fa-passwd-reset fa-stack">
									<i class="fa fa-undo fa-stack-2x"></i>
									<i class="fa fa-lock fa-stack-1x"></i>
								</span> <label>
									<h2 class="box-title"><b>Reset Password</b></h2>
								</label> <br>
							</div> <?php

									?>
						</div><!-- /.box-header -->
						<form role="form" action="" method="post" enctype="multipart/form-data">

							<div class="box-body">

								<?php
								if (isset($_GET['key'])) {
									//code to check if key and token is same
									$keyquery = "SELECT Email,token from facultydetails where token='" . $_GET['key'] . "'";
									$result3 = $conn->query($keyquery);
									$a = mysqli_fetch_assoc($result3);
									if (mysqli_num_rows($result3) == 1 && $a['Email'] == $_SESSION['email']) {
										echo "<div class=\"form-group\"> <b>Enter New Password :</b><br>
										<input type=\"password\" id=\"pwinput\" placeholder=\"New Password\" name=\"pass\"> <br> <input type=\"checkbox\" id=\"pwcheck\" />&nbsp Show Password<br><span id=\"pwtext\"></span><br>
										</div>
										<div class=\"form-group\"> <b>Confirm Password :</b><br><input type=\"password\" placeholder=\"Confirm Password\" name=\"pass2\"><br><br>
										</div>
										<div class=\"box-footer\">
											<input type=\"submit\" value=\"Reset Password\" class=\"btn btn-primary\" name=\"submit\" style=\"margin-left:-10px;\">
											&nbsp;<input type=\"submit\" class=\"btn\" value=\"Cancel\" name=\"cancel\">
										</div>";
									} else {
										echo "<div>Email does not exist</div>";
										header("Location:index.php?alert=error");
									}
								} else {
									if ($successMessage != "") {
										echo "<span id=\"msg\"><strong>" . $successMessage . "</strong></span>";
									} else {
										echo "<div class=\"form-group\"> <b>Email ID :</b><br>
											<input type=\"email\" placeholder=\"Email Here\" name=\"email\" id=\"email_input\"><br><br>
										</div>
										<div class=\"box-footer\">
											<input type=\"submit\" value=\"Send link\" class=\"btn btn-primary\" name=\"sendotp\" id=\"sendotp\" style=\"margin-left:-10px;\">
											&nbsp;<input type=\"submit\" class=\"btn\" value=\"Cancel\" name=\"cancel\">
										</div><br>";
									}
								}
								?>

							</div>
						</form>


						<!-- /.content-wrapper -->
					</div>
				</div>
			</div>
	</div>
	<?php
	if ($flag == 1) {
		echo '<div class="error2">' . $result . '</div>';
	}
	?>
	</div>
	</section>
	</div>
</body>
<?php
echo '<script type="text/javascript" src="jquery/jquery-3.2.1.min.js"></script>
				<script>
				$(document).ready(function(){
				
				$("#email_input").focus();

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