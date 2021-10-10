 <?php
 include("includes/view.php");
 include("includes/connection.php");
session_start();
ob_start();
if(!isset($_SESSION['loggedInUser'])){
  //send them to login page
  header("location:index.php");
}

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



$_SESSION['currentTab'] = "paper";

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
 $query = "SELECT * from faculty inner join facultydetails on faculty.Fac_ID = facultydetails.Fac_ID WHERE facultydetails.Dept='".$_SESSION['Dept']."' ORDER BY P_ID DESC LIMIT $start_from,$record_per_page ";  
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
                    <h2 class="box-title"><b>Paper Publication/Presentation Details</b></h2> 
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
                <th class='next-to-fixed-side'>Faculty</th>
                <th class='relative-side'>Type</th>
                <th>N/I</th>
                <th class='relative-side'>Conference/Journal Name</th>
        
                <th>Category</th>
                <th class='relative-side'>Co-authors</th>
                <th>Date from (YYYY-MM-DD)</th>
                <th class='relative-side'>Date to (YYYY-MM-DD)</th>
                <th >Index (Scopus/Sci/Both)</th>
                
                <th class='relative-side'>Volume</th>
                <th>Index (Scopus/Sci/Both) applicable</th>
                <th class='relative-side'>Location</th>
        
                <th>H-Index</th>
                <th class='relative-side'>Citations</th>
        
                <th>FDC Status</th>
                <th class='relative-side'>Presentation Status</th>
                <th>Awards</th>
                <th class='relative-side'>Link of Publication</th>
                <th>Presented By</th>
                <th class='relative-side'>FDC Approval Status</th>
                <th>Added on</th> 
                <th class='relative-side'>Updated on</th>

      
                <th>Paper</th>
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
                <td class='fixed-side'>".$row['Paper_title']."</td>
                <td class='next-to-fixed-side'>".$row['F_NAME']."</td>
                <td class='relative-side'>".$row['Paper_type']."</td>
                <td>".$row['Paper_N_I']."</td>
                <td class='relative-side'>".$row['conf_journal_name']."</td>
                <td>".$row['paper_category']."</td>
                <td class='relative-side'>".$row['Paper_co_authors']."</td>
                <td>".$row['Date_from']."</td>
                <td class='relative-side'>".$row['Date_to']."</td>
                <td >".$row['scopus']."</td>
                <td class='relative-side'>".$row['volume']."</td>
                <td>".$row['scopusindex']."</td>
                <td class='relative-side'>".$row['Location']."</td>
                
                <td>".$row['h_index']."</td>
                <td class='relative-side'>".$row['citations']."</td>
        
                <td>".$row['FDC_Y_N']."</td>
                <td class='relative-side'>".$row['presentation_status']."</td>
                <td>".$row['Paper_awards']."</td>
                <td class='relative-side'>".$row['Link_publication']."</td>
                <td>".$row['presented_by']."</td>
                <td class='relative-side'>".$row['FDC_approved_disapproved']."</td>
                <td>".$row['Adate']."</td>
                <td class='relative-side'>".$row['Udate']."</td>";
                
              if(($row['paper_path']) != '')
              {
                  if(($row['paper_path']) == 'NULL')
                    {
                      $output.= "<td>
                                <form action = 'upload-paper.php' method = 'POST'>
                                    <input type = 'hidden' name = 'id' value = '".$row['P_ID']."'>
                                    <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                        <span class='glyphicon glyphicon-upload'></span>
                                    </button>
                                </form>
                      </td>";
                    }
                  else if(($row['paper_path']) == "not_applicable") 
                    {
                      $output.= "<td>not applicable</td>";
                    }
                  else
                    {
                      $output.= "<td> <a href = '".$row['paper_path']."' target='_blank' >View paper</td>";
                    }
              }
              else{
                  $output.= "<td>no status </td>";
                }
              
              if(($row['certificate_path']) != "")
              {
                  if(($row['certificate_path']) == "NULL")
                  {
                    $output.= "<td class='relative-side'>
                           <form action = 'upload-certificate.php' method = 'POST'>
                                <input type = 'hidden' name = 'id' value = '".$row['P_ID']."'>
                                    <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                         <span class='glyphicon glyphicon-upload'></span>
                                    </button>
                             </form>
                    </td>";
                  }
                  else if(($row['certificate_path']) == "not_applicable") 
                    $output.= "<td class='relative-side'>not applicable</td>";
                  else
                    $output.= "<td class='relative-side'> <a href = '".$row['certificate_path']."' target='_blank'>View certificate</td>";
              }
              else
                  $output.= "<td class='relative-side'>no status </td>";
              
               if(($row['report_path']) != "")
              {
                  if(($row['report_path']) == "NULL"){
                    $output.= "<td>
                                  <form action = 'upload-report.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '".$row['P_ID']."'>
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
              
              $FDC_approved_disapproved= $row['FDC_approved_disapproved'];

             if(($FDC_approved_disapproved== 'disapproved' || $FDC_approved_disapproved== 'not approved') && ($_SESSION['type']!='com' && $_SESSION['type']!='cod'))
              {
                $output.= "<td class='relative-side'>
                  <form action = '3_edit_hod.php' method = 'POST'>
                    <input type = 'hidden' name = 'id' value = '".$row['P_ID']."'>
                    <button type = 'submit' class = 'btn btn-primary btn-sm'>
                      <span class='glyphicon glyphicon-edit'></span>
                    </button>
                  </form>
                </td>";
              }
              else if(($FDC_approved_disapproved== 'approved')&& ($_SESSION['type']!='com' && $_SESSION['type']!='cod'))
              {
                $output.= "<td class='relative-side'>
                  <form action = '3_edit_hod.php' method = 'POST'>
                    <input type = 'hidden' name = 'id' value = '".$row['P_ID']."'>
                    <button type = 'submit' class = 'btn btn-primary btn-sm' disabled>
                      <span class='glyphicon glyphicon-edit'></span>
                    </button>
                  </form>
                </td>";
              }

              if(($FDC_approved_disapproved== 'disapproved' || $FDC_approved_disapproved== 'not approved') && ($_SESSION['type']!='com' && $_SESSION['type']!='cod') )
              {
                $output.= "<td>
                    <form action = '4_delete.php' method = 'POST'>
                      <input type = 'hidden' name = 'id' value = '".$row['P_ID']."'>
                      <button type = 'submit' class = 'btn btn-primary btn-sm' >
                        <span class='glyphicon glyphicon-trash'></span>
                      </button>
                    </form>
                  </td>";
              }
              else if(($FDC_approved_disapproved== 'approved')&& ($_SESSION['type']!='com' && $_SESSION['type']!='cod'))
              {
                  $output.= "<td>
                    <form action = '4_delete.php' method = 'POST'>
                      <input type = 'hidden' name = 'id' value = '".$row['P_ID']."'>
                      <button type = 'submit' class = 'btn btn-primary btn-sm' disabled>
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

 $page_query = "SELECT * from faculty inner join facultydetails on faculty.Fac_ID = facultydetails.Fac_ID WHERE facultydetails.Dept='".$_SESSION['Dept']."' ORDER BY P_ID ASC";  
 $page_result = mysqli_query($conn, $page_query);  
 $total_records = mysqli_num_rows($page_result);  
 $total_pages = ceil($total_records/$record_per_page);  
 $output.= "</div></div><br/>";
 $output .= "<div align='center'>";
 for($i=1; $i<=$total_pages; $i++)  
 {
      $output .= "<span class='pagination_link pagination flex-wrap' style='cursor:pointer; padding:6px; border:1px solid #ccc ; border-radius : 3px;font-size:16px;' id='".$i."'>".$i."</span>";  
 }  
 $output.= "</div><br>";
 echo $output;  
 ?> 
 <br>
   <?php if ($_SESSION['rows'] > 0) {
    if($_SESSION['type'] == 'hod') {?>
              <div class="text-left"><a href="1_add_paper_multiple_hod.php"type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Paper</span></a>
    <?php } ?>
              <a href="count_all.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Count Publications</span></a> 
              <a href="export_to_excel_publication_hod.php" type="button" name="export" class="btn btn-success btn-sm"><span class="glyphicon ">Export to Excel</span></a> 
  <?php }else {
    if($_SESSION['type'] == 'hod') { ?>
                <div class="text-left"><a href="1_add_paper_multiple_hod.php"type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Paper</span></a>

  <?php } }?>
  <br>
  <br>
    </div>
            </section>
    
</div>

<?php include_once('footer.php'); ?>  