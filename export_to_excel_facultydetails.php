<?php
ob_start();
session_start();
$_SESSION['currentTab'] = "paper";

include 'includes/connection.php';

$fid = $_SESSION['Fac_ID'];

$queryrun="SELECT * FROM facultydetails where Fac_ID=$fid";
 $resultrun = mysqli_query($conn, $queryrun);
while($row=mysqli_fetch_assoc($resultrun)){
  $_SESSION['Dept']=$row['Dept'];
  $_SESSION['type']=$row['type'];
}

$sql = "SELECT * from facultydetails WHERE Dept='".$_SESSION['Dept']."' ORDER BY F_NAME";
$filename = "faculty_details"; 

$result = mysqli_query($conn,$sql) or die("Couldn't execute query:<br>" . mysqli_error(). "<br>" . mysqli_errno()); 
$output="";
if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" style="border:1px 1px">  
                    <tr> 
                         <th>Faculty ID</th>  
                         <th>Faculty Name</th>  
                         <th>Email ID</th>
                         <th>Mobile Number</th>
                         <th>Role</th>
                    </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
                    <tr> 
                         <td>'.$row["Fac_ID"].'</td>
                         <td>'.$row["F_NAME"].'</td>
                         <td>'.$row["Email"].'</td>  
                         <td>'.$row["Mobile"].'</td> 
                         <td>'.$row["type"].'</td>   
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=faculty_details.xls');
  echo $output;
 }

?>

