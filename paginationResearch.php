<?php
include("includes/view.php");
session_start();
ob_start();
if (!isset($_SESSION['loggedInUser'])) {
  //send them to login page
  header("location:index.php");
}
$_SESSION['currentTab'] = "research";

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
$query = "SELECT * FROM researchdetails where Fac_ID='" . $_SESSION['Fac_ID'] . "' ORDER BY research_Id ASC LIMIT $start_from,$record_per_page ";
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
          <h2 class="box-title"><b>Research Details</b></h2>
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
                <th>Research Title</th>
                <th>Date from (YYYY-MM-DD)</th>
                <th>Date to (YYYY-MM-DD)</th>
                <th>Principle Investigator</th>
                <th>Submitted to</th>
                <th>Proposed Amount</th>
                <th>Co-Investigator</th>
                <th>Tenure</th>
                <th>Amount Sanctioned</th>
                <th>Area</th>
                <th>Last edited</th>
                <th>Report</th>
                <th>Edit</th>
                <th>Delete</th>
           </tr>  
 ";
              $output .= "</thead>";
              while ($row = mysqli_fetch_array($result)) {
                $output .= " 
           <tr>  
                <td>" . $row['researchTitle'] . "</td>
                <td>" . $row['fromDate'] . "</td>
                <td>" . $row['toDate'] . "</td>
                <td>" . $row['principleInvestigator'] . "</td>
                <td>" . $row['submittedTo'] . "</td>
                
                
                <td>" . $row['proposedAmount'] . "</td>
                <td>" . $row['coInvestigator'] . "</td>
                <td>" . $row['tenure'] . "</td>
                <td>" . $row['amountSanctioned'] . "</td>
                <td>" . $row['area'] . "</td>
                <td>" . $row['currentTimestamp'] . "</td>";

                $_SESSION['research_Id'] = $row['research_Id'];

                if (($row['reportPath']) != "") {
                  if (($row['reportPath']) == 'NULL') {
                    $output .= "<td>
                                  <form action = 'upload-report-research.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '" . $row['research_Id'] . "'>
                                      <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                          <span class='glyphicon glyphicon-upload'></span>
                                      </button>
                                  </form>
                              </td>";
                  } else if (($row['reportPath']) == "not_applicable")
                    $output .= "<td>not applicable</td>";
                  else
                    $output .= "<td> <a href = '" . $row['reportPath'] . "' target='_blank'>View report</td>";
                } else
                  $output .= "<td>no status </td>";

                $output .= "<td>
                    <form action = 'researchEdit.php' method = 'POST'>
                        <input type = 'hidden' name ='rid' value = '" . $row['research_Id'] . "'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-edit'></span>
                        </button>
                    </form>
                </td>";

                $output .= "<td>
                    <form action = 'researchDeleteConfirm.php' method = 'POST'>
                        <input type = 'hidden' name ='rid' value = '" . $row['research_Id'] . "'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-trash'></span>
                        </button>
                    </form>
                </td>";
                "</tr>";

                $output .= "</tr>";
              }

              $output .= '</table><br /><div align="center">';
              ?>
        </div>
      </div>
    </div>

    <?php

    $page_query = "SELECT * FROM researchdetails where Fac_ID='" . $_SESSION['Fac_ID'] . "' ORDER BY research_Id ASC";
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
      <div class="text-left"><a href="researchForm.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Research Details</span></a>
        <a href="researchAnalysis.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Count Researches</span></a>
        <a href="researchView-ExportToExcel.php" type="button" name="export" class="btn btn-success btn-sm"><span class="glyphicon ">Export to Excel</span></a>

      <?php } else { ?>
        <div class="text-left"><a href="researchForm.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Research Details</span></a>

        <?php } ?>
        <br>
        <br>
        </div>
        </section>

      </div>

      <?php include_once('footer.php'); ?>