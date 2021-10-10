
<?php
ob_start();

session_start();


//check if user has logged in or not

if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
if(isset($_SESSION['type'])){
	if($_SESSION['type'] != 'hod')
    //if not hod then send the user to login page
    header("location:index.php");
}
//$_SESSION['currentTab'] = "paper";

//connect ot database
include_once("includes/connection.php");
include_once("includes/functions.php");


//include custom functions files 
//include_once("includes/functions.php");
//echo "flag  : ".$_GET['flag']."<br>";
$flag = $_COOKIE["flag"];



if(isset($_POST['id'])){
    $_SESSION['id'] = $_POST['id'];
	
}

?>
<SCRIPT LANGUAGE="JavaScript" type="text/javascript">

    var r = confirm("Do you want to delete ? ");
    if (r == true) {
		var flag = 1;
    } else {
		var flag = 2; 
	}
	document.cookie = "flag="+flag;

    window.location.href = "41_deletedetails.php?flag=" + flag;

</script>