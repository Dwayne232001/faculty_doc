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

$_SESSION['currentTab'] = "invitedlec";

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
 $query = "SELECT * from invitedlec inner join facultydetails on invitedlec.Fac_ID = facultydetails.Fac_ID WHERE facultydetails.Dept='".$_SESSION['Dept']."' ORDER BY invited_id DESC LIMIT $start_from,$record_per_page ";  
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
          <h2 class="box-title"><b>Faculty Interaction Details</b></h2> 
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
                <th class='fixed-side'>Organized By</th>
                <th class='next-to-fixed-side'>Faculty Name</th>
                <th class='relative-side'>Duration from(YYYY-MM-DD)</th>
                <th>Duration to(YYYY-MM-DD)</th> 
               
                <th class='relative-side'> Topic of Lecture </th>
                <th> Invited as Resource person for </th>
                <th class='relative-side'> Details if any other activity </th>
                <th> Last Updated On </th>
                <th class='relative-side'> Awards</th>
                <th>Invitation Letter</th>
                <th class='relative-side'>Certificate</th>
    "; 
    if($_SESSION['type']!='cod' && $_SESSION['type']!='com'){
              $output.=" <th>Edit</th>
                <th class='relative-side'>Delete</th>
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
                <td class='fixed-side'>".$row['organized']."</td>
                <td class='next-to-fixed-side'>".$row['F_NAME']."</td>
                <td class='relative-side'>".$row['durationf']."</td>
                <td >".$row['durationt']."</td>
                
                <td class='relative-side'>".$row['topic']."</td>
                <td>".$row['res_type']."</td>
                <td class='relative-side'>".$row['details']."</td>
                <td>".$row['tdate']."</td>
                <td class='relative-side'>".$row['award']."</td>";
                
                $_SESSION['invited_id'] = $row['invited_id'];
            
                if(($row['invitation_path']) != "")
              {
                  if(($row['invitation_path']) == "NULL"){
                    $output.= "<td>
                                  <form action = 'upload_invitation_ilec.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '".$row['invited_id']."'>
                                      <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                          <span class='glyphicon glyphicon-upload'></span>
                                      </button>
                                  </form>
                    </td>";
                  }
              
                  else if(($row['invitation_path']) == "not_applicable") 
                      $output.= "<td>not applicable</td>";
                  else
                      $output.= "<td> <a href = '".$row['invitation_path']."' target='_blank'>View Invitation</td>";
              }
              else
                $output.= "<td>no status </td>";

               if(($row['certificate_path']) != "")
              {
                  if(($row['certificate_path']) == 'NULL'){
                    $output.= "<td class='relative-side'>
                                  <form action = 'upload_certificate_ilec.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '".$row['invited_id']."'>
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

              if($_SESSION['type']!='com' && $_SESSION['type']!='cod'){
                  $output.= "<td>
                    <form action = 'edit_invited_lec.php' method = 'POST'>
                        <input type = 'hidden' name = 'rid' value = '".$row['invited_id']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-edit'></span>
                        </button>
                    </form>
                </td>";
              }
              if($_SESSION['type']!='com' && $_SESSION['type']!='cod'){
                $output.= "<td class='relative-side'>
                    <form action = '4_delete_invited_lec.php' method = 'POST'>
                        <input type = 'hidden' name = 'rid' value = '".$row['invited_id']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-trash'></span>
                        </button>
                    </form>
                </td>";
              }              
      $output.= "</tr>";

      }

 $output .= '</table><br /><div align="center">';
 ?>
  </div>
  </div>
  </div>
  
<?php

 $page_query = "SELECT * from invitedlec inner join facultydetails on invitedlec.Fac_ID = facultydetails.Fac_ID WHERE facultydetails.Dept='".$_SESSION['Dept']."' ORDER BY invited_id DESC";  
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
      <a href="facultyinteraction_hod.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Lecture</span></a> 
      <?php }?>
              <a href="analysis_f.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Count Lectures</span></a> 

             
             
              <a href="export_to_excel_invited_hod.php" type="button" name="export" class="btn btn-success btn-sm"><span class="glyphicon ">Export to Excel</span></a> 

  <?php }else {
  
if($_SESSION['type'] == 'hod'){?>
                <div class="text-left"><a href="facultyinteraction_hod.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Lecture</span></a>
  <?php } } ?>
  <br>
  <br>
    </div>
            </section>
    
</div>

<?php include_once('footer.php'); ?>  