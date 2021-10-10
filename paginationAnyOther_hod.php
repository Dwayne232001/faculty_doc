<?php
 include("includes/view.php");
session_start();
ob_start();
if(!isset($_SESSION['loggedInUser'])){
    //send them to login page
    header("location:index.php");
}

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
$_SESSION['currentTab'] = "anyOther";


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
 $query = "SELECT * from any_other_activity inner join facultydetails on any_other_activity.Fac_ID = facultydetails.Fac_ID WHERE facultydetails.Dept='".$_SESSION['Dept']."' ORDER BY any_other_ID DESC LIMIT $start_from,$record_per_page ";  
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
          <h2 class="box-title"><b>Any Other Activity Details</b></h2> 
          <br>
          </div>
                </div>
            </div>

   <div class="box box-primary">

  <div id="table-scroll" class="table-scroll">
    <div class="table-wrap">
      <table class="main-table">
        <thead>
 <?php
 $output .= " 
            <tr> 
                <th class='fixed-side'>Activity Name</th>
                <th class='next-to-fixed-side'>Faculty Name</th>
                <th class='relative-side'>Purpose</th>
                <th>Organized By</th>
                
                <th class='relative-side'>Date from</th>
                <th>Date to</th>
                <th class='relative-side'>Last Edited</th>
                <th>Permission</th>
                <th class='relative-side'>Certificate</th>
                <th>Report</th>
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
      $output .= " 
           <tr>  
                <td class='fixed-side'>".$row['activity_name']."</td>
                <td class='next-to-fixed-side'>".$row['F_NAME']."</td>
                <td class='relative-side'>".$row['purpose_of_activity']."</td>
                <td>".$row['organized_by']."</td>
                
                <td class='relative-side'>".$row['Date_from']."</td>
                <td>".$row['Date_to']."</td>
                <td class='relative-side'>".$row['currentTimestamp']."</td>";
                
                $_SESSION['any_other_ID'] = $row['any_other_ID'];

              if(($row['permission_path']) != "")
              {
                  if(($row['permission_path']) == "NULL"){
                    $output.= "<td>
                                  <form action = 'upload-permission_anyother.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '".$row['any_other_ID']."'>
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

               if(($row['certificate_path']) != "")
              {
                  if(($row['certificate_path']) == 'NULL'){
                    $output.= "<td class='relative-side'>
                                  <form action = 'upload-certificate_anyother.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '".$row['any_other_ID']."'>
                                      <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                          <span class='glyphicon glyphicon-upload'></span>
                                      </button>
                                  </form>
                              </td>";
                  }
              
                  else if(($row['certificate_path']) == "not_applicable") 
                      $output.= "<td class='relative-side'>not applicable</td>";
                  else
                      $output.= "<td class='relative-side'> <a href = '".$row['certificate_path']."' target='_blank'>View Certificate</td>";
              }
              else
                $output.= "<td class='relative-side'>no status </td>";

              if(($row['report_path']) != "")
              {
                  if(($row['report_path']) == 'NULL'){
                    $output.= "<td>
                                  <form action = 'upload-report_anyother.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '".$row['any_other_ID']."'>
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
              
            if($_SESSION['type']!='cod' && $_SESSION['type']!='com'){

              $output.= "<td class='relative-side'>
                    <form action = '3_edit_anyother.php' method = 'POST'>
                        <input type = 'hidden' name = 'rid' value = '".$row['any_other_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-edit'></span>
                        </button>
                    </form>
                </td>";

               $output.= "<td>
                    <form action = '4_delete_anyother.php' method = 'POST'>
                        <input type = 'hidden' name = 'rid' value = '".$row['any_other_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-trash'></span>
                        </button>
                    </form>
                </td>";
              "</tr>";
            }
              
      $output.= "</tr>";

      }
 $output .= '</table><br /><div align="center">';
 ?>
  </div>
  </div>
  </div>
  
<?php

 $page_query = "SELECT * from any_other_activity inner join facultydetails on any_other_activity.Fac_ID = facultydetails.Fac_ID WHERE facultydetails.Dept='".$_SESSION['Dept']."' ORDER BY any_other_ID DESC";  
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
<a href="1_add_activity_multiple_hod_anyother.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add AnyOther Activity</span></a> 
<?php }?>
              <a href="count_all_anyother.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Count AnyOther Activity</span></a> 

              <a href="export_to_excel_hod_anyother.php" type="button" name="export" class="btn btn-success btn-sm"><span class="glyphicon ">Export to Excel</span></a> 

  <?php }else {
              
if($_SESSION['type'] == 'hod'){?>
              <div class="text-left"><a href="1_add_activity_multiple_hod_anyother.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add AnyOther Activity</span></a>

  <?php } }?>
  <br>
  <br>
    </div>
            </section>
    
</div>

<?php include_once('footer.php'); ?>  