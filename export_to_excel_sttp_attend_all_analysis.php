<?php
session_start();
include 'includes/connection.php';
$output='';
$display = 0;	
		
$sql = $_SESSION['sql'];

$result = mysqli_query($conn,$sql) or die("Couldn't execute query:<br>" . mysqli_error(). "<br>" . mysqli_errno()); 
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" style="border:1px 1px">  
                    <tr>   
                         <th>Faculty Name</th>
                         <th>Activity Title</th>  
                         <th>Activity Type</th>
                         <th>Organized By</th>
                         <th>From Date</th>
                         <th>To Date</th>
                         <th>Number of days</th>
                         <th>Location</th>
                    </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
                    <tr>   
                         <td>'.$row["F_NAME"].'</td>
                         <td>'.$row["Act_title"].'</td>
                         <td>'.$row["Act_type"].'</td> 
                         <td>'.$row["Organized_by"].'</td>  
                         <td>'.$row["Date_from"].'</td>
                         <td>'.$row["Date_to"].'</td>
                         <td>'.$row["noofdays"].'</td>
                         <td>'.$row["Location"].'</td>  
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=sttp_attended_all.xls');
  echo $output;
 }
?>