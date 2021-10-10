<?php
 include("includes/view.php");
session_start();
ob_start();
if(!isset($_SESSION['loggedInUser'])){
    //send them to login page
    header("location:index.php");
}
$_SESSION['currentTab'] = "books";

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
 $query = "SELECT * FROM books_published where Fac_ID='".$_SESSION['Fac_ID']."' ORDER BY published_id ASC LIMIT $start_from,$record_per_page ";  
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
                    <h2 class="box-title"><b>Books/Chapter Published Details</b></h2> 
                    
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
                <th>Author</th>
                <th>Co Author (First Name)</th>
                <th>Co Author (Last Name)</th>
                <th>Book Type</th>
                <th>Title</th>
                <th>Edition (If Any)</th>
                <th>Name of Publisher</th>
                <th>Chapter Number (If Any)</th>
                <th>ISSN/eISSN/ISBN Number</th>
                <th>Date</th>
                <th>Book/Chapter URL</th>  
                <th>Edit</th>
                <th>Delete</th>         
           </tr>  
 "; 
 $output.= "</thead>"; 
 while($row = mysqli_fetch_array($result))  
 {  
      $output .= " 
           <tr>  
                <td>".$row['author']."</td>
                <td>".$row['first_name']."</td>
                <td>".$row['last_name']."</td>
                <td>".$row['book_type']."</td>
                <td>".$row['title']."</td>
                <td>".$row['edition']."</td>
                <td>".$row['publisher_name']."</td>
                <td>".$row['chapter_no']."</td>
                <td>".$row['issn_no']."</td>
                <td>".$row['date']."</td>
                <td>".$row['url']."</td>";
                
            //   if(($row['paper_path']) != '')
            //   {
            //       if(($row['paper_path']) == 'NULL')
            //         {
            //           $output.= "<td class='relative-side'>
            //                     <form action = 'upload-paper.php' method = 'POST'>
            //                         <input type = 'hidden' name = 'id' value = '".$row['P_ID']."'>
            //                         <button type = 'submit' class = 'btn btn-primary btn-sm'>
            //                             <span class='glyphicon glyphicon-upload'></span>
            //                         </button>
            //                     </form>
            //           </td>";
            //         }
            //       else if(($row['paper_path']) == "not_applicable") 
            //         {
            //           $output.= "<td class='relative-side'>not applicable</td>";
            //         }
            //       else
            //         {
            //           $output.= "<td class='relative-side'> <a href = '".$row['paper_path']."' target='_blank' >View paper</td>";
            //         }
            //   }
            //   else{
            //       $output.= "<td class='relative-side'>no status </td>";
            //     }
              
            //   if(($row['certificate_path']) != "")
            //   {
            //       if(($row['certificate_path']) == "NULL")
            //       {
            //         $output.= "<td>
            //                <form action = 'upload-certificate.php' method = 'POST'>
            //                     <input type = 'hidden' name = 'id' value = '".$row['P_ID']."'>
            //                         <button type = 'submit' class = 'btn btn-primary btn-sm'>
            //                              <span class='glyphicon glyphicon-upload'></span>
            //                         </button>
            //                  </form>
            //         </td>";
            //       }
            //       else if(($row['certificate_path']) == "not_applicable") 
            //         $output.= "<td>not applicable</td>";
            //       else
            //         $output.= "<td> <a href = '".$row['certificate_path']."' target='_blank'>View certificate</td>";
            //   }
            //   else
            //       $output.= "<td>no status </td>";
              
            //    if(($row['report_path']) != "")
            //   {
            //       if(($row['report_path']) == "NULL"){
            //         $output.= "<td class='relative-side'>
            //                       <form action = 'upload-report.php' method = 'POST'>
            //                           <input type = 'hidden' name = 'id' value = '".$row['P_ID']."'>
            //                           <button type = 'submit' class = 'btn btn-primary btn-sm'>
            //                               <span class='glyphicon glyphicon-upload'></span>
            //                           </button>
            //                       </form>
            //         </td>";
            //       }
              
            //       else if(($row['report_path']) == "not_applicable") 
            //           $output.= "<td class='relative-side'>not applicable</td>";
            //       else
            //           $output.= "<td class='relative-side'> <a href = '".$row['report_path']."' target='_blank'>View report</td>";
            //   }
            //   else
            //     $output.= "<td class='relative-side'>no status </td>";
              
            //   $FDC_approved_disapproved= $row['FDC_approved_disapproved'];

            //  if($FDC_approved_disapproved== 'disapproved' || $FDC_approved_disapproved== 'not approved')
            //   {
            //     $output.= "<td>
            //       <form action = '3_edit_hod.php' method = 'POST'>
            //         <input type = 'hidden' name = 'id' value = '".$row['P_ID']."'>
            //         <button type = 'submit' class = 'btn btn-primary btn-sm'>
            //           <span class='glyphicon glyphicon-edit'></span>
            //         </button>
            //       </form>
            //     </td>";
            //   }
            //   else if($FDC_approved_disapproved== 'approved')
            //   {
            //     $output.= "<td>
            //       <form action = '3_edit_hod.php' method = 'POST'>
            //         <input type = 'hidden' name = 'id' value = '".$row['P_ID']."'>
            //         <button type = 'submit' class = 'btn btn-primary btn-sm' disabled>
            //           <span class='glyphicon glyphicon-edit'></span>
            //         </button>
            //       </form>
            //     </td>";
            //   }

            //   if($FDC_approved_disapproved== 'disapproved' || $FDC_approved_disapproved== 'not approved')
            //   {
            //     $output.= "<td class='relative-side'>
            //         <form action = '4_delete.php' method = 'POST'>
            //           <input type = 'hidden' name = 'id' value = '".$row['P_ID']."'>
            //           <button type = 'submit' class = 'btn btn-primary btn-sm' >
            //             <span class='glyphicon glyphicon-trash'></span>
            //           </button>
            //         </form>
            //       </td>";
            //   }
            //   else if($FDC_approved_disapproved== 'approved')
            //   {
            //       $output.= "<td class='relative-side'>
            //         <form action = '4_delete.php' method = 'POST'>
            //           <input type = 'hidden' name = 'id' value = '".$row['P_ID']."'>
            //           <button type = 'submit' class = 'btn btn-primary btn-sm' disabled>
            //             <span class='glyphicon glyphicon-trash'></span>
            //           </button>
            //         </form>
            //       </td>";
            //   }
            $output.= "<td>
                    <form action = 'edit_published.php' method = 'POST'>
                        <input type = 'hidden' name = 'rid' value = '".$row['published_id']."'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-edit'></span>
                        </button>
                    </form>
                </td>";

            $output.= "<td>
                  <form action = '4_delete_invited_lec.php' method = 'POST'>
                    <input type = 'hidden' name = 'rid' value = '".$row['published_id']."'>
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

 $page_query = "SELECT * FROM books_published where Fac_ID='".$_SESSION['Fac_ID']."' ORDER BY published_id ASC";  
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
   <?php if ($_SESSION['rows'] > 0) {?>
              <div class="text-left"><a href="1_add_paper_multiple.php"type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Paper</span></a>
              <a href="count_your.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Count Publications</span></a> 
              <a href="export_to_excel_publication_faculty.php" type="button" name="export" class="btn btn-success btn-sm"><span class="glyphicon ">Export to Excel</span></a> 

  <?php }else {?>
                <div class="text-left"><a href="1_add_paper_multiple.php"type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Paper</span></a>

  <?php } ?>
  <br>
  <br>
    </div>
            </section>
    
</div>

<?php include_once('footer.php'); ?>  