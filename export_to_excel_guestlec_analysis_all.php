<?php
session_start();
include 'includes/connection.php';

$sql1=$_SESSION['sql'];

$output = '';
 $result = mysqli_query($conn, $sql1);
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" style="border:1px 1px">  
                    <tr>   
                         <th>Faculty</th>
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
                         <td>'.$row["F_NAME"].'</td>
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