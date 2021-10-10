<?php
 include("includes/view.php");
session_start();
ob_start();
if(!isset($_SESSION['loggedInUser'])){
    //send them to login page
    header("location:index.php");
}
$_SESSION['currentTab'] = "technical_review";

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
 $query = "SELECT * FROM facInteraction where Fac_ID='".$_SESSION['Fac_ID']."' ORDER BY invited_id ASC LIMIT $start_from,$record_per_page ";  
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
                <th>Organised By</th>
                <th>Duration from(YYYY-MM-DD)</th>
                <th>Duration to(YYYY-MM-DD)</th>
                <th> Invited as Resource person for </th>
                <th>Invitation Letter</th>
                <th>Certificate</th>
                <th>Edit</th>
                <th>Delete</th>
           </tr>  
 "; 

//  <th> Awards</th>
//  <th class='relative-side'> Invited as Resource person for </th>
//  <th> Topic of Lecture </th>
//  <th class='relative-side'> Details if any other activity </th>
//  <th> Last Updated On </th>
//  <th class='relative-side'>Invitation Letter</th>
//  <th>Certificate</th>
//  <th class='relative-side'>Edit</th>
//  <th>Delete</th>
//  </tr>  

 $output.= "</thead>"; 
 while($row = mysqli_fetch_array($result))  
 {  
      $output .= " 
           <tr>  
                <td>".$row['organised_by']."</td>
                <td>".$row['date_from']."</td>
                <td>".$row['date_to']."</td>
                <td>".$row['invitation']."</td>";

                // <td>.$row['award'].</td>
                // <td class='relative-side'>.$row['res_type'].</td>
                // <td>".$row['topic']."</td>
                // <td class='relative-side'>".$row['details']."</td>
                // <td>".$row['tdate']."</td>
                
                // $_SESSION['invited_id'] = $row['invited_id'];
            
                if(($row['invitation_path']) != "")
              {
                  if(($row['invitation_path']) == "NULL"){
                    $output.= "<td class='relative-side'>
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

               if(($row['certificate']) != "")
              {
                  if(($row['certificate']) == 'NULL'){
                    $output.= "<td>
                                  <form action = 'upload_certificate_ilec.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '".$row['invited_id']."'>
                                      <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                          <span class='glyphicon glyphicon-upload'></span>
                                      </button>
                                  </form>
                              </td>";
                  }
              
                  else if(($row['certificate']) == "not_applicable") 
                      $output.= "<td>not applicable</td>";
                  else
                      $output.= "<td> <a href = '".$row['certificate']."' target='_blank'>View Certificate</td>";
              }
              else
                $output.= "<td>no status </td>";

              
              $output.= "<td>
                    <form action = 'edit_invited_lec.php' method = 'POST'>
                        <input type = 'hidden' name = 'rid' value = '".$row['invited_id']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-edit'></span>
                        </button>
                    </form>
                </td>";

               $output.= "<td>
                    <form action = '4_delete_invited_lec.php' method = 'POST'>
                        <input type = 'hidden' name = 'rid' value = '".$row['invited_id']."'>
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

 $page_query = "SELECT * FROM facInteraction where Fac_ID='".$_SESSION['Fac_ID']."' ORDER BY invited_id ASC";  
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
   <?php if ($_SESSION['rows'] > 0) {?>
              <div>
              <a href="faculty_interaction_analysis.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Count Lectures</span></a> 

              <a href="guest2.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Lecture</span></a> 
             
              <a href="export_to_excel_invited.php" type="button" name="export" class="btn btn-success btn-sm"><span class="glyphicon ">Export to Excel</span></a> 

  <?php }else {?>
                <div class="text-left"><a href="guest2.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Lecture</span></a>

  <?php } ?>
  <br>
  <br>
    </div>
            </section>
    
</div>

<?php include_once('footer.php'); ?>  