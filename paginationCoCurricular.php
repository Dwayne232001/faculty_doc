<?php
include("includes/view.php");
session_start();
ob_start();
if (!isset($_SESSION['loggedInUser'])) {
    //send them to login page
    header("location:index.php");
}
$_SESSION['currentTab'] = "co";

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
$query = "SELECT * FROM co_curricular where fac_id='" . $_SESSION['Fac_ID'] . "' ORDER BY co_curricular_ID ASC LIMIT $start_from,$record_per_page ";
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
                    <h2 class="box-title"><b>Co-Curricular Activity Details</b></h2>
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
                <th>Activity Name</th>
                <th>Organized By</th>
                <th class='relative-side'>Details</th>
                <th>Date from</th>
                <th class='relative-side'>Date to</th>
                <th>Last edited</th>
                <th class='relative-side'>Event</th>
                <th>Level</th>
                <th class='relative-side'>Certificate</th>
                <th>Edit</th>
                <th class='relative-side'>Delete</th>
           </tr>  
 ";
                            $output .= "</thead>";
                            while ($row = mysqli_fetch_array($result)) {
                                $output .= " 
           <tr>  
                <td>" . $row['activity_name'] . "</td>
                <td>" . $row['organized_by'] . "</td>
                <td class='relative-side'>" . $row['purpose_of_activity'] . "</td>
                <td>" . $row['Date_from'] . "</td>
                <td class='relative-side'>" . $row['Date_to'] . "</td>
                <td>" . $row['currentTimestamp'] . "</td>
                <td class='relative-side'>" . $row['event'] . "</td>
                <td>" . $row['level'] . "</td>";


                                $_SESSION['co_curricular_ID'] = $row['co_curricular_ID'];

                                //   if(($row['permission_path']) != "")
                                //   {
                                //       if(($row['permission_path']) == "NULL"){
                                //         $output.= "<td class='relative-side'> 
                                //                       <form action = 'upload-permission_cocurricular.php' method = 'POST'>
                                //                           <input type = 'hidden' name = 'id' value = '".$row['co_curricular_ID']."'>
                                //                           <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                //                               <span class='glyphicon glyphicon-upload'></span>
                                //                           </button>
                                //                       </form>
                                //         </td>";
                                //       }

                                //       else if(($row['permission_path']) == "not_applicable") 
                                //           $output.= "<td class='relative-side'>not applicable</td>";
                                //       else
                                //           $output.= "<td class='relative-side'> <a href = '".$row['permission_path']."' target='_blank'>View Permission</td>";
                                //   }
                                //   else
                                //     $output.= "<td class='relative-side'>no status </td>";

                                if (($row['certificate_path']) != "") {
                                    if (($row['certificate_path']) == 'NULL') {
                                        $output .= "<td class='relative-side'>
                                  <form action = 'upload-certificate_cocurricular.php' method = 'POST'>
                                      <input type = 'hidden' name = 'id' value = '" . $row['co_curricular_ID'] . "'>
                                      <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                          <span class='glyphicon glyphicon-upload'></span>
                                      </button>
                                  </form>
                              </td>";
                                    } else if (($row['certificate_path']) == "not_applicable")
                                        $output .= "<td class='relative-side'>not applicable</td>";
                                    else
                                        $output .= "<td class='relative-side'> <a href = '" . $row['certificate_path'] . "' target='_blank'>View Certificate</td>";
                                } else
                                    $output .= "<td class='relative-side'>no status </td>";

                                //     if (($row['report_path']) != "") {
                                //         if (($row['report_path']) == 'NULL') {
                                //             $output .= "<td class='relative-side'>
                                //       <form action = 'upload-report_cocurricular.php' method = 'POST'>
                                //           <input type = 'hidden' name = 'id' value = '" . $row['co_curricular_ID'] . "'>
                                //           <button type = 'submit' class = 'btn btn-primary btn-sm'>
                                //               <span class='glyphicon glyphicon-upload'></span>
                                //           </button>
                                //       </form>
                                //   </td>";
                                //         } else if (($row['report_path']) == "not_applicable")
                                //             $output .= "<td class='relative-side'>not applicable</td>";
                                //         else
                                //             $output .= "<td class='relative-side'> <a href = '" . $row['report_path'] . "' target='_blank'>View Report</td>";
                                //     } else
                                //         $output .= "<td class='relative-side'>no status </td>";

                                $output .= "<td>
                    <form action = '3_edit_cocurricular.php' method = 'POST'>
                        <input type = 'hidden' name = 'rid' value = '" . $row['co_curricular_ID'] . "'>
                        <button type = 'submit' class = 'btn btn-primary btn-sm'>
                            <span class='glyphicon glyphicon-edit'></span>
                        </button>
                    </form>
                </td>";

                                $output .= "<td class='relative-side'>
                    <form action = '4_delete_cocurricular.php' method = 'POST'>
                        <input type = 'hidden' name = 'rid' value = '" . $row['co_curricular_ID'] . "'>
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

        $page_query = "SELECT * FROM co_curricular where Fac_ID='" . $_SESSION['Fac_ID'] . "' ORDER BY co_curricular_ID ASC";
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
                <a href="count_your_cocurricular.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon ">Count Co-Curricular Activitiess</span></a>

                <a href="1_add_activity_multiple_cocurricular.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Co-Curricular Activity</span></a>

                <a href="export_to_excel_cocurricular.php" type="button" name="export" class="btn btn-success btn-sm"><span class="glyphicon ">Export to Excel</span></a>

            <?php } else { ?>
                <div class="text-left"><a href="1_add_activity_multiple_cocurricular.php" type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus">Add Co-Curricular Activity</span></a>

                <?php } ?>
                <br>
                <br>
                </div>
                </section>

            </div>

            <?php include_once('footer.php'); ?>