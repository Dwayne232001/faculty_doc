<?php

// Inialize session
session_start();
//$google = sessionStorage.getItem('myUserEntity');
//if ($google == null) {
	
// Delete certain session
unset($_SESSION['username']);
// Delete all session variables
 session_destroy();
//}
//else {
	echo '<script type="text/javascript"> signOut(); </script>';
//}
// Jump to login page
header('Location: index.php');

?>
<script type="text/javascript">
function signOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
      alert('User signed out.');
    });
  }
</script>