

 <?php
 include("includes/view.php");
session_start();
ob_start();
if(!isset($_SESSION['loggedInUser'])){
    //send them to login page
    header("location:index.php");
}
$_SESSION['currentTab'] = "technical_review";

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

 $queryrun="SELECT Dept FROM facultydetails where Fac_ID=$fid";
 $resultrun = mysqli_query($conn, $queryrun);
while($row=mysqli_fetch_assoc($resultrun)){
  $_SESSION['Dept']=$row['Dept'];
}

 $start_from = ($page - 1)*$record_per_page;  
 $query = "SELECT * from paper_review inner join facultydetails on paper_review.Fac_ID = facultydetails.Fac_ID WHERE facultydetails.Dept='".$_SESSION['Dept']."' ORDER BY paper_review_ID DESC LIMIT $start_from,$record_per_page ";  
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
          <h2 class="box-title"><b>Technical Paper Reviewed Details</b></h2> 
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
                <th class='fixed-side'>Paper Title</th>
                <th class='next-to-fixed-side'>Faculty</th>
                <th class='relative-side'>Paper Type</th>
                <th>Paper Level National/International</th>
                <th class='relative-side'>Conference/Journal Name</th>
                <th>Paper Category</th>
                <th class='relative-side'>Date from (YYYY-MM-DD)</th>
                <th>Date to (YYYY-MM-DD)</th>
                <th class='relative-side'>Oranized by</th>
                <th>Volume</th>
                <th class='relative-side'>Paper details</th>
                <th>Last updated</th>
                <th class='relative-side'>Mail/Letter</th>
                <th>Certificate</th>
                <th class='relative-side'>Report</th>
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
                <td class='fixed-side'>".$row['Paper_title']."</td>
                <td class='next-to-fixed-side'>".$row['F_NAME']."</td> 
                <td  class='relative-side'>".$row['Paper_N_I']."</td>
                <td>".$row['Paper_type']."</td>
                <td class='relative-side'>".$row['conf_journal_name']."</td>
                <td>".$row['paper_category']."</td>
                <td  class='relative-side'>".$row['Date_from']."</td>
                <td>".$row['Date_to']."</td>
                <td class='relative-side'>".$row['organised_by']."</td>
                <td>".$row['volume']."</td>
                <td class='relative-side'>".$row['details']."</td>
                <td>".$row['last_added']."</td>";
                
                $_SESSION['paper_review_ID'] = $row['paper_review_ID'];
            
                if(($row['mail_letter_path']) != "")
              {
                  if(($row['mail_letter_path']) == "NULL"){
                    $output.= "<td  class='relative-side'>
                                  <form action = 'upload-mail-letter.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '".$row['paper_review_ID']."'>
                                      <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                          <span class='glyphicon glyphicon-upload'></span>
                                      </button>
                                  </form>
                    </td>";
                  }
              
                  else if(($row['mail_letter_path']) == "not_applicable") 
                      $output.= "<td  class='relative-side'>not applicable</td>";
                  else
                      $output.= "<td  class='relative-side'> <a href = '".$row['mail_letter_path']."' target='_blank'>View Mail Letter</td>";
              }
              else
                $output.= "<td  class='relative-side'>no status </td>";

               if(($row['certificate_path']) != "")
              {
                  if(($row['certificate_path']) == 'NULL'){
                    $output.= "<td>
                                  <form action = 'upload-certificate-review.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '".$row['paper_review_ID']."'>
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
                    $output.= "<td  class='relative-side'>
                                  <form action = 'upload-report-review.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '".$row['paper_review_ID']."'>
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

              if($_SESSION['type']!='com' && $_SESSION['type']!='cod'){
                $output.= "<td>
                      <form action = '3_edit_review.php' method = 'POST'>
                          <input type = 'hidden' name = 'rid' value = '".$row['paper_review_ID']."'>
                          <button type = 'submit' class = 'btn btn-primary btn-sm'>
                              <span class='glyphicon glyphicon-edit'></span>
                          </button>
                      </form>
                  </td>";
                }
                if($_SESSION['type']!='com' && $_SESSION['type']!='cod'){
                   $output.= "<td  class='relative-side'>
                        <form action = '4_delete_review.php' method = 'POST'>
                            <input type = 'hidden' name = 'rid' value = '".$row['paper_review_ID']."'>
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

 $page_query = "SELECT * from paper_review inner join facultydetails on paper_review.Fac_ID = facultydetails.Fac_ID WHERE facultydetails.Dept='".$_SESSION['Dept']."' ORDER BY paper_review_ID DESC";  
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
          <a href="1_add_paper_multiple_review_hod.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Paper Reviewed</span></a>
          <?php }?>
          <a href="count_all_review.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Count Papers Reviewed</span></a> 

          <a href="export_to_excel_review_hod.php" type="button" name="export" class="btn btn-success btn-sm"><span class="glyphicon ">Export to Excel</span></a> 

  <?php }else {
  
if($_SESSION['type'] == 'hod'){?>
                <div class="text-left"><a href="researchForm.php"type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Paper Reviewed</span></a>

  <?php } }?>
  <br>
  <br>
    </div>
            </section>
    
</div>

<?php include_once('footer.php'); ?>  