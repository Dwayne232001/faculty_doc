<?php
include_once('includes/connection.php');

//export.php
ob_start();
session_start();
$_SESSION['currentTab'] = "iv";
$fid=$_SESSION['Fac_ID'];
$output = '';
$query = $_SESSION['sql'];
$result = mysqli_query($conn, $query);
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" style="border:1px 1px">  
                    <tr>   
                         <th>Industry Name</th>  
                         <th>City</th>  
                         <th>Purpose</th>
                         <th>Date_from</th>
                         <th>Date_to</th>
                         <th>Number of days</th>
                         <th>Audience</th>
                    </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
                    <tr>   
                         <td>'.$row["ind"].'</td>
                         <td>'.$row["city"].'</td>
                         <td>'.$row["purpose"].'</td>    
                         <td>'.$row["t_from"].'</td>
                         <td>'.$row["t_to"].'</td>
                         <td>'.$row["noofdays"].'</td>
                         <td>'.$row["t_audience"].'</td>
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=iv_analysis.xls');
  echo $output;
 }
?>