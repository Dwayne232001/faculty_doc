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
                                <td><strong>Faculty Name</font></strong></td>
                                <td><strong>Industry Name</font></strong></td>
                                <td><strong>City</font></strong></td>	
                                <td><strong>Purpose</font></strong></td>
                                <td><strong>Audience</font></strong></td>
                                <td><strong>No. of participants</font></strong></td>
                                <td><strong>Staff</font></strong></td>
                                <td><strong>IV Type</font></strong></td>
                                <td><strong>From Date</strong></td>
                                <td><strong>To Date</strong></td>
							   
							   </tr>";
							   for($i=1;$i<=$num;$i++)
							   {
								  $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
								  echo '<tr>';
                                        echo "<td>".$sname."</td>";		
                                        echo "<td>".$row['ind']."</td>";
                                        echo "<td>".$row['city']."</td>";
                                        echo "<td>".$row['purpose']."</td>";
                                        echo "<td>".$row['t_audience']."</td>";
                                        echo "<td>".$row['part']."</td>";
                                        echo "<td>".$row['staff']."</td>";
                                        echo "<td>".$row['ivtype']."</td>";
                                        echo "<td>".$row['t_from']."</td>";
                                        echo "<td>".$row['t_to']."</td>";
								  
								  echo '</tr>';
								}
								echo "</table></div>";
						   }
						   else  if($display == 1)
						   {
							   echo "<div class='box-body'>
							   <br>Total number of Activities : $num
							   

							   <table align='' border='1' class='table table-stripped table-bordered' id = 'example1'>
							   
							   <tr id='tr1'>
							            <td><strong>Faculty Name</font></strong></td>
										<td><strong>Industry Name</font></strong></td>
										<td><strong>City</font></strong></td>	
										<td><strong>Purpose</font></strong></td>
                                        <td><strong>Audience</font></strong></td>
                                        <td><strong>No. of participants</font></strong></td>
                                        <td><strong>Staff</font></strong></td>
                                        <td><strong>IV Type</font></strong></td>
										<td><strong>From Date</strong></td>
										<td><strong>To Date</strong></td>
							   </tr>";
							   for($i=1;$i<=$num;$i++)
							   {
								  $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
								  echo '<tr>';
                                        echo "<td>".$sname."</td>";		
										echo "<td>".$row['ind']."</td>";
                                        echo "<td>".$row['city']."</td>";
                                        echo "<td>".$row['purpose']."</td>";
                                        echo "<td>".$row['t_audience']."</td>";
                                        echo "<td>".$row['part']."</td>";
                                        echo "<td>".$row['staff']."</td>";
                                        echo "<td>".$row['ivtype']."</td>";
										echo "<td>".$row['t_from']."</td>";
										echo "<td>".$row['t_to']."</td>";
                                  echo '</tr>';
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
							            <td><strong>Faculty Name</font></strong></td>
										<td><strong>Industry Name</font></strong></td>
										<td><strong>City</font></strong></td>	
										<td><strong>Purpose</font></strong></td>
                                        <td><strong>Audience</font></strong></td>
                                        <td><strong>No. of participants</font></strong></td>
                                        <td><strong>Staff</font></strong></td>
                                        <td><strong>IV Type</font></strong></td>
										<td><strong>From Date</strong></td>
										<td><strong>To Date</strong></td>
							   </tr>";
							   for($i=1;$i<=$num;$i++)
							   {
								  $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
								  echo '<tr>';
                                        echo "<td>".$sname."</td>";		
                                        echo "<td>".$row['ind']."</td>";
                                        echo "<td>".$row['city']."</td>";
                                        echo "<td>".$row['purpose']."</td>";
                                        echo "<td>".$row['t_audience']."</td>";
                                        echo "<td>".$row['part']."</td>";
                                        echo "<td>".$row['staff']."</td>";
                                        echo "<td>".$row['ivtype']."</td>";
                                        echo "<td>".$row['t_from']."</td>";
                                        echo "<td>".$row['t_to']."</td>";
								  echo '</tr>';
								}
								echo "</table></div>";
						   }
					   $op = "<p align='center'  style='font-size:20px'><strong>K.J.Somaiya College of Engineering</strong></p>"."<p align='center'>(Autonomous College affiliated to University of Mumbai)</p>"."<p align='center'>Industrial Visit</p>"."<table  border='1' class='table table-stripped table-bordered' id = 'example1'>";
						   
				$html = ob_get_clean();

				$dompdf = new DOMPDF();
				$dompdf->load_html($op."<br>".$html);
				
				$dompdf->set_paper('a4', 'portrait');
				$dompdf->render();
                $dompdf->stream('hi',array('Attachment'=>0));
				
?>