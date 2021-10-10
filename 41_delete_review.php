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
$_SESSION['currentTab']="technical_review";

//connect ot database
include_once("includes/connection.php");
include_once("includes/functions.php");

$flag = $_GET['flag'];

    $id = $_SESSION['id'];

	if($flag == 1)
	{

	$sql = "delete from paper_review WHERE paper_review_ID = $id";

			if ($conn->query($sql) === TRUE) {
				if($_SESSION['type'] == 'hod' )
				{
					header("location:2_dashboard_hod_review.php?alert=delete");
				}
				else
					header("location:2_dashboard_review.php?alert=delete");

			} 
			else {
				if($_SESSION['type'] == 'hod' )
				{
					header("location:2_dashboard_hod_review.php?alert=error");
				}
				else
					header("location:2_dashboard_review.php?alert=error");
			}
	
	}
	else if($flag == 2)
	{
		if($_SESSION['type'] == 'hod' )
				{
					header("location:2_dashboard_hod_review.php");
				}
				else
					header("location:2_dashboard_review.php");
	}



?>