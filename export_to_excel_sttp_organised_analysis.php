<?php
ob_start();
session_start();
$_SESSION['currentTab'] = "sttp";

include 'includes/connection.php';
$table = "organised"; 
$filename = "sttp_organised"; 
$sql = $_SESSION['sql'];

$output = '';
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" style="border:1px 1px">  
                    <tr>   
                         <th>Title</th>  
                         <th>Type</th>
                         <th>Organised By</th>
                         <th>Date from</th>
                         <th>Date to</th>
                         <th>Number of days</th>
                    </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
                    <tr>   
                         <td>'.$row["Act_title"].'</td>
                         <td>'.$row["Act_type"].'</td>
                         <td>'.$row["Organized_by"].'</td>
                         <td>'.$row["Date_from"].'</td>    
                         <td>'.$row["Date_to"].'</td>
                         <td>'.$row["noofdays"].'</td>
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=sttp_orgainsed_analysis.xls');
  echo $output;
 }

?>

