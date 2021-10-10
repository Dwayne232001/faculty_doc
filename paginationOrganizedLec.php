<?php
 include("includes/view.php");
session_start();
ob_start();
if(!isset($_SESSION['loggedInUser'])){
    //send them to login page
    header("location:index.php");
}
$_SESSION['currentTab'] = "organised_guest";

if($_SESSION['type'] != 'faculty'){
	header("location:index.php");
}

//connect to database
include("includes/connection.php");
$fid = $_SESSION['Fac_ID'];
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
 $query = "SELECT * FROM guestlec where fac_id='".$_SESSION['Fac_ID']."' ORDER BY p_id ASC LIMIT $start_from,$record_per_page ";  
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
          <h2 class="box-title"><b>Organized Guest Lecture Details</b></h2>
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
                <th class='fixed-side'>Topic</th>
                <th class= 'next-to-fixed-side'>Organization</th>
                <th class='relative-side'>Resource Person Name</th>
                <th>Designation</th>
                <th class='relative-side'>Target Audience</th>
                <th >Duration from (YYYY/MM/DD)</th>
                <th class='relative-side'>Duration to (YYYY/MM/DD)</th>
                <th>Last Updated on</th>
                <th class='relative-side'>Attendance Record</th>
                <th>Permission Record</th>
                <th class='relative-side'>Certificate Copy</th>
                <th>Report Copy</th>  
                <th class='relative-side'>Edit</th>
                <th>Delete</th>
           </tr>  
 "; 
 $output.= "</thead>"; 
 while($row = mysqli_fetch_array($result))  
 {  
      $output .= " 
           <tr>  
                <td class='fixed-side'>".$row['topic']."</td>
                <td class= 'next-to-fixed-side'>".$row['organisation']."</td>
                <td class='relative-side'>".$row['name']."</td>
                <td>".$row['designation']."</td>
                <td class='relative-side'>".$row['targetaudience']."</td>
                
                <td >".$row['durationf']."</td>
                <td class='relative-side'>".$row['durationt']."</td>
                <td>".$row['tdate']."</td>";
                
                $_SESSION['P_ID'] = $row['p_id'];
            
                if(($row['attendance_path']) != "")
              {
                  if(($row['attendance_path']) == "NULL"){
                    $output.= "<td class='relative-side'>
                                  <form action = 'upload_attendance_glec.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '".$row['p_id']."'>
                                      <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                          <span class='glyphicon glyphicon-upload'></span>
                                      </button>
                                  </form>
                    </td>";
                  }
              
                  else if(($row['attendance_path']) == "not_applicable") 
                      $output.= "<td class='relative-side'>not applicable</td>";
                  else
                      $output.= "<td class='relative-side'> <a href = '".$row['attendance_path']."' target='_blank'>View Attendance</td>";
              }
              else
                $output.= "<td class='relative-side'>no status </td>";

              if(($row['permission_path']) != "")
              {
                  if(($row['permission_path']) == "NULL"){
                    $output.= "<td>
                                  <form action = 'upload_permission_glec.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '".$row['p_id']."'>
                                      <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                          <span class='glyphicon glyphicon-upload'></span>
                                      </button>
                                  </form>
                    </td>";
                  }
              
                  else if(($row['permission_path']) == "not_applicable") 
                      $output.= "<td>not applicable</td>";
                  else
                      $output.= "<td> <a href = '".$row['permission_path']."' target='_blank'>View Permission</td>";
              }
              else
                $output.= "<td>no status </td>";

               if(($row['certificate1_path']) != "")
              {
                  if(($row['certificate1_path']) == 'NULL'){
                    $output.= "<td class='relative-side'>
                                  <form action = 'upload_certificate_glec.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '".$row['p_id']."'>
                                      <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                          <span class='glyphicon glyphicon-upload'></span>
                                      </button>
                                  </form>
                              </td>";
                  }
              
                  else if(($row['certificate1_path']) == "not_applicable") 
                      $output.= "<td class='relative-side'>not applicable</td>";
                  else
                      $output.= "<td class='relative-side'> <a href = '".$row['certificate1_path']."' target='_blank'>View Certificate</td>";
              }
              else
                $output.= "<td class='relative-side'>no status </td>";

              if(($row['report_path']) != "")
              {
                  if(($row['report_path']) == 'NULL'){
                    $output.= "<td>
                                  <form action = 'upload_report_glec.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '".$row['p_id']."'>
                                      <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                          <span class='glyphicon glyphicon-upload'></span>
                                      </button>
                                  </form>
                              </td>";
                  }
              
                  else if(($row['report_path']) == "not_applicable") 
                      $output.= "<td>not applicable</td>";
                  else
                      $output.= "<td> <a href = '".$row['report_path']."' target='_blank'>View Report</td>";
              }
              else
                $output.= "<td>no status </td>";

              
              $output.= "<td class='relative-side'>
                    <form action = '41_edit.php' method = 'POST'>
                        <input type = 'hidden' name = 'rid' value = '".$row['p_id']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-edit'></span>
                        </button>
                    </form>
                </td>";

               $output.= "<td>
                    <form action = '4_delete_org_lec.php' method = 'POST'>
                        <input type = 'hidden' name = 'rid' value = '".$row['p_id']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-trash'></span>
                        </button>
                    </form>
                </td>";
              "</tr>";
              
      $output.= "</tr>";

      }

 $output .= '</table><br /><div align="center">';
 ?>
  </div>
  </div>
  </div>
  
<?php

 $page_query = "SELECT * FROM guestlec where Fac_ID='".$_SESSION['Fac_ID']."' ORDER BY p_id ASC";  
 $page_result = mysqli_query($conn, $page_query);  
 $total_records = mysqli_num_rows($page_result);  
 $total_pages = ceil($total_records/$record_per_page);  
 $output.= "</div></div><br/>";
 $output.= "<div align='center'>";
 for($i=1; $i<=$total_pages; $i++)  
 {
      $output .= "<span class='pagination_link pagination  flex-wrap' style='cursor:pointer; padding:6px; border:1px solid #ccc ; border-radius : 3px;font-size:16px;' id='".$i."'>".$i."</span>";  
 }  
 $output.= "</div><br>";
 echo $output;  
 ?> 
 <br>
   <?php if ($_SESSION['rows'] > 0) {?>
              <div>
              <a href="analysis_i.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Count Organized Lectures</span></a> 

              <a href="organised_guest.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Organized Lecture</span></a> 
             
              <a href="export_to_excel_guest_organised.php" type="button" name="export" class="btn btn-success btn-sm"><span class="glyphicon ">Export to Excel</span></a> 

  <?php }else {?>
              <div class="text-left"><a href="organized_guest.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Organized Lecture</span></a>

  <?php } ?>
  <br>
  <br>
    </div>
            </section>
    
</div>

<?php include_once('footer.php'); ?>  