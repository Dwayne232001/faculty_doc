<?php
include("includes/connection.php");
session_start();
ob_start();

if(isset($_SESSION['type'])){
    if($_SESSION['type'] != 'hod' && $_SESSION['type'] != 'cod' && $_SESSION['type']!='com'){
    //if not hod then send the user to login page
    session_destroy();
    header("location:index.php");
  }
  }  
  
  $fid=$_SESSION['Fac_ID'];
  
  $queryrun="SELECT * FROM facultydetails where Fac_ID=$fid";
  $resultrun = mysqli_query($conn, $queryrun);
  while($row=mysqli_fetch_assoc($resultrun)){
    $_SESSION['Dept']=$row['Dept'];
    $_SESSION['type']=$row['type'];
}
$fromDate=$toDate="";
$fromDate =  $_SESSION['fromDate'] ;
$toDate = $_SESSION['toDate'] ;
$type1 = $_SESSION['type1'] ;
$type2 = $_SESSION['type2'] ;
$type3 = $_SESSION['type3'] ;
$facultyName = $_SESSION['facultyName'];

$sql = "";
$output='';

if($type1 === 1)
{
    $sql= " SELECT * FROM researchdetails inner join facultydetails on researchdetails.Fac_ID=facultydetails.Fac_ID WHERE toDate >= '$fromDate' AND fromDate <= '$toDate'";
}
else if($type2 === 1)
{
    $facultyName = $_SESSION['facultyNameForExcel'] ;
    $sql = " SELECT * FROM researchdetails inner join facultydetails on researchdetails.Fac_ID=facultydetails.Fac_ID WHERE facultyName LIKE '%$facultyName%'";
}
else if($type3 === 1)
{
    $facultyName = $_SESSION['facultyNameForExcel'];
    $sql = " SELECT * FROM researchdetails inner join facultydetails on researchdetails.Fac_ID=facultydetails.Fac_ID WHERE toDate >= '$fromDate' AND fromDate <= '$toDate' AND facultyName LIKE '%$facultyName%'";
}

$result = mysqli_query($conn, $sql);
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" style="border:1px 1px">  
                    <tr>   
                         <th>Faculty</th>
                         <th>Research Title</th>  
                         <th>Start Date</th>
                         <th>End Date</th>
                         <th>Number of days</th>
                         <th>Submitted to</th>
                         <th>Whether Approved?</th>
                    </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
                    <tr>   
                         <td>'.$row['F_NAME'].'</td>
                         <td>'.$row["researchTitle"].'</td>
                         <td>'.$row["fromDate"].'</td>
                         <td>'.$row["toDate"].'</td>
                         <td>'.$row["noofdays"].'</td>
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