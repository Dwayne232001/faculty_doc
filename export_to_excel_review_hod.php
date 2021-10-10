<?php
ob_start();
session_start();
$_SESSION['currentTab'] = "paper";

include("includes/connection.php");

if(!isset($_SESSION['loggedInUser'])){
     //send the iser to login page
     header("location:index.php");
 }

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

$output="";
$query = "SELECT * from paper_review inner join facultydetails on paper_review.Fac_ID = facultydetails.Fac_ID";
$result = mysqli_query($conn,$query) or die("Couldn't execute query:<br>" . mysqli_error(). "<br>" . mysqli_errno()); 
if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table style="border:1px 1px">  
                    <tr>   
                         <th>Faculty Name</th>
                         <th>Paper Title</th>  
                         <th>Journal/Conference</th>  
                         <th>National/Interntional</th>
                         <th>Paper category</th>  
                         <th>conf_journal_name</th>
                         <th>Date_from</th>
                         <th>Date_to</th>
                         <th>Organised By</th>
                         <th>Paper Details</th>
                         <th>volume</th>
                         <th>Last Updated</th>
                         <th>Mail_Letter</th>
                         <th>Certificate</th>
                         <th>Report</th>
                    </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
                    <tr>   
                         <td>'.$row['F_NAME'].'</td>
                         <td>'.$row["Paper_title"].'</td>
                         <td>'.$row["Paper_type"].'</td>
                         <td>'.$row["Paper_N_I"].'</td>  
                         <td>'.$row["paper_category"].'</td> 
                         <td>'.$row["conf_journal_name"].'</td>  
                         <td>'.$row["Date_from"].'</td>
                         <td>'.$row["Date_to"].'</td>
                         <td>'.$row["organised_by"].'</td>
                         <td>'.$row["details"].'</td>
                         <td>'.$row["volume"].'</td>
                         <td>'.$row["last_added"].'</td>   
                         <td>'.$row["mail_letter_path"].'</td> 
                         <td>'.$row["certificate_path"].'</td>  
                         <td>'.$row["report_path"].'</td> 
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=technical_review_all.xls');
  echo $output;
 }
?>