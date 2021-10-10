<?php
	ob_start();
    session_start();
    if(!isset($_SESSION['loggedInUser'])){
        //send them to login page
        header("location:index.php");
    }
    include("includes/connection.php");

    include_once('head.php');
    include_once('header.php'); 
    // if($_SESSION['type']!='hod'){
    // 	header("Location: index.php");
    // }

    if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
    {
        include_once('sidebar_hod.php');
    }
    else
        include_once('sidebar.php');
    $success=0;
    if(isset($_POST['name'])){
    	$name=$_POST['name'];
    	$fname=explode(',', $name);
    	$dept=end($fname);
    	$facname=$fname[0];
    	$query="SELECT * from facultydetails where F_NAME='$facname' ";
		$rt=mysqli_query($conn,$query);
		while($row=mysqli_fetch_array($rt)){
			$fid=$row['Fac_ID'];
			$facmobile=$row['Mobile'];
		}
    	$sql="INSERT INTO committee (fac_id,com_name,date_from,com_status) values($fid,'$facname',now(),'active')";
    	if(mysqli_query($conn,$sql)==true){
    		$success=1;
    	}
    	$fid1=(($conn->insert_id)-1);
    	$comname="Committee ".$facname;
    	if($success==1){
    		$sql1="UPDATE committee SET com_status='inactive' where com_id=$fid1";
    		$sql2="UPDATE facultydetails SET F_NAME='$comname' where type='com' ";
    		$sql3="UPDATE facultydetails SET mobile=$facmobile where type='com' ";
    		if(mysqli_query($conn,$sql1)==true && mysqli_query($conn,$sql2)==true && mysqli_query($conn,$sql3)==true){
    			$success=2;
    		}
    	}
    	echo $facname.$fid.$dept.$fid1.$success.$facname.$facmobile.$comname;
    	if($success==2){
    		header("Location:list_of_activities_user.php");
    	}
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
								<label>Select Faculty Name </label><br>
								<select id='search' name='name'>
									<?php
										$sql= " SELECT * FROM facultydetails WHERE Dept='".$_SESSION['Dept']."' AND type='faculty' ORDER BY F_NAME";
										$result= mysqli_query($conn,$sql);
										while($row=mysqli_fetch_array($result))
										{
											echo"<option>".$row['F_NAME']."</option>";
										}
									?>
								</select>
								<br><br><br>
								<input type='submit' name='submit'>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script>
	$("#search").chosen();
</script>
</head>
