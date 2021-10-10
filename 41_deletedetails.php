<?php
echo "flag  : ".$_GET['flag']."<br>";

?>
<?php
ob_start();

session_start();


//check if user has logged in or not

if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
//$_SESSION['currentTab'] = "paper";

//connect ot database
include_once("includes/connection.php");
include_once("includes/functions.php");

$flag = $_GET['flag'];

    $id = $_SESSION['id'];

	if($flag == 1)
	{
			$sql = "delete from facultydetails WHERE Fac_ID = $id";
			
			if ($conn->query($sql) === TRUE)
				{					
					$del = 1;
				}
			

			
	
			if($del == 1)
			{
				if($_SESSION['type'] == 'hod' ){
					header("location:viewdetails.php?alert=delete");
				}
				
			}
			else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
	
	}
	else if($flag == 2)
	{
		if($_SESSION['type'] == 'hod' ){
					header("location:viewdetails.php");
				}
					
	}



?>