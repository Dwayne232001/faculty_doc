<?php
ob_start();
session_start();

include("includes/connection.php");
$Fac_ID = $_SESSION['Fac_ID'];
$fromDate =  $_SESSION['fromDate'] ;
$toDate = $_SESSION['toDate'] ;

$sql = "SELECT * FROM researchdetails WHERE toDate >= '$fromDate' AND fromDate <= '$toDate' AND Fac_ID = $Fac_ID";
$output="";
$result = mysqli_query($conn, $sql);
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" style="border:1px 1px">  
                    <tr>   
                         <th>Research Title</th>  
                         <th>Start Date</th>
                         <th>End Date</th>
                         <th>Submitted to</th>
                         <th>Whether Approved?</th>
                    </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
                    <tr>   
                         <td>'.$row["researchTitle"].'</td>
                         <td>'.$row["fromDate"].'</td>
                         <td>'.$row["toDate"].'</td>  
                         <td>'.$row["submittedTo"].'</td> 
                         <td>'.$row["radioApproval"].'</td>  
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=researchAnalysis_HOD.xls');
  echo $output;
 }
?>