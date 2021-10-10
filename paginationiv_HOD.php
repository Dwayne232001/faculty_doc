

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
$_SESSION['currentTab'] = "iv";

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
 $query = "SELECT * FROM facultydetails INNER JOIN iv_organized ON facultydetails.Fac_ID=iv_organized.f_id WHERE facultydetails.Dept='".$_SESSION['Dept']."' ORDER BY id DESC LIMIT $start_from,$record_per_page ";  
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
          <h2 class="box-title"><b>Industrial Visit Details</b></h2> 
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
                <th class='fixed-side'>Industry</th>
                <th class='next-to-fixed-side'>Faculty Name</th> 
                <th class='relative-side'>Purpose</th>
                <th>City</th>
                <th class='relative-side'>Audience</th>
                <th>No. of Participants</th>
                <th class='relative-side'>Staff</th>
                
                <th>IV type</th>
                <th class='relative-side'>Details</th>
                <th>Date from (YYYY-MM-DD)</th>
                <th class='relative-side'>Date to (YYYY-MM-DD)</th>
  
                <th>Permmission</th>
                <th class='relative-side'>Certificate</th>
                <th>Report</th>
                <th class='relative-side'>Attendance</th>
                
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
                <td class='fixed-side'>".$row['ind']."</td>
                <td class='next-to-fixed-side'>".$row['F_NAME']."</td>
                <td class='relative-side'>".$row['purpose']."</td>
                <td>".$row['city']."</td>
                
                <td class='relative-side'>".$row['t_audience']."</td>
                <td>".$row['part']."</td>
                <td class='relative-side'>".$row['staff']."</td>
                
                <td >".$row['ivtype']."</td>
                <td class='relative-side'>".$row['details']."</td>
                <td>".$row['t_from']."</td>
                <td class='relative-side'>".$row['t_to']."</td>";
                
              if(($row['permission']) != '')
              {
                  if(($row['permission']) == 'NULL')
                    {
                      $output.= "<td>
                                <form action = 'upload-permission_iv.php' method = 'POST'>
                                    <input type = 'hidden' name = 'id' value = '".$row['id']."'>
                                    <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                        <span class='glyphicon glyphicon-upload'></span>
                                    </button>
                                </form>
                      </td>";
                    }
                  else if(($row['permission']) == "not_applicable") 
                    {
                      $output.= "<td>not applicable</td>";
                    }
                  else
                    {
                      $output.= "<td> <a href = '".$row['permission']."' target='_blank' >View permission</td>";
                    }
              }
              else{
                  $output.= "<td>no status </td>";
              }
              
              if(($row['certificate']) != "")
              {
                  if(($row['certificate']) == "NULL")
                  {
                    $output.= "<td class='relative-side'>
                           <form action = 'upload-certificate_iv.php' method = 'POST'>
                                <input type = 'hidden' name = 'id' value = '".$row['id']."'>
                                    <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                         <span class='glyphicon glyphicon-upload'></span>
                                    </button>
                             </form>
                    </td>";
                  }
                  else if(($row['certificate']) == "not_applicable") 
                    $output.= "<td class='relative-side'>not applicable</td>";
                  else
                    $output.= "<td class='relative-side'> <a href = '".$row['certificate']."' target='_blank'>View certificate</td>";
              }
              else
                  $output.= "<td class='relative-side'>no status </td>";
              
               if(($row['report']) != "")
              {
                  if(($row['report']) == "NULL"){
                    $output.= "<td>
                                  <form action = 'upload-report_iv.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '".$row['id']."'>
                                      <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                          <span class='glyphicon glyphicon-upload'></span>
                                      </button>
                                  </form>
                    </td>";
                  }
              
                  else if(($row['report']) == "not_applicable") 
                      $output.= "<td>not applicable</td>";
                  else
                      $output.= "<td> <a href = '".$row['report']."' target='_blank'>View report</td>";
              }
              else
                $output.= "<td>no status </td>";

              if(($row['attendance']) != '')
              {
                  if(($row['attendance']) == 'NULL')
                    {
                      $output.= "<td class='relative-side'>
                                <form action = 'upload-attendence_iv.php' method = 'POST'>
                                    <input type = 'hidden' name = 'id' value = '".$row['id']."'>
                                    <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                        <span class='glyphicon glyphicon-upload'></span>
                                    </button>
                                </form>
                      </td>";
                    }
                  else if(($row['attendance']) == "not_applicable") 
                    {
                      $output.= "<td class='relative-side'>not applicable</td>";
                    }
                  else
                    {
                      $output.= "<td class='relative-side'> <a href = '".$row['attendance']."' target='_blank' >View attendance</td>";
                    }
              }
              else{
                  $output.= "<td class='relative-side'>no status </td>";
              }
              if($_SESSION['type']!='cod' && $_SESSION['type']!='com'){
                $output.= "<td>
                  <form action = '3_edit_iv.php' method = 'POST'>
                    <input type = 'hidden' name = 'id' value = '".$row['id']."'>
                    <button type = 'submit' class = 'btn btn-primary btn-sm'>
                      <span class='glyphicon glyphicon-edit'></span>
                    </button>
                  </form>
                </td>";

                $output.= "<td class='relative-side'>
                    <form action = '4_delete_iv.php' method = 'POST'>
                      <input type = 'hidden' name = 'id' value = '".$row['id']."'>
                      <button type = 'submit' class = 'btn btn-primary btn-sm' >
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

 $page_query =  "SELECT * FROM iv_organized where f_id='".$_SESSION['Fac_ID']."' ORDER BY id ASC";    
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
              <div class="text-left"><a href="industrialvisit_HOD.php"type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add IV Details</span></a>
              <?php }?>
              <a href="count_all_iv.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Count Industrial Visits</span></a> 
              <a href="export_to_excel_hod_iv.php" type="button" name="export" class="btn btn-success btn-sm"><span class="glyphicon ">Export to Excel</span></a> 

  <?php }else {
  
if($_SESSION['type'] == 'hod'){?>
                <div class="text-left"><a href="Industrialvisit.php"type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add IV Details</span></a>

  <?php } }?>
  <br>
  <br>
    </div>
            </section>
    
</div>

<?php include_once('footer.php'); ?>  