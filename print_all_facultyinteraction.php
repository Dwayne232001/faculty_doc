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
				$sname = $_SESSION['faculty_name'];
				$Fac_ID = $_SESSION['Fac_ID'];	
				$display = $_SESSION['display'];
					
					
				
							$result=$conn->query($sql);
						   $num= $result->num_rows;
						   
						   ob_start();
						   if($display == 2)
						   {
							   echo "<div class='box-body'>
							   <br>Total number of Activities : $num
							   

							   <table align='' border='1' class='table table-stripped table-bordered' id = 'example1'>
							   
							   <tr id='tr1'>
								<th>Faculty Name</th>
								
								<th>Organized by</th>
							   <th>Date from</th>
							   <th>Date to</th>
							   <th>Award</th>
							   <th>Resource Type</th>
							   <th>Topic</th>
							   <th>Details</th>
							   
							   </tr>";
							   for($i=1;$i<=$num;$i++)
							   {
								  $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
								  echo '
								  <tr>
								  <td>'.$row["F_NAME"].'</td>
								  
								  <td>'.$row["organized"].'</td>
								  <td>'.$row["durationf"].'</td>
								  <td>'.$row["durationt"].'</td>
								  <td>'.$row["award"].'</td>
								  <td>'.$row["res_type"].'</td>
								  <td>'.$row["topic"].'</td>
								  <td>'.$row["details"].'</td>
								  
								  
								  
								  </tr>';
								}
								echo "</table></div>";
						   }
						   else  if($display == 1)
						   {
							   echo "<div class='box-body'>
							   <br>Total number of Activities : $num
							   

							   <table align='' border='1' class='table table-stripped table-bordered' id = 'example1'>
							   
							   <tr id='tr1'>
								<th>Faculty Name</th>

							   <th>Organized by</th>
							   <th>Date from</th>
							   <th>Date to</th>
							   <th>Award</th>
							   <th>Resource Type</th>
							   <th>Topic</th>
							   <th>Details</th>
							   
							   </tr>";
							   for($i=1;$i<=$num;$i++)
							   {
								  $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
								  echo '
								  <tr>
								  <td>'.$row["F_NAME"].'</td>
								  
								  <td>'.$row["organized"].'</td>
								  <td>'.$row["durationf"].'</td>
								  <td>'.$row["durationt"].'</td>
								  <td>'.$row["award"].'</td>
								  <td>'.$row["res_type"].'</td>
								  <td>'.$row["topic"].'</td>
								  <td>'.$row["details"].'</td>
								  </tr>';
								}
								echo "</table></div>";
						   }
						   else  if($display == 3)
						   {
							   echo "<div class='box-body'>
							   Faculty Name : $sname
							   <br>Total number of Activities : $num
							   

							   <table align='' border='1' class='table table-stripped table-bordered' id = 'example1'>
							   
							   <tr id='tr1'>
								<th>Faculty Name</th>

							   <th>Organized by</th>
							   <th>Date from</th>
							   <th>Date to</th>
							   <th>Award</th>
							   <th>Resource Type</th>
							   <th>Topic</th>
							   <th>Details</th>
							   </tr>";
							   for($i=1;$i<=$num;$i++)
							   {
								  $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
								  echo '
								  <tr>
								  <td>'.$row["F_NAME"].'</td>
								  
								   <td>'.$row["organized"].'</td>
								  <td>'.$row["durationf"].'</td>
								  <td>'.$row["durationt"].'</td>
								  <td>'.$row["award"].'</td>
								  <td>'.$row["res_type"].'</td>
								  <td>'.$row["topic"].'</td>
								  <td>'.$row["details"].'</td>
								  </tr>';
								}
								echo "</table></div>";
						   }
					   $op = "<p align='center'  style='font-size:20px'><strong>K.J.Somaiya College of Engineering</strong></p>"."<p align='center'>(Autonomous College affiliated to University of Mumbai)</p>"."<p align='center'>Faculty Interaction Activity</p>"."<table  border='1' class='table table-stripped table-bordered' id = 'example1'>";
						   
				$html = ob_get_clean();

				$dompdf = new DOMPDF();
				$dompdf->load_html($op."<br>".$html);
				
				$dompdf->set_paper('a4', 'portrait');
				$dompdf->render();
                $dompdf->stream('hi',array('Attachment'=>0));
				
?>