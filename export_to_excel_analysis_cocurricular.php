<?php
session_start();
include 'includes/connection.php';
$table = "co_curricular"; 
$fid = $_SESSION['Fac_ID'];
$datefrom=$_SESSION['from_date'];
$dateto=$_SESSION['to_date'];
$sql = "select * from $table where Fac_ID ='".$_SESSION['Fac_ID']."' AND Date_from>='$datefrom' AND Date_from<='$dateto' ;";
$result = mysqli_query($conn,$sql) or die("Couldn't execute query:<br>" . mysqli_error(). "<br>" . mysqli_errno()); 
$output="";
if(mysqli_num_rows($result) > 0)
 {
 $output.='
  <table class="table" style="border:1px 1px">  
                    <tr>   
                         <th>Activity Name</th>  
                         <th>Organized By</th>  
                         <th>Purpose</th>
                         <th>Date from</th>
                         <th>Date to</th>
                         <th>Number of days</th>
                    </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
                    <tr>   
                         <td>'.$row["activity_name"].'</td>
                         <td>'.$row["organized_by"].'</td>
                         <td>'.$row["purpose_of_activity"].'</td>    
                         <td>'.$row["Date_from"].'</td>
                         <td>'.$row["Date_to"].'</td>
                         <td>'.$row["noofdays"].'</td>
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=co_curricular_analysis.xls');
  echo $output;
}
?>  
