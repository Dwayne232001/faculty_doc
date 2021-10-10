<script>
$(document).ready(function(){
    // $("select").change(function(){
    //     $(location).attr('href', 'IV.php?x=../IV/'+$(this).val()+'/edit.php');
    // });
    var table = $('#viewAttended').DataTable( {       
        scrollX:  true,
        scrollY: 300,
        scrollCollapse: true,
        autoWidth:      true,  
        paging:         true,  
        columnDefs: [
        { "width": "100px", "targets": [5,6,7,8] }
      
    ]     
     
    } );
});
</script>

<?php
  if(session_status() == PHP_SESSION_NONE)
   {
    //session has not started
    session_start();
   }
  if($_SESSION['username']=="")
   {
    header("refresh:2,url=index.php");
    die("Login Required");
   }
  else
  {
    $username = $_SESSION['username'];
  }
//the particular session will be only set in HOD login 
if(isset($_SESSION['edit_menu_faculty']))
{
  $f_id = $_SESSION['edit_menu_faculty'];
  unset($_SESSION['edit_menu_faculty']);
}
else
{
  $f_id = $_SESSION['f_id'];
}
$records = edit($attended,$f_id); //can be found in IVSql.php
$sql = editReturn($attended,$f_id);//return query
  $_SESSION['table_query'] = $sql;
  
if(isset($_POST['upload']))
{
  $_SESSION['type']   = $attended; 
  $_SESSION['id']   = $_POST['id']; 
  $_SESSION['file']   = $_POST['file']; 
  header("location:IV.php?x=IV/upload.php");
}

if (isset($_POST['edit']))
{
  
  $_SESSION['id'] = $_POST['id'];
  header("location:IV.php?x=IV/attended/form.php"); 
}

if (isset($_POST['delete']))
{
  $_SESSION['type']   = $attended; 
  $_SESSION['id'] = $_POST['id'];
  header("location:IV/delete.php"); 
}
if(isset($_POST['add']))
{
  header("location:IV.php?x=IV/select_menu/addcount.php&type=Attended");
}
?>
<style>
  th{
    padding:0px;
    border: 1px solid black;
    background-color: #4CAF50;
      color: white;
    }

</style>
 
  </head>
  
        <!-- Main content -->
              <div class="box">
           
                <div class="box-body">
                        <br>
                  <div class="scroll">
                  <?php
                    if(mysqli_num_rows($records)>0)
                        {
                          
                  ?>          
                  <table id="viewAttended" class="table table-striped table-bordered ">
                    <thead>
                    <tr>
                      <th>Faculty name</th>
                      <th>Industry Name</th>
                      <th>City</th>
                      <th>Purpose</th>
                  <!--<th>Date</th>-->
                      <th>from</th>
                      <th>To</th>
                      <th>Updated at</th>
					  
                      <th>Permission</th>
                      <th>Report</th>
                      <th>Certificate</th>
			  
                      <th>Edit</th>
                      <th>delete</th>  
              
				  </tr>
                    </thead>
                    <tbody>
                   <?php
                        //the values for id of user will be also sent from her (upload)
                      while($employee=mysqli_fetch_assoc($records))
                          {
                            $f_name = mysqli_fetch_assoc(getFacultyDetails($employee['f_id']))['F_NAME'];
                            echo"<tr>";
                            echo"<td align='center'>".$f_name."</td>";
                            echo"<td align='center'>".$employee['ind']."</td>";
                            echo"<td align='center'>".$employee['city']."</td>";
                            echo"<td align='center'>".$employee['purpose']."</td>";
                          //echo"<td>".$employee['date']."</td>";
                            echo"<td align='center' width='10%'>".date("d-m-Y",strtotime($employee['t_from']))."</td>";
                            echo"<td align='center' width='10%'>".date("d-m-Y",strtotime($employee['t_to']))."</td>";
                            echo"<td align='center'>".$employee['tdate']."</td>";

                            echo"<td><table class='table-bordered' ><tr>";
                            
                            if(($employee['permission']) != "")
                            {
                              if(($employee['permission']) == "NULL")
                                echo "<td width='100%'>not yet available</td>";
                              else if(($employee['permission']) == "not_applicable") 
                                echo "<td width='100%'>not applicable</td>";
                              else
                                echo "<td width='100%'> <a href = '".$employee['permission']."' target='_blank'>View permission</td>";
                            }
                            else
                              echo "<td width='100%'>no status</td>";

                             echo "<td>
                                    <form action = 'IV.php?x=IV/attended/edit.php' method = 'POST'>
                                      <input type = 'hidden' name = 'file' value = 'permission'>
                                      <input type = 'hidden' name = 'id' value = '".$employee['id']."'>";
					
                                   echo"     <button name ='upload' type = 'submit' class = 'btn btn-primary btn-sm'>
                                        <span class='glyphicon glyphicon-upload'></span>
                                        </button>";
					
                                   echo " </form>
                                  </td>";
                            echo"</tr></table></td>";  
                            
                            echo"<td><table class='table-bordered' ><tr>";
                            if(($employee['report']) != "")
                            {
                              if(($employee['report']) == "NULL")
                                echo "<td width='100%'>not yet available</td>";
                              else if(($employee['report']) == "not_applicable") 
                                echo "<td width='100%'>not applicable</td>";
                              else
                                echo "<td width='100%'> <a href = '".$employee['report']."' target='_blank'>View report</td>";
                            }
                            else
                              echo "<td width='100%'>no status </td>";
                            echo "<td>
                                    <form action = 'IV.php?x=IV/attended/edit.php' method = 'POST'>
                                      <input type = 'hidden' name = 'file' value = 'report'>
                                      <input type = 'hidden' name = 'id' value = '".$employee['id']."'>";
					                                
							echo "<button name ='upload' type = 'submit' class = 'btn btn-primary btn-sm'>
                                        <span class='glyphicon glyphicon-upload'></span>
                                        </button>";
					                                  
								echo "</form>
                                  </td>";
                            echo"</tr></table></td>";

                            echo"<td><table class='table-bordered' ><tr>";
                            if(($employee['certificate']) != "")
                            {
                              if(($employee['certificate']) == "NULL")
                                echo "<td width='100%'>not yet available</td>";
                              else if(($employee['certificate']) == "not_applicable") 
                                echo "<td width='100%'>not applicable</td>";
                              else
                                echo "<td width='100%'> <a href = '".$employee['certificate']."' target='_blank'>View certificate</td>";
                            }
                            else
                              echo "<td width='100%'>no status </td>";
                            echo  "<td>
                                    <form action = 'IV.php?x=IV/attended/edit.php' method = 'POST'>
                                      <input type = 'hidden' name = 'file' value = 'certificate'>
                                      <input type = 'hidden' name = 'id' value = '".$employee['id']."'>";
										  
                                      echo  " <button name ='upload' type = 'submit' class = 'btn btn-primary btn-sm'>
                                        <span class='glyphicon glyphicon-upload'></span>
                                        </button> ";
                                    
									echo "</form>
                                  </td>";
								  
                            echo"</tr></table></td>";
				
                             echo "<td align='center'>
                                    <form action = 'IV.php?x=IV/attended/edit.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '".$employee['id']."'> 
                                      <button name = 'edit' type = 'submit' class = 'btn btn-primary btn-sm'>
                                        <span class='glyphicon glyphicon-edit'></span>
                                      </button>
                                    </form>
                                  </td>";

                             echo "<td align='center'>
                                    <form action = 'IV.php?x=IV/attended/edit.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '".$employee['id']."'> 
                                      <button name ='delete' type = 'submit' class = 'btn btn-primary btn-sm'>
                                        <span class='glyphicon glyphicon-trash'></span>
                                      </button>
                                    </form>
                                  </td>";
                            echo"</tr>";
				
						  }
                         }
                         else
                         {
                           echo "<div class='alert alert-warning'>There no IV Activities</div>";
                         } 
                    ?>
                    </tbody>
                   
                  </table>
               
                </div>
                <div class="row">
                  <div class="col-md-8">
                    <form method='POST' action="IV.php?x=IV/attended/edit.php">   
                    <button type=submit name='add' class="btn btn-primary" >Add Activity</button>
					<a href="IV/export_to_excelold.php" type="button" class="btn btn-primary"><span class="glyphicon ">Export to Excel</span></a>
                    <a href="IV/printToPDF.php" type="button" class="btn btn-primary" target="_blank"><span class="glyphicon">Print</span></a>
                                  
				   </form>
                  </div>
                  <?php
                    if(mysqli_num_rows($records)>0)
                        {
                  ?>        
                  <div class="col-md-8">
                    <?php 
                     $_SESSION['table_query'] = $sql;
                     ?>
                   </div>
                </div> 
                  <?php
                  }
                  ?>
                </div><!-- /.box-body -->
              </div><!-- /.box -->

  </body>
  </html>