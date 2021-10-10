 <style>
	div.scroll {
		overflow:scroll;
	}
	</style>
<?php 
ob_start();
 if(session_status() == PHP_SESSION_NONE)
   {
    session_start();
   }
 if(!isset($_SESSION['username']))
   {
    header("refresh:2,url=index.php");
   }
$isType;  
if(isset($_POST['attended']))
  {
    if(empty($_POST['min_date']) && empty($_POST['max_date']))
    {
      $result = view($attended,$f_id,0,1);   // 0 = date set and 1 = attended
      $sql = viewReturn($attended,$f_id,0,1); //for query return
      $isType = "Attended";
    }
    else
    {
      $result = view($attended,$f_id,1,1); // 1 = date set and 1 = attended
      $sql = viewReturn($attended,$f_id,1,1);
      $isType = "Attended";
    }
     $_SESSION['table_query'] = $sql;

  } 

if(isset($_POST['organized']))
  {
    if(empty($_POST['min_date']) && empty($_POST['max_date']))
    {
      $result = view($organized,$f_id,0,2); // 0 = date set and 2 = organized
      $sql = viewReturn($organized,$f_id,0,2);
      $isType = "Organized";
    }
    else
    {
      $result = view($organized,$f_id,1,2); // 1 = date set and 2 = organized
      $sql = viewReturn($organized,$f_id,1,2);
      $isType = "Organized";
    }
    $_SESSION['table_query'] = $sql;
	
  }

  if(empty($_POST['min_date']) && empty($_POST['max_date']))
    $date_display=" - NULL ";
  else
    $date_display=date("d-m-Y",strtotime($_POST['min_date']))." To ".date("d-m-Y",strtotime($_POST['max_date']));
?>

<h4> Date: <b><i><?php echo $date_display;   ?></i></b></h4>  
<div class="scroll">
  <?php
			if(isset($_POST['attended']))
			{
					  if(mysqli_num_rows($result)>0)
                      {
  ?>

					<div class="box-body">
                  <table border="1" class="table table-striped table-bordered ">
                    <thead>
                    <tr>
                      <?php 
					  
						if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
                        { ?>
                      <th>Faculty Name</th>
                      <?php 
                        }
                      ?>
                      <th>Industry Name</th>
                      <th>City</th>
                      <th>Purpose</th>
					  
                      <th>From</th>
                      <th>To</th>
                      <th>Updated at</th>
					  
                     </tr> 

<?php 

                          while($employee=mysqli_fetch_assoc($result))
                          {

                            echo"<tr>";
                            echo"</div>";
							if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
                            {
                            $f_name = mysqli_fetch_assoc(getFacultyDetails($employee['f_id']))['F_NAME'];
                            echo "<td>".$f_name."</td>";
                            }
                            echo"<td>".$employee['ind']."</td>";
                            echo"<td>".$employee['city']."</td>";
                            echo"<td>".$employee['purpose']."</td>";
					
                            echo"<td>".date("d-m-Y",strtotime($employee['t_from']))."</td>";
                            echo"<td>".date("d-m-Y",strtotime($employee['t_to']))."</td>";
                            echo"<td>".$employee['tdate']."</td>";
							
                            echo "</tr>";
                           }  
                         }
                         else
						 {
                            echo "<div class='alert alert-warning'>There no IV Activities</div>";
						 }
			}//attended
			else if(isset($_POST['organized']))
			{
				if(mysqli_num_rows($result)>0)
                      {
  ?>

					<div class="box-body">
                  <table border="1" class="table table-striped table-bordered ">
                    <thead>
                    <tr>
                      <?php 
					  
						if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
                        { ?>
                      <th>Faculty Name</th>
                      <?php 
                        }
                      ?>
                      <th>Industry Name</th>
                      <th>City</th>
                      <th>Purpose</th>
					  <th>No of Participants</th>
					  <th>Sponsored/Not Sponsored</th>
					  <th>Details of Sponsor</th>
                      <th>From</th>
                      <th>To</th>
                      <th>Updated at</th>
					  
                     </tr> 

					<?php 

                          while($employee=mysqli_fetch_assoc($result))
                          {

                            echo"<tr>";
                            echo"</div>";
							if($_SESSION['username'] == ('hodextc@somaiya.edu') || $_SESSION['username'] == ('member@somaiya.edu') || $_SESSION['username'] == ('hodcomp@somaiya.edu') )
                            {
                            $f_name = mysqli_fetch_assoc(getFacultyDetails($employee['f_id']))['F_NAME'];
                            echo "<td>".$f_name."</td>";
                            }
                            echo"<td>".$employee['ind']."</td>";
                            echo"<td>".$employee['city']."</td>";
                            echo"<td>".$employee['purpose']."</td>";
							echo"<td>".$employee['part']."</td>";
							echo"<td>".$employee['ivtype']."</td>";
							echo"<td>".$employee['details']."</td>"; 
                            echo"<td>".date("d-m-Y",strtotime($employee['t_from']))."</td>";
                            echo"<td>".date("d-m-Y",strtotime($employee['t_to']))."</td>";
                            echo"<td>".$employee['tdate']."</td>";
							
                            echo "</tr>";
                           }  
                         }
                         else
						 {
                            echo "<div class='alert alert-warning'>There no IV Activities</div>";
						 }
			}//organised

						 
 ?>
  </thead>
 </table>
</div>


                  <div>
                    <?php
                      if(mysqli_num_rows($result)>0)
                      { 
                        //$_SESSION['table_query'] = $sql;
						if(isset($_POST['attended']))
						{			
							echo "<a href= 'IV/export_to_excel_attended_analysis.php?flag=1&type=$isType&count=$total&date=$date_display' type='button' class='btn btn-success btn-sm' ><span class='glyphicon'>Export to Excel</span></a>";
                        }
						else
							echo "<a href= 'IV/export_to_excel.php?flag=1&type=$isType&count=$total&date=$date_display' type='button' class='btn btn-success btn-sm' ><span class='glyphicon'>Export to Excel</span></a>";
						
						echo " ";
                        echo "<a href='IV/printToPDF.php?flag=1&type=$isType&count=$total&date=$date_display' type='button' class='btn btn-success btn-sm' target='_blank'><span class='glyphicon'>Print</span></a>";
                      }
                    ?>
                  </div>


    

