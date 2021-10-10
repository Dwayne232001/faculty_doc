<?php
include("includes/view.php");
session_start();
ob_start();
if (!isset($_SESSION['loggedInUser'])) {
    //send them to login page
    header("location:index.php");
}
$_SESSION['currentTab'] = "sttp";

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
$query = "SELECT * FROM attended where fac_id='" . $_SESSION['Fac_ID'] . "' ORDER BY A_ID ASC LIMIT $start_from,$record_per_page ";
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
                    <h2 class="box-title"><b>STTP/WS/FDP/QIP/TR/S/IN Attended Form Details</b></h2>

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
                <th class= 'next-to-fixed-side'>Type</th>
                <th>Organized by</th>
                <th>Date from:<br><small>Y-M-D</small></th>
                <th>Date to:<br><small>Y-M-D</small></th>
                <th>Category</th>
                <th>Level</th>
                <th>No of Hours</th>
                <th>No of days</th>
                <th>No of Weeks</th>
                <th>Last Updated</th>
                <th>Certificate</th>
                <th>Report</th>
                <th>Edit</th>
                <th>Delete</th>
           </tr>  
 ";
                            $output .= "</thead>";
                            while ($row = mysqli_fetch_array($result)) {
                                $output .= " 
           <tr>  
                <td class='fixed-side'>" . $row['Act_title'] . "</td>
                <td class= 'next-to-fixed-side'>" . $row['Act_type'] . "</td>
                <td>" . $row['Organized_by'] . "</td>
                <td>" . $row['Date_from'] . "</td>
                <td>" . $row['Date_to'] . "</td>
                <td>" . $row['Category'] . "</td>
                <td>" . $row['Status_Of_Activity'] . "</td>
                <td>" . $row['Equivalent_Duration'] . "</td>
                <td>" . $row['noofdays'] . "</td>
                <td>" . $row['noofweeks'] . "</td>
                <td>" . $row['LastUpdated'] . "</td>";

                                $_SESSION['A_ID'] = $row['A_ID'];

                                //             if (($row['Permission_path']) != "") {
                                //                 if (($row['Permission_path']) == "NULL") {
                                //                     $output .= "<td>
                                //               <form action = 'upload-permission_attend.php' method = 'POST'>
                                //                   <input type = 'hidden' name = 'id' value = '" . $row['A_ID'] . "'>
                                //                   <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                //                       <span class='glyphicon glyphicon-upload'></span>
                                //                   </button>
                                //               </form>
                                // </td>";
                                //                 } else if (($row['Permission_path']) == "not_applicable")
                                //                     $output .= "<td>not applicable</td>";
                                //                 else
                                //                     $output .= "<td> <a href = '" . $row['Permission_path'] . "' target='_blank'>View Permission</td>";
                                //             } else
                                //                 $output .= "<td>no status </td>";

                                if (($row['Certificate_path']) != "") {
                                    if (($row['Certificate_path']) == 'NULL') {
                                        $output .= "<td>
                                  <form action = 'upload-certificate_attend.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '" . $row['A_ID'] . "'>
                                      <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                          <span class='glyphicon glyphicon-upload'></span>
                                      </button>
                                  </form>
                              </td>";
                                    } else if (($row['Certificate_path']) == "not_applicable")
                                        $output .= "<td>not applicable</td>";
                                    else
                                        $output .= "<td> <a href = '" . $row['Certificate_path'] . "' target='_blank'>View Certificate</td>";
                                } else
                                    $output .= "<td>no status </td>";

                                if (($row['Report_path']) != "") {
                                    if (($row['Report_path']) == 'NULL') {
                                        $output .= "<td>
                                  <form action = 'upload-report_attend.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '" . $row['A_ID'] . "'>
                                      <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                          <span class='glyphicon glyphicon-upload'></span>
                                      </button>
                                  </form>
                              </td>";
                                    } else if (($row['Report_path']) == "not_applicable")
                                        $output .= "<td>not applicable</td>";
                                    else
                                        $output .= "<td> <a href = '" . $row['Report_path'] . "' target='_blank'>View Report</td>";
                                } else
                                    $output .= "<td>no status </td>";
                                $output .= "<td>
                    <form action = '3_edit_attend.php' method = 'POST'>
                        <input type = 'hidden' name = 'rid' value = '" . $row['A_ID'] . "'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-edit'></span>
                        </button>
                    </form>
                </td>";

                                $output .= "<td>
                    <form action = '4_delete_attended.php' method = 'POST'>
                        <input type = 'hidden' name = 'rid' value = '" . $row['A_ID'] . "'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-trash'></span>
                        </button>
                    </form>
                </td>";
                                "</tr>";

                                //                 if ($row['ApprovalStatus'] === 'Approved') {
                                //                     $output .= "<t>
                                //     <form action = '3_edit_attend.php' method = 'POST'>
                                //         <input type = 'hidden' name = 'rid' value = '" . $row['A_ID'] . "'>
                                //         <button type = 'submit' class = 'btn btn-primary btn-sm' disabled=disabled>
                                //             <span class='glyphicon glyphicon-edit'></span>
                                //         </button>
                                //     </form>
                                // </td>";

                                //                     $output .= "<td>
                                //     <form action = '4_delete_attended.php' method = 'POST'>
                                //         <input type = 'hidden' name = 'rid' value = '" . $row['A_ID'] . "'>
                                //         <button type = 'submit' class = 'btn btn-primary btn-sm' disabled=disabled>
                                //             <span class='glyphicon glyphicon-trash'></span>
                                //         </button>
                                //     </form>
                                // </td>";
                                //                     "</tr>";
                                //                 } else {
                                //                     $output .= "<t>
                                //     <form action = '3_edit_attend.php' method = 'POST'>
                                //         <input type = 'hidden' name = 'rid' value = '" . $row['A_ID'] . "'>
                                //         <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                //             <span class='glyphicon glyphicon-edit'></span>
                                //         </button>
                                //     </form>
                                // </td>";

                                //                     $output .= "<td>
                                //     <form action = '4_delete_attended.php' method = 'POST'>
                                //         <input type = 'hidden' name = 'rid' value = '" . $row['A_ID'] . "'>
                                //         <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                //             <span class='glyphicon glyphicon-trash'></span>
                                //         </button>
                                //     </form>
                                // </td>";
                                //                     "</tr>";
                                //                 }

                                $output .= "</tr>";
                            }

                            $output .= '</table><br /><div align="center">';
                            ?>
                </div>
            </div>
        </div>

        <?php

        $page_query = "SELECT * FROM attended where Fac_ID='" . $_SESSION['Fac_ID'] . "' ORDER BY A_ID ASC";
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
                <a href="count_your_attend.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Count Attended Activities</span></a>

                <a href="1_add_paper_multiple_attend.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Attended Activity</span></a>

                <a href="export_to_excel_sttp_attend.php" type="button" name="export" class="btn btn-success btn-sm"><span class="glyphicon ">Export to Excel</span></a>

            <?php } else { ?>
                <div class="text-left"><a href="1_add_paper_multiple_attend.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Attended Activity</span></a>

                <?php } ?>
                <br>
                <br>
                </div>
                </section>

            </div>

            <?php include_once('footer.php'); ?>