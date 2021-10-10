<script>
$(document).ready(function(){
  $(".2").attr("class","active");
});
</script>

<script>
$(document).ready(function(){
    // $("select").change(function(){
    //     $(location).attr('href', 'IV.php?x=../IV/'+$(this).val()+'/edit.php');
    // });
    var table = $('#viewOrganized').DataTable( {       
        scrollX:  true,
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

$records = edit($organized,$f_id); //can be found in IVSql.php
$sql = editReturn($organized,$f_id);//return query

if(isset($_POST['upload']))
{
  $_SESSION['type']   = $organized; //will be found in IV.php 
  $_SESSION['id']   = $_POST['id']; 
  $_SESSION['file']   = $_POST['file']; 
  header("location:IV.php?x=IV/upload.php");
}

if (isset($_POST['edit']))
{
  $_SESSION['id'] = $_POST['id'];
  header("location:IV.php?x=IV/Organized/form.php"); 
}
if (isset($_POST['delete']))
{
  $_SESSION['type']   = $organized; 
  $_SESSION['id'] = $_POST['id'];
  header("location:IV/delete.php"); 
}
if(isset($_POST['add']))
{
  header("location:IV.php?x=IV/select_menu/addcount.php&type=Organized");
}
?>
<style>
  th{
    padding:0px;
    border: 1px solid black;
    background-color: #4CAF50;
      color: white;
    }
	
	div.scroll {
		overflow:scroll;
	}
		
</style>
 
  </head>
  
        <!-- Main content -->
              <div class="box">
           
                <div class="box-body">
                        <br>
              <!--    <div class="scroll"> -->
                  <?php
                   if(mysqli_num_rows($records)>0)
                        {
                  ?>
				  <div class='scroll'>
                  <table id="viewOrganized" class="table table-striped table-bordered ">
                    <thead>
                    <tr>
                      <th>Faculty name</th>
                      <th>Industry Name</th>
                      <th>City</th>
                      <th>Purpose</th>
                  <!--<th>Date</th>-->
                      <th>Target Audience</th>
                      <th>Staff</th>
                      <th>from</th>
                      <th>To</th>
                      <th>Updated at</th>
					  
					  <th> No of participants </th>
                      <th> IV Type </th>
                      <th> Details </th>
                      <th>Permission</th>
                      <th>Report</th>
                      <th>Certificate</th>
                      <th>Attendence</th>
                      <th>Edit</th>
                      <th>delete</th>					  
                    </tr>
                    </thead>
					</div>
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
                            echo"<td align='center'>".$employee['t_audience']."</td>";
                            echo"<td align='center'>".$employee['staff']."</td>";
                            echo"<td align='center' width='10%'>".date("d-m-Y",strtotime($employee['t_from']))."</td>";
                            echo"<td align='center' width='10%'>".date("d-m-Y",strtotime($employee['t_to']))."</td>";
                            echo"<td align='center'>".$employee['tdate']."</td>";

                            echo"<td align='center'>".$employee['part']."</td>";
							echo"<td align='center'>".$employee['ivtype']."</td>";
							echo"<td align='center'>".$employee['details']."</td>";
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
                                    <form action = 'IV.php?x=IV/organized/edit.php' method = 'POST'>
                                      <input type = 'hidden' name = 'file' value = 'permission'>
                                      <input type = 'hidden' name = 'id' value = '".$employee['id']."'>
                                        <button name ='upload' type = 'submit' class = 'btn btn-primary btn-sm'>
                                        <span class='glyphicon glyphicon-upload'></span>
                                        </button>
                                    </form>
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
                                    <form action = 'IV.php?x=IV/organized/edit.php' method = 'POST'>
                                      <input type = 'hidden' name = 'file' value = 'report'>
                                      <input type = 'hidden' name = 'id' value = '".$employee['id']."'>
                                        <button name ='upload' type = 'submit' class = 'btn btn-primary btn-sm'>
                                        <span class='glyphicon glyphicon-upload'></span>
                                        </button>
                                    </form>
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

                            echo "<td>
                                    <form action = 'IV.php?x=IV/organized/edit.php' method = 'POST'>
                                      <input type = 'hidden' name = 'file' value = 'certificate'>
                                      <input type = 'hidden' name = 'id' value = '".$employee['id']."'>
                                        <button name ='upload' type = 'submit' class = 'btn btn-primary btn-sm'>
                                        <span class='glyphicon glyphicon-upload'></span>
                                        </button>
                                    </form>
                                  </td>";
                             //echo "<td></td>";     
                            
                            echo "</tr></table></td>";
                            /// Attendence

                            echo"<td><table class='table-bordered' ><tr>";
                            if(($employee['attendance']) != "")
                            {
                              if(($employee['attendance']) == "NULL")
                                echo "<td width='100%'>not yet available</td>";
                              else if(($employee['attendance']) == "not_applicable") 
                                echo "<td width='100%'>not applicable</td>";
                              else
                                echo "<td width='100%'> <a href = '".$employee['attendance']."' target='_blank'>View attendance</td>";
                            }
                            else
                              echo "<td width='100%'>no status </td>";

                            echo "<td>
                                    <form action = 'IV.php?x=IV/organized/edit.php' method = 'POST'>
                                      <input type = 'hidden' name = 'file' value = 'attendance'>
                                      <input type = 'hidden' name = 'id' value = '".$employee['id']."'>
                                        <button name ='upload' type = 'submit' class = 'btn btn-primary btn-sm'>
                                        <span class='glyphicon glyphicon-upload'></span>
                                        </button>
                                    </form>
                                  </td>";
                             //echo "<td></td>";     
                            
                            echo "</tr></table>";

                             echo "<td align='center'>
                                    <form action = 'IV.php?x=IV/organized/edit.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '".$employee['id']."'> 
                                      <button name = 'edit' type = 'submit' class = 'btn btn-primary btn-sm'>
                                        <span class='glyphicon glyphicon-edit'></span>
                                      </button>
                                    </form>
                                  </td>";

                              echo "<td align='center'>
                                    <form action = 'IV.php?x=IV/organized/edit.php' method = 'POST'>
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
                           echo "<div class='alert alert-warning'>There are no IV Activities</div>";
                         }

                         						 
                    ?>
                    </tbody>
                   
                  </table>
               
             <!--   </div> -->
                <div class="row">
                  <div class="col-md-8">
                    <form method='POST' action="IV.php?x=IV/organized/edit.php">              
                    <button type=submit name='add' class="btn btn-primary" >Add Activity
                    </button>
                    <a href="IV/export_to_excel.php" type="button" class="btn btn-primary"><span class="glyphicon ">Export to Excel</span></a>
				   <a href="IV/printToPDF.php" type="button" class="btn btn-primary" target="_blank"><span class="glyphicon">Print</span></a>
					

                    </form>
                  </div>
                   <?php
                    if(mysqli_num_rows($records)>0)
                        {
                  ?> 
                 <div class="col-md-2">
                    <?php 
                     $_SESSION['table_query'] = $sql;
                     ?>
                  </div>
				  <div class= "col-md-2">
				   </div>
                 </div>
                </div> 
                  <?php
                  }
                  ?>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
			  
					  

  </body>
  </html>