<?php  
include_once('includes/connection.php');

//export.php
ob_start();
session_start();
$_SESSION['currentTab'] = "Research";
$output = '';
 $query ="SELECT * FROM researchdetails where Fac_ID ='".$_SESSION['Fac_ID']."'";
 $result = mysqli_query($conn, $query);
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" style="border:1px 1px">  
                    <tr>   
                         <th>Research Title</th>  
                         <th>Start Date</th>
                         <th>End Date</th>
                         <th>Submitted to</th>
                         <th>Principle Investigator</th>
                         <th>Co Investigator</th>
                         <th>Proposed Amount</th>
                         <th>Whether Approved?</th>
                         <th>Sanctioned Amount</th>
                         <th>Awards Won</th>
                         <th>Report</th>
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
                         <td>'.$row["principleInvestigator"].'</td>  
                         <td>'.$row["coInvestigator"].'</td>
                         <td>'.$row["proposedAmount"].'</td>
                         <td>'.$row["radioApproval"].'</td>  
                         <td>'.$row["amountSanctioned"].'</td> 
                         <td>'.$row["awardsWon"].'</td>  
                         <td>'.$row["reportPath"].'</td>
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=researchView.xls');
  echo $output;
 }
?>