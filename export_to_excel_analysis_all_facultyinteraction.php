<?php
session_start();
include 'includes/connection.php';
$output='';
$display = 0;	
		
$sql1=$_SESSION['sql'];
$result = mysqli_query($conn, $sql1);
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" style="border:1px 1px">  
                    <tr>   
                         <th>Faculty Name</th>
                         <th>organized By</th>  
                         <th>Date_from</th>
                         <th>Date_to</th>
                         <th>Number of days</th>
                         <th>Award</th>
                         <th>Invited For</th>
                         <th>Topic of Lecture</th>
                         <th>Details if any other Activity</th>
                    </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
                    <tr>   
                         <td>'.$row["F_NAME"].'</td>
                         <td>'.$row["organized"].'</td>
                         <td>'.$row["durationf"].'</td> 
                         <td>'.$row["durationt"].'</td> 
                         <td>'.$row["noofdays"].'</td> 
                         <td>'.$row["award"].'</td>
                         <td>'.$row["res_type"].'</td>
                         <td>'.$row["topic"].'</td>  
                         <td>'.$row["details"].'</td> 
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=Analysis_FacultyIneraction.xls');
  echo $output;
 }
?>