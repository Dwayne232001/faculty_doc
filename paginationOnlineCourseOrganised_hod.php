<?php
 include("includes/view.php");
session_start();
ob_start();
if(!isset($_SESSION['loggedInUser'])){
    //send them to login page
    header("location:index.php");
}
$_SESSION['currentTab'] = "Online";

include("includes/connection.php");

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

 $record_per_page = 5;  
 $page = '';  
 $output = '';  
 if(isset($_POST["page"]))  
 {  
      $page = $_POST["page"];  
 }  
 else  
 {  
      $page = 1;  
 }  
 $start_from = ($page - 1)*$record_per_page;  
 $query = "SELECT * from online_course_organised inner join facultydetails on online_course_organised.Fac_ID = facultydetails.Fac_ID WHERE facultydetails.Dept='".$_SESSION['Dept']."' ORDER BY OC_O_ID DESC LIMIT $start_from,$record_per_page ";  
 $result = mysqli_query($conn, $query);
 $_SESSION['rows'] = mysqli_num_rows($result);
 ?>
        <div class="row">
          <div class="col-xs-12">
            <?php if(!isset($_GET['alert'])){ ?>
                       <br/>
      <?php } ?>
              <div class="box box-primary">
                <div class="box-header with-border">
          <div class="icon">
          <i style="font-size:18px" class="fa fa-table"></i>
          <h2 class="box-title"><b>Online/Offline Course Attended Details</b></h2> 
          <br>
          </div>
                </div>
            </div>
                <!-- /.box-header -->
        <!-- <div style="text-align:right">
        <a href="menu.php?menu=1 " style="text-align:right"> <u>Back to Paper Publication/Presentation Menu</u></a>&nbsp&nbsp  
                </div> -->

   <div class="box box-primary">

  <div id="table-scroll" class="table-scroll">
    <div class="table-wrap">
      <table class="main-table">
        <thead>
 <?php
 $output .= " 
            <tr> 
                <th class='fixed-side'>Course Name</th>
                <th class='next-to-fixed-side'>Faculty Name</th>
                <th class='relative-side'>Type of Course</th>
                <th>Duration From (y-m-d)</th>
                <th class='relative-side'>Duration To (y-m-d)</th>
                <th>Organised By</th>
                <th class='relative-side'>Purpose</th>
                <th>Target Audience</th>
                <th class='relative-side'>Faculty Role</th>
                <th>Full/Part time</th>
                <th class='relative-side'>Participants</th>
                <th>Duration</th>
                <th class='relative-side'>Status</th>
                <th>Sponsored</th>
                <th class='relative-side'>Name of sponsored</th>
                <th>Approved</th>
                <th class='relative-side'>Updated on</th>
                <th>Certificate</th>
                <th class='relative-side'>Report</th>
                <th>Attendence Record</th>
    "; 
    if($_SESSION['type']!='cod' && $_SESSION['type']!='com'){
              $output.=" <th class='relative-side'>Edit</th>
                <th>Delete</th>
           </tr> ";
      }
      else{
        $output.="</tr>";
      } 
 $output.= "</thead>"; 
 while($row = mysqli_fetch_array($result))  
 {   
      if($row['sponsored'] == 's')
                    $response = 'yes';
                else
                    $response = 'no';

      $output .= " 
           <tr>  
                <td class='fixed-side'>".$row['Course_Name']."</td>
                <td class='next-to-fixed-side'>".$row['F_NAME']."</td> 
                <td class='relative-side'>".$row['type_of_course']."</td>
                <td>".$row['Date_From']."</td>
                <td class='relative-side'>".$row['Date_To']."</td>
                <td>".$row['Organised_By']."</td>
                <td class='relative-side'>".$row['Purpose']."</td>
                <td>".$row['Target_Audience']."</td>
                <td class='relative-side'>".$row['faculty_role']."</td>
                <td>".$row['full_part_time']."</td>
                <td class='relative-side'>".$row['no_of_part']."</td>
                <td>".$row['duration']."</td>
                <td class='relative-side'>".$row['status']."</td>
                <td>".$response."</td>
                <td class='relative-side'>".$row['name_of_sponsor']."</td>
                <td>".$row['is_approved']."</td>
                <td class='relative-side'>".$row['updated_at']."</td>";

                $_SESSION['OC_O_ID'] = $row['OC_O_ID'];

               if(($row['certificate_path']) != "")
              {
                  if(($row['certificate_path']) == 'NULL'){
                    $output.= "<td>
                                  <form action = 'upload-certificate-organised.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '".$row['OC_O_ID']."'>
                                      <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                          <span class='glyphicon glyphicon-upload'></span>
                                      </button>
                                  </form>
                              </td>";
                  }
              
                  else if(($row['certificate_path']) == "not_applicable") 
                      $output.= "<td>not applicable</td>";
                  else
                      $output.= "<td> <a href = '".$row['certificate_path']."' target='_blank'>View Certificate</td>";
              }
              else
                $output.= "<td>no status </td>";

              if(($row['report_path']) != "")
              {
                  if(($row['report_path']) == 'NULL'){
                    $output.= "<td class='relative-side'>
                                  <form action = 'upload-report-organised.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '".$row['OC_O_ID']."'>
                                      <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                          <span class='glyphicon glyphicon-upload'></span>
                                      </button>
                                  </form>
                              </td>";
                  }
              
                  else if(($row['report_path']) == "not_applicable") 
                      $output.= "<td class='relative-side'>not applicable</td>";
                  else
                      $output.= "<td class='relative-side'> <a href = '".$row['report_path']."' target='_blank'>View Report</td>";
              }
              else
                $output.= "<td class='relative-side'>no status </td>";

              if(($row['attendence_path']) != "")
              {
                  if(($row['attendence_path']) == 'NULL'){
                    $output.= "<td>
                                  <form action = 'upload-attendence.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '".$row['OC_O_ID']."'>
                                      <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                          <span class='glyphicon glyphicon-upload'></span>
                                      </button>
                                  </form>
                              </td>";
                  }
              
                  else if(($row['attendence_path']) == "not_applicable") 
                      $output.= "<td>not applicable</td>";
                  else
                      $output.= "<td> <a href = '".$row['attendence_path']."' target='_blank'>View Attendance</td>";
              }
              else
                $output.= "<td>no status </td>";
            if($_SESSION['type']!='cod' && $_SESSION['type']!='com'){
              $output.= "<td class='relative-side'>
                    <form action = '3_edit_online_organised.php' method = 'POST'>
                        <input type = 'hidden' name = 'rid' value = '".$row['OC_O_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-edit'></span>
                        </button>
                    </form>
                </td>";

               $output.= "<td>
                    <form action = '4_delete_online_organised.php' method = 'POST'>
                        <input type = 'hidden' name = 'rid' value = '".$row['OC_O_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-trash'></span>
                        </button>
                    </form>
                </td>";
              "</tr>";
            }
          }
              
      $output.= "</tr>";

            
 $output .= '</table><br /><div align="center">';
 ?>
  </div>
  </div>
  </div>
  
<?php

 $page_query = "SELECT * from online_course_organised inner join facultydetails on online_course_organised.Fac_ID = facultydetails.Fac_ID WHERE facultydetails.Dept='".$_SESSION['Dept']."' ORDER BY OC_O_ID DESC";  
 $page_result = mysqli_query($conn, $page_query);  
 $total_records = mysqli_num_rows($page_result);  
 $total_pages = ceil($total_records/$record_per_page);  
 $output.= "</div></div><br/>";
 $output.= "<div align='center'>";
 for($i=1; $i<=$total_pages; $i++)  
 {
      $output .= "<span class='pagination_link pagination flex-wrap' style='cursor:pointer; padding:6px; border:1px solid #ccc ; border-radius : 3px;font-size:16px;' id='".$i."'>".$i."</span>";  
 }  
 $output.= "</div><br>";
 echo $output;  
 ?> 
 <br>
   <?php if ($_SESSION['rows'] > 0) {
       if($_SESSION['type'] == 'hod'){?>
       <div>
       <a href="1_add_course_organised_hod.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Course Organised</span></a> 
       <?php }?>
                <a href="count_all_online.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Count Courses Organised</span></a> 

              
                
                <a href="export_to_excel_online_organised_all.php" type="button" name="export" class="btn btn-success btn-sm"><span class="glyphicon ">Export to Excel</span></a> 

  <?php }else {
  
if($_SESSION['type'] == 'hod'){?>
              <div class="text-left">
                <a href="1_add_course_organised_hod.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Course Organised</span></a>

              <?php } }?>
                  <br>
                  <br>
              </div>
            </section>
    
</div>

<?php include_once('footer.php'); ?>  