<?php
ob_start();
session_start();
include 'includes/connection.php';
$table = "guestlec"; 
$filename = "guest_lecture_organised"; 
$sql = $_SESSION['sql'];

$result = mysqli_query($conn,$sql) or die("Couldn't execute query:<br>" . mysqli_error(). "<br>" . mysqli_errno()); 
$output="";

if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" style="border:1px 1px">  
                    <tr>   
                         <th>Topic</th>  
                         <th>Date_from</th>
                         <th>Date_to</th>
                         <th>Resource person Name</th>
                         <th>Organization</th>
                         <th>Target Audience</th>
                         </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
                    <tr>   
                         <td>'.$row["topic"].'</td>
                         <td>'.$row["durationf"].'</td>
                         <td>'.$row["durationt"].'</td>  
                         <td>'.$row["name"].'</td> 
                         <td>'.$row["organisation"].'</td>
                         <td>'.$row["targetaudience"].'</td>
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=guest_analysis.xls');
  echo $output;
 }

?>

