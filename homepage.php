<?php
require_once("includes/config.php");
require_once $google_api_path . "src/Google/Client.php";
require_once $google_api_path . 'vendor/autoload.php';
$client = new Google_Client(['client_id' => $CLIENT_ID]);  // Specify the CLIENT_ID of the app that accesses the backend
$id_token = $_POST['idtoken'];
$payload = $client->verifyIdToken($id_token);
if ($payload) {
  session_start();
  $_SESSION['email'] = $payload['email'];
  include("includes/connection.php");
  //fetching data from database
  $query = "SELECT * FROM facultydetails WHERE Email='" . $payload['email'] . "'";
  $result = mysqli_query($conn, $query);
  if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    $_SESSION['Fac_ID']  = $row['Fac_ID'];
    $_SESSION['username'] = $row['Email'];
    $pass = $row['Password'];
    $_SESSION['f_id'] = $row['Fac_ID'];

    $_SESSION['type'] = $row['type'];
    $_SESSION['loggedInUser'] = $row['F_NAME'];
    $_SESSION['loggedInEmail'] = $row['Email'];

    if ($_SESSION['email'] == 'babaso.aldar@somaiya.edu' || $_SESSION['email'] == 'member@somaiya.edu' || $_SESSION['email'] == 'hodcomp@somaiya.edu') {
      header("LOCATION: list_of_activities_user.php");
    } else
      header("LOCATION: list_of_activities_user.php");
  } else
    header("LOCATION: index.php?error=notfound1");
} else {
  //invalid token
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>GoogleSigninExample</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">


  <meta name="google-signin-client_id" content=<?php echo $CLIENT_ID; ?>>
  <!--Enter yout OAuth Client ID in the content attribute -->

  <script src="https://apis.google.com/js/platform.js" async defer></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>


  <script type="text/javascript">
    function signOut() {
      var auth2 = gapi.auth2.getAuthInstance();
      auth2.signOut().then(function() {
        alert('User signed out.');
        document.location.href = 'index.php';

      });
    }

    function onLoad() {
      gapi.load('auth2', function() {
        gapi.auth2.init();

      });
    }
  </script>
  <title>homepage</title>
</head>

<body>
  <img src="#" id="pic" align="center">
  <script type="text/javascript">
    document.getElementById("pic").src = <?php echo json_encode($_SESSION['pic']); ?>; //displaying user pic
  </script>
  <p id="name">nv</p>
  <script type="text/javascript">
    document.getElementById("name").innerHTML = <?php echo json_encode($_SESSION['user']); ?>; //displaying user name
  </script>
  <p id="email">nv</p>
  <script type="text/javascript">
    document.getElementById("email").innerHTML = <?php echo json_encode($_SESSION['email']); ?>; //displaying user email
  </script>
  <button onclick="signOut()">Sign Out</button>
  <script src="https://apis.google.com/js/platform.js?onload=onLoad" async defer></script>

</body>

</html>