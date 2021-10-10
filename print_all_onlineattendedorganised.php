<?php
ob_start();
session_start();
 if(!isset($_SESSION['loggedInUser'])){
    //send the iser to login page
    header("location:index.php");
}
				include_once 'dompdf/dompdf_config.inc.php';
				include_once("includes/connection.php");

				$sql = $_SESSION['sql'];
				$Fac_ID = $_SESSION['Fac_ID'];	
				
				$type = $_SESSION['type'] ;					
					
				
							$result=$conn->query($sql);
						   $num= $result->num_rows;
						   
						   if($type == "Attended")
						   {
							   echo "<div class='box-body'>
							   <br>Total number of Activities : $num 
							   

							   <table align='' border='1' class='table table-stripped table-bordered' id = 'example1'>
							   
							   <tr id='tr1'>
								<th>Faculty Name</th>
								
								<th>Course Name</th>
							   <th>Date from</th>
							   <th>Date to</th>
							   <th>organised by</th>
							   <th>purpose</th>
							   <th>FDC applicable ?</th>
							   <th>type of course</th>
							   <th>status of activity</th>
							   <th>duration</th>
							   <th>credit/audit</th>
							   
							   </tr>";
							   for($i=1;$i<=$num;$i++)
							   {
								  $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
								  echo '
								  <tr>
								  <td>'.$row["F_NAME"].'</td>
								  
								  <td>'.$row["Course_Name"].'</td>
								  <td>'.$row["Date_From"].'</td>
								  <td>'.$row["Date_To"].'</td>
								  <td>'.$row["Organised_by"].'</td>
								  <td>'.$row["Purpose"].'</td>
								  <td>'.$row["FDC_Y_N"].'</td>
								  <td>'.$row["type_of_course"].'</td>
								  <td>'.$row["status_of_activity"].'</td>
								  <td>'.$row["duration"].'</td>
								  <td>'.$row["credit_audit"].'</td>
								  
								  
								  
								  </tr>';
								}
								echo "</table></div>";
						   }
						   else if($type == "Organised")
						   {
							   echo "<div class='box-body'>
							   <br>Total number of Activities : $num 
							   

							   <table align='' border='1' class='table table-stripped table-bordered' id = 'example1'>
							   
							   <tr id='tr1'>
								<th>Faculty Name</th>
								
								<th>Course Name</th>
							   <th>Date from</th>
							   <th>Date to</th>
							   <th>organised by</th>
							   <th>purpose</th>
							   <th>Target Audience</th>
							   <th>type of course</th>
							   <th>Faculty role</th>
							   <th>full/part time</th>
							   <th>no of participants</th>
							   <th>Duration</th>
							   <th>Status</th>
							   <th>Sponsored</th>
							   <th>Name of Sopnsorer</th>
							   
							   </tr>";
							   for($i=1;$i<=$num;$i++)
							   {
								  $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
								  echo '
								  <tr>
								  <td>'.$row["F_NAME"].'</td>
								  
								  <td>'.$row["Course_Name"].'</td>
								  <td>'.$row["Date_From"].'</td>
								  <td>'.$row["Date_To"].'</td>
								  <td>'.$row["Organised_By"].'</td>
								  <td>'.$row["Purpose"].'</td>
								  <td>'.$row["Target_Audience"].'</td>
								  <td>'.$row["type_of_course"].'</td>
								  <td>'.$row["faculty_role"].'</td>
								  <td>'.$row["full_part_time"].'</td>
								  <td>'.$row["no_of_part"].'</td>
								  <td>'.$row["duration"].'</td>
								  <td>'.$row["status"].'</td>
								  <td>'.$row["sponsored"].'</td>
								  <td>'.$row["name_of_sponsor"].'</td>
								  
								  
								  
								  </tr>';
								}
								echo "</table></div>";
						   }
						  
						  
					   $op = "<p align='center'  style='font-size:20px'><strong>K.J.Somaiya College of Engineering</strong></p>"."<p align='center'>(Autonomous College affiliated to University of Mumbai)</p>"."<p align='center'>Online/Offline Course "."$type"."</p>"."<table  border='1' class='table table-stripped table-bordered' id = 'example1'>";
						   
				$html = ob_get_clean();

				$dompdf = new DOMPDF();
				$dompdf->load_html($op."<br>".$html);
				
				$dompdf->set_paper('a4', 'landscape');
				$dompdf->render();
                $dompdf->stream('hi',array('Attachment'=>0));
				
?>