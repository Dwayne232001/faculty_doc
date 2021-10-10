<?php
include("includes/view.php");
session_start();
ob_start();
if (!isset($_SESSION['loggedInUser'])) {
  //send them to login page
  header("location:index.php");
}
$_SESSION['currentTab'] = "iv";

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
$query = "SELECT * FROM iv_organized where f_id='" . $_SESSION['Fac_ID'] . "' ORDER BY id ASC LIMIT $start_from,$record_per_page ";
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
                <th>Industry</th>
                <th>City</th>
                <th>Resource Person</th>
                <th>Audience</th>
                <th>Staff</th>
                <th>No. of Participants</th>
                
                <th>Date from (YYYY-MM-DD)</th>
                <th>Date to (YYYY-MM-DD)</th>
                
  
                <th>Permission</th>
                <th>Certificate</th>
                <th>Report</th>
                <th>Attendance</th>

                <th>Edit</th>
                <th>Delete</th>
           </tr>  
 ";
              $output .= "</thead>";
              while ($row = mysqli_fetch_array($result)) {
                $output .= " 
           <tr>  
                <td>" . $row['ind'] . "</td>
                <td>" . $row['city'] . "</td>
                <td>" . $row['resource'] . "</td>
                <td>" . $row['t_audience'] . "</td>
                <td>" . $row['staff'] . "</td>
                <td>" . $row['part'] . "</td>
                
                <td>" . $row['t_from'] . "</td>
                <td>" . $row['t_to'] . "</td>";

                if (($row['permission']) != '') {
                  if (($row['permission']) == 'NULL') {
                    $output .= "<td class='relative-side'>
                                <form action = 'upload-permission_iv.php' method = 'POST'>
                                    <input type = 'hidden' name = 'id' value = '" . $row['id'] . "'>
                                    <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                        <span class='glyphicon glyphicon-upload'></span>
                                    </button>
                                </form>
                      </td>";
                  } else if (($row['permission']) == "not_applicable") {
                    $output .= "<td>not applicable</td>";
                  } else {
                    $output .= "<td> <a href = '" . $row['permission'] . "' target='_blank' >View permission</td>";
                  }
                } else {
                  $output .= "<td>no status </td>";
                }

                if (($row['certificate']) != "") {
                  if (($row['certificate']) == "NULL") {
                    $output .= "<td>
                           <form action = 'upload-certificate_iv.php' method = 'POST'>
                                <input type = 'hidden' name = 'id' value = '" . $row['id'] . "'>
                                    <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                         <span class='glyphicon glyphicon-upload'></span>
                                    </button>
                             </form>
                    </td>";
                  } else if (($row['certificate']) == "not_applicable")
                    $output .= "<td>not applicable</td>";
                  else
                    $output .= "<td> <a href = '" . $row['certificate'] . "' target='_blank'>View certificate</td>";
                } else
                  $output .= "<td>no status </td>";

                if (($row['report']) != "") {
                  if (($row['report']) == "NULL") {
                    $output .= "<td>
                                  <form action = 'upload-report_iv.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '" . $row['id'] . "'>
                                      <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                          <span class='glyphicon glyphicon-upload'></span>
                                      </button>
                                  </form>
                    </td>";
                  } else if (($row['report']) == "not_applicable")
                    $output .= "<td>not applicable</td>";
                  else
                    $output .= "<td> <a href = '" . $row['report'] . "' target='_blank'>View report</td>";
                } else
                  $output .= "<td>no status </td>";

                if (($row['attendance']) != '') {
                  if (($row['attendance']) == 'NULL') {
                    $output .= "<td>
                                <form action = 'upload-attendence_iv.php' method = 'POST'>
                                    <input type = 'hidden' name = 'id' value = '" . $row['id'] . "'>
                                    <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                        <span class='glyphicon glyphicon-upload'></span>
                                    </button>
                                </form>
                      </td>";
                  } else if (($row['attendance']) == "not_applicable") {
                    $output .= "<td>not applicable</td>";
                  } else {
                    $output .= "<td> <a href = '" . $row['attendance'] . "' target='_blank' >View attendance</td>";
                  }
                } else {
                  $output .= "<td>no status </td>";
                }

                $output .= "<td>
                  <form action = '3_edit_iv.php' method = 'POST'>
                    <input type = 'hidden' name = 'id' value = '" . $row['id'] . "'>
                    <button type = 'submit' class = 'btn btn-primary btn-sm'>
                      <span class='glyphicon glyphicon-edit'></span>
                    </button>
                  </form>
                </td>";

                $output .= "<td>
                    <form action = '4_delete_iv.php' method = 'POST'>
                      <input type = 'hidden' name = 'id' value = '" . $row['id'] . "'>
                      <button type = 'submit' class = 'btn btn-primary btn-sm' >
                        <span class='glyphicon glyphicon-trash'></span>
                      </button>
                    </form>
                  </td>";

                $output .= "</tr>";
              }

              $output .= '</table><br /><div align="center">';
              ?>
        </div>
      </div>
    </div>

    <?php

    $page_query = "SELECT * FROM iv_organized where f_id='" . $_SESSION['Fac_ID'] . "' ORDER BY id ASC";
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
      <div class="text-left"><a href="industrialvisit.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add IV Details</span></a>
        <a href="ivchart_fac.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Count Industrial Visits</span></a>
        <a href="export_to_excel_iv.php" type="button" name="export" class="btn btn-success btn-sm"><span class="glyphicon ">Export to Excel</span></a>

      <?php } else { ?>
        <div class="text-left"><a href="Industrialvisit.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add IV Details</span></a>

        <?php } ?>
        <br>
        <br>
        </div>
        </section>

      </div>

      <?php include_once('footer.php'); ?>