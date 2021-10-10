

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
 $query = "SELECT * FROM paper_review where Fac_ID='".$_SESSION['Fac_ID']."' ORDER BY paper_review_ID ASC LIMIT $start_from,$record_per_page ";  
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

   <div class="box box-primary">

  <div id="table-scroll" class="table-scroll">
    <div class="table-wrap">
      <table class="main-table">
        <thead>
 <?php
 $output .= " 
           <tr>  
                <th class='fixed-side'>Paper Title</th>
                <th class= 'next-to-fixed-side'>Paper Type</th>
                <th class='relative-side'>Conference/Journal Name</th>
                <th>Paper Level National/International</th>
                
                <th  class='relative-side'>Paper Category</th>
                <th>Date from (YYYY-MM-DD)</th>
                <th  class='relative-side'>Date to (YYYY-MM-DD)</th>
                <th>Oranized by</th>
                <th class='relative-side'>Paper details</th>
                <th>Volume</th>
                <th  class='relative-side'>Last updated</th>
                <th>Mail/Letter</th>
                <th  class='relative-side'>Certificate</th>
                <th>Report</th>
                <th class='relative-side'>Edit</th>
                <th>Delete</th>
           </tr>  
 "; 
 $output.= "</thead>"; 
 while($row = mysqli_fetch_array($result))  
 {  
      $output .= " 
           <tr>  
                <td class='fixed-side'>".$row['Paper_title']."</td>
                <td class= 'next-to-fixed-side'>".$row['Paper_type']."</td>
                <td  class='relative-side'>".$row['conf_journal_name']."</td>
                <td>".$row['Paper_N_I']."</td>
                
                <td class='relative-side'>".$row['paper_category']."</td>
                <td>".$row['Date_from']."</td>
                <td class='relative-side'>".$row['Date_to']."</td>
                <td>".$row['organised_by']."</td>
                <td class='relative-side'>".$row['details']."</td>
                <td>".$row['volume']."</td>
                <td class='relative-side'>".$row['last_added']."</td>";
                
                $_SESSION['paper_review_ID'] = $row['paper_review_ID'];
            
                if(($row['mail_letter_path']) != "")
              {
                  if(($row['mail_letter_path']) == "NULL"){
                    $output.= "<td>
                                  <form action = 'upload-mail-letter.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '".$row['paper_review_ID']."'>
                                      <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                          <span class='glyphicon glyphicon-upload'></span>
                                      </button>
                                  </form>
                    </td>";
                  }
              
                  else if(($row['mail_letter_path']) == "not_applicable") 
                      $output.= "<td>not applicable</td>";
                  else
                      $output.= "<td> <a href = '".$row['mail_letter_path']."' target='_blank'>View Mail/Letter</td>";
              }
              else
                $output.= "<td>no status </td>";

               if(($row['certificate_path']) != "")
              {
                  if(($row['certificate_path']) == 'NULL'){
                    $output.= "<td class='relative-side'>
                                  <form action = 'upload-certificate-review.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '".$row['paper_review_ID']."'>
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
                                  <form action = 'upload-report-review.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '".$row['paper_review_ID']."'>
                                      <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                          <span class='glyphicon glyphicon-upload'></span>
                                      </button>
                                  </form>
                              </td>";
                  }
              
                  else if(($row['report_path']) == "not_applicable") 
                      $output.= "<td>not applicable</td>";
                  else
                      $output.= "<td> <a href = '".$row['report_path']."' target='_blank'>View report</td>";
              }
              else
                $output.= "<td>no status </td>";
              
              $output.= "<td class='relative-side'>
                    <form action = '3_edit_review.php' method = 'POST'>
                        <input type = 'hidden' name = 'rid' value = '".$row['paper_review_ID']."'>
                        <button type = 'submit' class ='btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-edit'></span>
                        </button>
                    </form>
                </td>";

               $output.= "<td>
                    <form action = '4_delete_review.php' method = 'POST'>
                        <input type = 'hidden' name = 'rid' value = '".$row['paper_review_ID']."'>
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

 $page_query = "SELECT * FROM paper_review where Fac_ID='".$_SESSION['Fac_ID']."' ORDER BY paper_review_ID ASC";  
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
              <a href="count_your_review.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Count Paper Reviews</span></a> 

              <a href="1_add_paper_multiple_review.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Paper Reviewed</span></a> 
             
              <a href="export_to_excel_review_faculty.php" type="button" name="export" class="btn btn-success btn-sm"><span class="glyphicon ">Export to Excel</span></a> 

  <?php }else {?>
                <div class="text-left"><a href="researchForm.php"type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Paper Reviewed</span></a>

  <?php } ?>
  <br>
  <br>
    </div>
            </section>
    
</div>

<?php include_once('footer.php'); ?>  