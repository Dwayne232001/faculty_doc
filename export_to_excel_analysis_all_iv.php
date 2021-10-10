<?php
session_start();
include 'includes/connection.php';
$table = "iv_organized"; 
$output='';
$display = 0;	
$sql1=$_SESSION['sql'];
$result = mysqli_query($conn, $sql1);
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" style="border:1px 1px">  
                    <tr>
                         <th>Faculty</th>   
                         <th>Industry Name</th>
                         <th>Audience</th>
                         <th>City</th>  
                         <th>Purpose</th>
                         <th>Date_from</th>
                         <th>Date_to</th>
                         <th>Number of days</th>
                    </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
                    <tr> 
                         <td>'.$row["F_NAME"].'</td>  
                         <td>'.$row["ind"].'</td>
                         <td>'.$row["t_audience"].'</td>
                         <td>'.$row["city"].'</td>
                         <td>'.$row["purpose"].'</td>    
                         <td>'.$row["t_from"].'</td>
                         <td>'.$row["t_to"].'</td>
                         <td>'.$row["noofdays"].'</td>
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=iv_analysis_hod.xls');
  echo $output;
 }
?>