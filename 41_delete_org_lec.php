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
//connect ot database
include_once("includes/connection.php");
include_once("includes/functions.php");

$flag = $_GET['flag'];

    $id = $_SESSION['id'];

	if($flag == 1)
	{

	$sql = "delete from guestlec WHERE P_ID = $id";

			if ($conn->query($sql) === TRUE) {
				if($_SESSION['type'] == 'hod' )
				{
					header("location:view_organised_hod_lec.php?alert=delete");
				}
				else
					header("location:view_organised_lec.php?alert=delete");

			} 
			else {
				if($_SESSION['type'] == 'hod' )
				{
					header("location:view_organised_hod_lec.php?alert=error");
				}
				else
					header("location:view_organised_lec.php?alert=error");
			}
	
	}
	else if($flag == 2)
	{
		if($_SESSION['type'] == 'hod' )
				{
					header("location:view_organised_hod_lec.php");
				}
				else
					header("location:view_organised_lec.php");
	}



?>