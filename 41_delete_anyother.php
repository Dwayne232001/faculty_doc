<?php
echo "flag  : ".$_GET['flag']."<br>";

?>
<?php
ob_start();

session_start();


//check if user has logged in or not

if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index_excurricular.php");
}
//connect ot database
include_once("includes/connection.php");
include_once("includes/functions.php");

$flag = $_GET['flag'];

    $id = $_SESSION['id'];

	if($flag == 1)
	{
    echo $id;
	$sql = "delete from any_other_activity WHERE any_other_ID = '$id'";

			if ($conn->query($sql) === TRUE) {
				if($_SESSION['type'] == 'hod' )
				{
					header("location:2_dashboard_hod_anyother.php?alert=delete");
				}
				else
					header("location:2_dashboard_anyother.php?alert=delete");

			} 
			else {
				if($_SESSION['type'] == 'hod' )
				{
					header("location:2_dashboard_hod_anyother.php?alert=error");
				}
				else
					header("location:2_dashboard_anyother.php?alert=error");
			}
	
	}
	else if($flag == 2)
	{
		if($_SESSION['type'] == 'hod' )
				{
					header("location:2_dashboard_hod_anyother.php");
				}
				else
					header("location:2_dashboard_anyother.php");
	}



?>