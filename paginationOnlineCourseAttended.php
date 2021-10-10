<?php
include("includes/view.php");
session_start();
ob_start();
if (!isset($_SESSION['loggedInUser'])) {
  //send them to login page
  header("location:index.php");
}
$_SESSION['currentTab'] = "Online";

if ($_SESSION['type'] != 'faculty') {
  header("location:index.php");
}

//connect to database
include("includes/connection.php");
$fid = $_SESSION['Fac_ID'];
$record_per_page = 5;
$page = '';
$output = '';
if (isset($_POST["page"])) {
  $page = $_POST["page"];
} else {
  $page = 1;
}
$start_from = ($page - 1) * $record_per_page;
$query = "SELECT * FROM online_course_attended where Fac_ID='" . $_SESSION['Fac_ID'] . "' ORDER BY OC_A_ID ASC LIMIT $start_from,$record_per_page ";
$result = mysqli_query($conn, $query);
$_SESSION['rows'] = mysqli_num_rows($result);
?>
<div class="row">
  <div class="col-xs-12">
    <?php if (!isset($_GET['alert'])) { ?>
      <br />
    <?php } ?>
    <div class="box box-primary">
      <div class="box-header with-border">
        <div class="icon">
          <i style="font-size:18px" class="fa fa-table"></i>
          <h2 class="box-title"><b>Online Course Completed Details</b></h2>
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
                <th class='fixed-side'>Name</th>
                <th class='next-to-fixed-side'>Duration From (y-m-d)</th>
                <th>Duration To (y-m-d)</th>
                <th>Organised By</th>
                <th>Certificate/Audit</th>
                <th>Certificate</th>
                <th>Edit</th>
                <th>Delete</th>
           </tr>  
 ";
//  <th>Duration</th>
// <th>Updated on</th>

              $output .= "</thead>";
              while ($row = mysqli_fetch_array($result)) {
                $output .= " 
           <tr>  
                <td class='fixed-side'>" . $row['Course_Name'] . "</td>
                <td class= 'next-to-fixed-side'>" . $row['Date_From'] . "</td>
                <td>" . $row['Date_To'] . "</td>
                <td>" . $row['Organised_by'] . "</td>
                <td>" . $row['credit_audit'] . "</td>";
                // <td>" . $row['noofweeks'] . "</td>
                // <td>" . $row['updated_at'] . "</td>
                
                $_SESSION['OC_A_ID'] = $row['OC_A_ID'];

                if (($row['certificate_path']) != "") {
                  if (($row['certificate_path']) == 'NULL') {
                    $output .= "<td>
                                  <form action = 'upload-certificate-attended.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '" . $row['OC_A_ID'] . "'>
                                      <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                          <span class='glyphicon glyphicon-upload'></span>
                                      </button>
                                  </form>
                              </td>";
                  } else if (($row['certificate_path']) == "not_applicable")
                    $output .= "<td>not applicable</td>";
                  else
                    $output .= "<td> <a href = '" . $row['certificate_path'] . "' target='_blank'>View Certificate</td>";
                } else
                  $output .= "<td>no status </td>";

                // if (($row['report_path']) != "") {
                //   if (($row['report_path']) == 'NULL') {
                //     $output .= "<td class='relative-side'>
                //                   <form action = 'upload-report-attended.php' method = 'POST'>
                //                       <input type = 'hidden' name = 'id' value = '" . $row['OC_A_ID'] . "'>
                //                       <button type = 'submit' class = 'btn btn-primary btn-sm'>
                //                           <span class='glyphicon glyphicon-upload'></span>
                //                       </button>
                //                   </form>
                //               </td>";
                //   } else if (($row['report_path']) == "not_applicable")
                //     $output .= "<td class='relative-side'>not applicable</td>";
                //   else
                //     $output .= "<td class='relative-side'> <a href = '" . $row['report_path'] . "' target='_blank'>View Report</td>";
                // } else
                //   $output .= "<td class='relative-side'>no status </td>";

                $output .= "<td class='relative-side'>
                    <form action = '3_edit_online_attended.php' method = 'POST'>
                        <input type = 'hidden' name = 'rid' value = '" . $row['OC_A_ID'] . "'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-edit'></span>
                        </button>
                    </form>
                </td>";

                $output .= "<td>
                    <form action = '4_delete_online_attended.php' method = 'POST'>
                        <input type = 'hidden' name = 'rid' value = '" . $row['OC_A_ID'] . "'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-trash'></span>
                        </button>
                    </form>
                </td>";
                "</tr>";
              }

              $output .= "</tr>";


              $output .= '</table><br /><div align="center">';
              ?>
        </div>
      </div>
    </div>

    <?php

    $page_query = "SELECT * FROM online_course_attended where Fac_ID='" . $_SESSION['Fac_ID'] . "' ORDER BY OC_A_ID ASC";
    $page_result = mysqli_query($conn, $page_query);
    $total_records = mysqli_num_rows($page_result);
    $total_pages = ceil($total_records / $record_per_page);
    $output .= "</div></div><br/>";
    $output .= "<div align='center'>";
    for ($i = 1; $i <= $total_pages; $i++) {
      $output .= "<span class='pagination_link pagination flex-wrap' style='cursor:pointer; padding:6px; border:1px solid #ccc ; border-radius : 3px;font-size:16px;' id='" . $i . "'>" . $i . "</span>";
    }
    $output .= "</div><br>";
    echo $output;
    ?>
    <br>
    <?php if ($_SESSION['rows'] > 0) { ?>
      <div>
        <a href="count_your_online.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Count Courses Attended</span></a>

        <a href="1_add_course_attended.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Course Attended</span></a>

        <a href="ExportToExcel_online.php" type="button" name="export" class="btn btn-success btn-sm"><span class="glyphicon ">Export to Excel</span></a>

      <?php } else { ?>
        <div class="text-left">
          <a href="1_add_course_attended.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Course Attended</span></a>

        <?php } ?>
        <br>
        <br>
        </div>
        </section>

      </div>

      <?php include_once('footer.php'); ?>