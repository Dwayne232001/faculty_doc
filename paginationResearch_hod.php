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

$_SESSION['currentTab'] = "research";

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
 $query = "SELECT * FROM facultydetails INNER JOIN researchdetails ON facultydetails.Fac_ID=researchdetails.Fac_ID WHERE Dept='".$_SESSION['Dept']."' ORDER BY researchId DESC LIMIT $start_from,$record_per_page ";  
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
                  <h2 class="box-title"><b>Research Details</b></h2> 
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
                <th class='fixed-side'>Research Title</th>
                <th class='next-to-fixed-side'>Faculty Name</th>
                <th class='relative-side'>Date from (YYYY-MM-DD)</th>
                <th>Date to (YYYY-MM-DD)</th>
                <th class='relative-side'>Submitted to</th>
                <th>Principle Investigator</th>
                <th class='relative-side'>Co-Investigator</th>
                <th>Proposed Amount</th>
        
                <th class='relative-side'>Approved?</th>
                <th>Amount Sanctioned</th>
        
                <th class='relative-side'>Awards Won</th>
                <th>Last edited</th>
                <th class='relative-side'>Report</th>";
                if($_SESSION['type']!='cod' && $_SESSION['type']!='com'){
                    $output.="<th>Edit</th>
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
                <td class='fixed-side'>".$row['researchTitle']."</td>
                <td class='next-to-fixed-side'>".$row['F_NAME']."</td>
                <td class='relative-side'>".$row['fromDate']."</td>
                <td>".$row['toDate']."</td>
                <td class='relative-side'>".$row['submittedTo']."</td>
                <td>".$row['principleInvestigator']."</td>
                <td class='relative-side'>".$row['coInvestigator']."</td>
                <td>".$row['proposedAmount']."</td>
                <td class='relative-side'>".$row['radioApproval']."</td>
                <td>".$row['amountSanctioned']."</td>
                <td class='relative-side'>".$row['awardsWon']."</td>
                <td>".$row['currentTimestamp']."</td>";
                
                $_SESSION['researchId'] = $row['researchId'];
            
               if(($row['reportPath']) != "")
              {
                  if(($row['reportPath']) == 'NULL'){
                    $output.= "<td class='relative-side'>
                                  <form action = 'upload-report-research.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '".$row['researchId']."'>
                                      <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                          <span class='glyphicon glyphicon-upload'></span>
                                      </button>
                                  </form>
                              </td>";
                  }
              
                  else if(($row['reportPath']) == "not_applicable") 
                      $output.= "<td class='relative-side'>not applicable</td>";
                  else
                      $output.= "<td class='relative-side'> <a href = '".$row['reportPath']."' target='_blank'>View report</td>";
              }
              else
                $output.= "<td class='relative-side'>no status </td>";
              if($_SESSION['type']!='com' && $_SESSION['type']!='cod'){
                $output.= "<td>
                    <form action = 'researchEdit.php' method = 'POST'>
                        <input type = 'hidden' name = 'rid' value = '".$row['researchId']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-edit'></span>
                        </button>
                    </form>
                </td>";
              }
              if($_SESSION['type']!='com' && $_SESSION['type']!='cod'){  
                $output.= "<td class='relative-side'>
                    <form action = 'researchDeleteConfirm.php' method = 'POST'>
                        <input type = 'hidden' name = 'rid' value = '".$row['researchId']."'>
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

 $page_query ="SELECT * FROM facultydetails INNER JOIN researchdetails ON facultydetails.Fac_ID=researchdetails.Fac_ID WHERE Dept='".$_SESSION['Dept']."' ORDER BY researchId DESC";  

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
   
if($_SESSION['type'] == 'hod'){?>
              <div class="text-left"><a href="researchFormHOD.php"type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Research Details</span></a>
              <?php }?>
              <a href="researchAnalysisHOD.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Count Researches</span></a> 
              <a href="researchViewHOD-ExportToExcel.php" type="button" name="export" class="btn btn-success btn-sm"><span class="glyphicon ">Export to Excel</span></a> 

  <?php }else {
  
if($_SESSION['type'] == 'hod'){?>
                <div class="text-left"><a href="researchFormHOD.php"type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Research Details</span></a>

  <?php } }?>
  <br>
  <br>
    </div>
            </section>
    
</div>

<?php include_once('footer.php'); ?>  