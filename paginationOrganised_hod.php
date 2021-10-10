<?php
 include("includes/view.php");
session_start();
ob_start();
if(!isset($_SESSION['loggedInUser'])){
    //send them to login page
    header("location:index.php");
}
$_SESSION['currentTab'] = "sttp";

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
 $query = "SELECT * from organised inner join facultydetails on organised.Fac_ID = facultydetails.Fac_ID WHERE facultydetails.Dept='".$_SESSION['Dept']."' ORDER BY A_ID DESC LIMIT $start_from,$record_per_page ";  
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
          <h2 class="box-title"><b>STTP/Workshop/FDP Organized Activity Details</b></h2> 
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
                <th class='fixed-side'>Title</th>
                <th class='next-to-fixed-side'>Faculty Name</th>
                <th class='relative-side'>Type</th>
                <th>Organized by</th>
                <th class='relative-side'>Resource Person</th>
                <th>Date from:<br><small>Y-M-D</small></th>
                <th class='relative-side'>Date to:<br><small>Y-M-D</small></th>
                <th >Duration</th>
                
                <th class='relative-side'>Co-ordinated by</th>
                <th>Role of Faculty</th>
                <th class='relative-side'>Full time/Part time</th>
                <th>No of Participants</th>
                <th class='relative-side'>Location</th>
                <th>Status of Activity</th>
                <th class='relative-side'>Sponsors</th>
                <th>Sponsorship Details</th>
                <th class='relative-side'>Approval Details</th>
                <th>Last Updated</th>
                <th class='relative-side'>Permission Letter</th>
                <th>Certificate</th>
                <th class='relative-side'>Report</th>
                <th>Brochure</th>
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
                <td class='fixed-side'>".$row['Act_title']."</td>
                <td class='next-to-fixed-side'>".$row['F_NAME']."</td>
                <td class='relative-side'>".$row['Act_type']."</td>
                <td>".$row['Organized_by']."</td>
                <td class='relative-side'>".$row['Resource']."</td>
                <td>".$row['Date_from']."</td>
                <td class='relative-side'>".$row['Date_to']."</td>
                <td>".$row['Equivalent_Duration']."</td>
                <td class='relative-side'>".$row['Coordinated_by']."</td>
                <td>".$row['Role_Of_Faculty']."</td>
                <td class='relative-side'>".$row['Time_Activities']."</td>
                <td>".$row['No_Of_Participants']."</td>
                <td class='relative-side'>".$row['Location']."</td>
                <td>".$row['Status_Of_Activity']."</td>
                <td class='relative-side'>".$row['Sponsorship']."</td>
                <td>".$row['Sponsor_Details']."</td>
                <td class='relative-side'>".$row['Approval_Details']."</td>
                <td>".$row['LastUpdated']."</td>";

                $_SESSION['A_ID'] = $row['A_ID'];

              if(($row['Permission_path']) != "")
              {
                  if(($row['Permission_path']) == "NULL"){
                    $output.= "<td class='relative-side'>
                                  <form action = 'upload-permission_organised.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '".$row['A_ID']."'>
                                      <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                          <span class='glyphicon glyphicon-upload'></span>
                                      </button>
                                  </form>
                    </td>";
                  }
              
                  else if(($row['Permission_path']) == "not_applicable") 
                      $output.= "<td class='relative-side'>not applicable</td>";
                  else
                      $output.= "<td class='relative-side'> <a href = '".$row['Permission_path']."' target='_blank'>View Permission</td>";
              }
              else
                $output.= "<td class='relative-side'>no status </td>";

               if(($row['Certificate_path']) != "")
              {
                  if(($row['Certificate_path']) == 'NULL'){
                    $output.= "<td>
                                  <form action = 'upload-certificate_organised.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '".$row['A_ID']."'>
                                      <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                          <span class='glyphicon glyphicon-upload'></span>
                                      </button>
                                  </form>
                              </td>";
                  }
              
                  else if(($row['Certificate_path']) == "not_applicable") 
                      $output.= "<td>not applicable</td>";
                  else
                      $output.= "<td> <a href = '".$row['Certificate_path']."' target='_blank'>View Certificate</td>";
              }
              else
                $output.= "<td>no status </td>";

              if(($row['Report_path']) != "")
              {
                  if(($row['Report_path']) == 'NULL'){
                    $output.= "<td class='relative-side'>
                                  <form action = 'upload-report_organised.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '".$row['A_ID']."'>
                                      <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                          <span class='glyphicon glyphicon-upload'></span>
                                      </button>
                                  </form>
                              </td>";
                  }
              
                  else if(($row['Report_path']) == "not_applicable") 
                      $output.= "<td class='relative-side'>not applicable</td>";
                  else
                      $output.= "<td class='relative-side'> <a href = '".$row['Report_path']."' target='_blank'>View Report</td>";
              }
              else
                $output.= "<td class='relative-side'>no status </td>";

              if(($row['Brochure_path']) != "")
              {
                  if(($row['Brochure_path']) == 'NULL'){
                    $output.= "<td>
                                  <form action = 'upload-brochure_organised.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '".$row['A_ID']."'>
                                      <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                          <span class='glyphicon glyphicon-upload'></span>
                                      </button>
                                  </form>
                              </td>";
                  }
              
                  else if(($row['Brochure_path']) == "not_applicable") 
                      $output.= "<td>not applicable</td>";
                  else
                      $output.= "<td> <a href = '".$row['Brochure_path']."' target='_blank'>View Brochure</td>";
              }
              else
                $output.= "<td>no status </td>";
              
              if($_SESSION['type']!='cod' && $_SESSION['type']!='com'){
                $output.= "<td class='relative-side'>
                    <form action = '3_edit_organised.php' method = 'POST'>
                        <input type = 'hidden' name = 'rid' value = '".$row['A_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-edit'></span>
                        </button>
                    </form>
                </td>";
              }
              if($_SESSION['type']!='cod' && $_SESSION['type']!='com'){
                $output.= "<td>
                    <form action = '4_delete_organised.php' method = 'POST'>
                        <input type = 'hidden' name = 'rid' value = '".$row['A_ID']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-trash'></span>
                        </button>
                    </form>
                </td>";
            }
          }
              
      $output.= "</tr>";

            
 $output .= '</table><br /><div align="center">';
 ?>
  </div>
  </div>
  </div>
  
<?php

 $page_query = "SELECT * from organised inner join facultydetails on organised.Fac_ID = facultydetails.Fac_ID WHERE facultydetails.Dept='".$_SESSION['Dept']."' ORDER BY A_ID DESC";  
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
      <a href="1_add_paper_multiple_organised_hod.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Organised Activity</span></a>
      <?php }?>    
          <a href="count_all_attend.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Count Organised Activity</span></a> 

              <a href="export_to_excel_organised_hod.php" type="button" name="export" class="btn btn-success btn-sm"><span class="glyphicon ">Export to Excel</span></a> 

  <?php }else {
  
if($_SESSION['type'] == 'hod'){?>
              <div class="text-left"><a href="1_add_paper_multiple_organised_hod.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Organised Activity</span></a>

  <?php } }?>
  <br>
  <br>
    </div>
            </section>
    
</div>

<?php include_once('footer.php'); ?>  