
<?php
ob_start();

session_start();

//check if user has logged in or not

if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
$_SESSION['currentTab'] = "sttp1";

//connect ot database
include_once("includes/connection.php");
include_once("includes/functions.php");

//include custom functions files 
//include_once("includes/functions.php");
//echo "flag  : ".$_GET['flag']."<br>";
// $flag = $_COOKIE["flag"];

if(isset($_POST['rid'])){
    $_SESSION['id'] = $_POST['rid'];
}

?>
<SCRIPT LANGUAGE="JavaScript" type="text/javascript">

    var r = confirm("Do you want to delete ?");
    if (r == true) {
		var flag = 1;
    } else {
		var flag = 2; 
	}
	document.cookie = "flag="+flag;

    window.location.href = "41_delete_organised.php?flag=" + flag;

</script>