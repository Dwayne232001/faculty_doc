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
            <th>Organized By</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Awards</th>
            <th>Invited as Resource person for</th>
            <th>Topic Of Lecture</th>
            <th>Details if Any Other Activity</th>				
        </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
        <tr>
            <td>'.$row['organized'].'</td>
            <td>'.$row['durationf'].'</td>
            <td>'.$row['durationt'].'</td>
            <td>'.$row['award'].'</td>
            <td>'.$row['res_type'].'</td>
            <td>'.$row['topic'].'</td>
            <td>'.$row['details'].'</td>						
        </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=faculty_interaction_analysis.xls');
  echo $output;
 }
?>