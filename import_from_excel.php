<?php
ob_start();
session_start();
include_once('head.php');
include_once('header.php');
if (!isset($_SESSION['loggedInUser'])) {
   //send the iser to login page
   header("location:index.php");
}
// if(isset($_SESSION['type'])){
if ($_SESSION['type'] != 'hod') {
   //if not hod then send the user to login page
   session_destroy();
   header("location:index.php?");
}
// }
//connect ot database
include_once("includes/connection.php");

$fid = $_SESSION['Fac_ID'];

$queryrun = "SELECT * FROM facultydetails where Fac_ID=$fid";
$resultrun = mysqli_query($conn, $queryrun);
while ($row = mysqli_fetch_assoc($resultrun)) {
   $_SESSION['Dept'] = $row['Dept'];
   $_SESSION['type'] = $row['type'];
}

//include custom functions files 
include_once("includes/functions.php");
include_once("includes/scripting.php");

//include config file
include_once("includes/config.php");

if ($_SESSION['type'] == 'hod') {
   include_once('sidebar_hod.php');
} elseif ($_SESSION['type'] == 'cod' || $_SESSION['type'] == 'com') {
   include_once('sidebar_cod.php');
} else {
   include_once('sidebar.php');
}

?>
<?php
include 'PHPExcel-1.8/Classes/PHPExcel';
if (isset($_POST['submit'])) {
   // print_r($_POST['dropdown']);
}
if (isset($_FILES['excel'])) {
   require_once('includes/connection.php');
   // $db_user='root';
   // $db_password='';
   // $db='department';
   // $conn=mysqli_connect($host,$db_user,$db_password) or die(mysqli_error());
   // mysqli_select_db($db) or die(mysqli_error);

   $errors = array();
   $file_name = $_FILES['excel']['name'];
   $file_size = $_FILES['excel']['size'];
   $file_tmp = $_FILES['excel']['tmp_name'];
   $file_type = $_FILES['excel']['type'];
   // $file_ext=strtolower(end(explode('.',$_FILES['excel']['name'])));
   $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

   $expensions = array("xsl", "xls", "xlsx", "xltx", "xlt");

   if (in_array($file_ext, $expensions) === false) {
      $errors[] = "extension not allowed, please choose an Excel file.";
   }

   if ($file_size > 2097152) {
      $errors[] = 'File size must be exactly 2 MB';
   }

   if (empty($errors) == true) {
      //move_uploaded_file($file_tmp, $file_name);
      copy($file_tmp, $file_name);
      //echo "Success";
      //chmod(".",0777);
      exec("sudo chmod -R 777 .");
   
      switch ($_POST['dropdown']) {
         case 'faculty':
            exec('python pandas_test.py ' . $file_name . ' 2>&1', $output);
            break;
         case 'paper_review':
            exec('python pandas_paper_review.py ' . $file_name . ' 2>&1', $output);
            break;
         case 'researchdetails':
            exec('python pandas_research.py ' . $file_name . ' 2>&1', $output);
            break;
         case 'invitedlec':
            exec('python pandas_invitedlec.py ' . $file_name . ' 2>&1', $output);
            break;
         case 'attended':
            exec('python pandas_attended.py ' . $file_name . ' 2>&1', $output);
            break;
         case 'organised':
            exec('python pandas_organised.py ' . $file_name . ' 2>&1', $output);
            break;
         case 'guestlec':
            exec('python pandas_guestlec.py ' . $file_name . ' 2>&1', $output);
            break;
         case 'online_course_attended':
            exec('python pandas_ocattended.py ' . $file_name . ' 2>&1', $output);
            break;
         case 'online_course_organised':
            exec('python pandas_ocorganised.py ' . $file_name . ' 2>&1', $output);
            break;
         case 'iv_organized':
            exec('python pandas_ivorganized.py ' . $file_name . ' 2>&1', $output);
            break;
         case 'co_curricular':
            exec('python pandas_cocurr.py ' . $file_name . ' 2>&1', $output);
            break;
         case 'ex_curricular':
            exec('python pandas_excurr.py ' . $file_name . ' 2>&1', $output);
            break;
         case 'any_other_activity':
            exec('python pandas_anyother.py ' . $file_name . ' 2>&1', $output);
            break;
         default:
            $output = 'Error';
            break;
      }
      //print_r($output);
      unlink($file_name);
      
      // if(isset($output) && file_exists(dirname(__FILE__).'/excels/failed_'.$file_name.'.'.$file_ext)){
      //    // echo $file_name;
      //    // libxml_disable_entity_loader(false);
      //    $type = "Excel2007"; 
      //    require_once(dirname(__FILE__)."/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");
      //    // require_once './PHPExcel-1.8/Classes/PHPExcel.php';
      //    $objReader = PHPExcel_IOFactory::createReader($type);
      //    $objPHPExcel = $objReader->load(dirname(__FILE__).'/excels/failed_'.$file_name.'.'.$file_ext);
      //    // header('Content-type: application/vnd.ms-excel');
      //    // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      //    header("Content-Type: application/xls");
      //    header("Content-Disposition: attachment; filename=\"failed_".$file_name.".".$file_ext."\"");
      //    // header('Cache-Control: max-age=0');
      //    header("Pragma: no-cache");
      //    header("Expires: 0");
      //    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $type);
      //    ob_end_clean();
      //    $objWriter->save('php://output');
      //    exit();
      // }
   } else {
   }
}
?>

<style>
   .all,
   .ilec,
   .online,
   .activity,
   .sttp_org,
   .oc_org,
   .iv_org,
   .guestlec,
   .research,
   .review {
      display: none;
   }

   .all,
   .paper,
   .ilec,
   .online,
   .activity,
   .sttp_org,
   .oc_org,
   .iv_org,
   .guestlec,
   .research,
   .review {
      margin-top: 5px;
   }
</style>

<script>
   $(function() {
      $('#category').change(function() {
         var opt = $(this).val();
         if (opt == 'faculty') {
            $('.paper').show();
            $('.ilec').hide();
            $('.online').hide();
            $('.all').hide();
            $('.activity').hide();
            $('.sttp_org').hide();
            $('.oc_org').hide();
            $('.iv_org').hide();
            $('.research').hide();
            $('.guestlec').hide();
            $('.review').hide();
         } else if (opt == 'invitedlec') {
            $('.ilec').show();
            $('.paper').hide();
            $('.online').hide();
            $('.all').hide();
            $('.activity').hide();
            $('.sttp_org').hide();
            $('.oc_org').hide();
            $('.iv_org').hide();
            $('.research').hide();
            $('.guestlec').hide();
            $('.review').hide();
         } else if (opt == 'online_course_attended') {
            $('.online').show();
            $('.ilec').hide();
            $('.paper').hide();
            $('.all').hide();
            $('.activity').hide();
            $('.sttp_org').hide();
            $('.oc_org').hide();
            $('.iv_org').hide();
            $('.research').hide();
            $('.guestlec').hide();
            $('.review').hide();
         } else if (opt == 'any_other_activity' || opt == 'co_curricular' || opt == 'ex_curricular') {
            $('.activity').show();
            $('.online').hide();
            $('.ilec').hide();
            $('.paper').hide();
            $('.all').hide();
            $('.sttp_org').hide();
            $('.oc_org').hide();
            $('.iv_org').hide();
            $('.research').hide();
            $('.guestlec').hide();
            $('.review').hide();
         } else if (opt == 'organised') {
            $('.sttp_org').show();
            $('.activity').hide();
            $('.online').hide();
            $('.ilec').hide();
            $('.paper').hide();
            $('.all').hide();
            $('.oc_org').hide();
            $('.iv_org').hide();
            $('.research').hide();
            $('.guestlec').hide();
            $('.review').hide();
         } else if (opt == 'online_course_organised') {
            $('.oc_org').show();
            $('.activity').hide();
            $('.online').hide();
            $('.ilec').hide();
            $('.paper').hide();
            $('.all').hide();
            $('.sttp_org').hide();
            $('.iv_org').hide();
            $('.research').hide();
            $('.guestlec').hide();
            $('.review').hide();
         } else if (opt == 'iv_organized') {
            $('.iv_org').show();
            $('.activity').hide();
            $('.online').hide();
            $('.ilec').hide();
            $('.paper').hide();
            $('.all').hide();
            $('.oc_org').hide();
            $('.sttp_org').hide();
            $('.research').hide();
            $('.guestlec').hide();
            $('.review').hide();
         } else if (opt == 'guestlec') {
            $('.guestlec').show();
            $('.iv_org').hide();
            $('.activity').hide();
            $('.online').hide();
            $('.ilec').hide();
            $('.paper').hide();
            $('.all').hide();
            $('.oc_org').hide();
            $('.sttp_org').hide();
            $('.research').hide();
            $('.review').hide();
         } else if (opt == 'researchdetails') {
            $('.research').show();
            $('.iv_org').hide();
            $('.activity').hide();
            $('.online').hide();
            $('.ilec').hide();
            $('.paper').hide();
            $('.all').hide();
            $('.oc_org').hide();
            $('.sttp_org').hide();
            $('.guestlec').hide();
            $('.review').hide();
         } else if (opt == 'paper_review') {
            $('.review').show();
            $('.research').hide();
            $('.iv_org').hide();
            $('.activity').hide();
            $('.online').hide();
            $('.ilec').hide();
            $('.paper').hide();
            $('.all').hide();
            $('.oc_org').hide();
            $('.sttp_org').hide();
            $('.guestlec').hide();
         } else {
            $('.all').show();
            $('.online').hide();
            $('.ilec').hide();
            $('.paper').hide();
            $('.activity').hide();
            $('.oc_org').hide();
            $('.iv_org').hide();
            $('.research').hide();
            $('.guestlec').hide();
            $('.review').hide();
         }
      });
   });
</script>

<html>
<!-- <body align="center"> -->
<div class="content-wrapper">
   <section class="content">
      <div class="row">
         <?php
         if (isset($output)) {
            $msg = '<br/><br/><div class="alert alert-warning">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
            </button>
        <strong>Import done! Entries imported: ' . end($output) . '</strong>
        </div>';
            echo $msg;
         }
         ?>
         <br /><br /><br />
         <div class="col-md-8">
            <div class="box box-primary">
               <div class="box-header with-border" style="width:300">
                  <div class="icon">
                     <i style="font-size:20px" class="fa fa-upload"></i>
                     <h3 class="box-title"><b>Import Data from Excel</b></h3><br>
                     <b><a style="font-size:15px;">Excel</a><span style="font-size:17px;">&nbsp;&rarr;</span><a style="font-size:15px;">&nbsp;Database</a></b>
                  </div>
               </div>
               <div class="form-group col-md-12">
                  <form action="" method="POST" enctype="multipart/form-data"><br>
                     <div class="col-md-6">
                        <label for="excel">Select File</label>
                        <input type="file" class="form-control input-lg" name="excel" id="excel" />
                     </div>
                     <div class="col-md-6">
                        <label for="category">Select Category</label>
                        <select class="form-control input-lg" id="category" name="dropdown">
                           <option value="faculty">Paper Publication</option>
                           <option value="paper_review">Paper Reviewer</option>
                           <option value="researchdetails">Research Details</option>
                           <option value="invitedlec">Faculty Interaction</option>
                           <option value="attended">STTP/Workshop Attended</option>
                           <option value="organised">STTP/Workshop Organised</option>
                           <option value="online_course_attended">Online/Offline Course Attended</option>
                           <option value="online_course_organised">Online/Offline Course Organised</option>
                           <option value="iv_organized">Industrial Visit Organised</option>
                           <option value="guestlec">Guest Lecture Organised</option>
                           <option value="co_curricular">Co-Curricular Activity</option>
                           <option value="ex_curricular">Extra-Curricular Activity</option>
                           <option value="any_other_activity">Any Other Activity</option>
                        </select>
                     </div>
                     <div class="col-md-12 paper">
                        <strong>Header Format Help</strong>
                        <a class="btn btn-link" href="Headers Papers.pdf" target="_blank" id="link1">Click Here</a>
                     </div>
                     <div class="col-md-12 ilec">
                        <strong>Header Format Help</strong>
                        <a class="btn btn-link" href="Headers Invited Lec.pdf" target="_blank" id="link2">Click Here</a>
                     </div>
                     <div class="col-md-12 online">
                        <strong>Header Format Help</strong>
                        <a class="btn btn-link" href="Headers Online.pdf" target="_blank" id="link2">Click Here</a>
                     </div>
                     <div class="col-md-12 activity">
                        <strong>Header Format Help</strong>
                        <a class="btn btn-link" href="Headers Any Other.pdf" target="_blank" id="link2">Click Here</a>
                     </div>
                     <div class="col-md-12 all">
                        <strong>Header Format Help</strong>
                        <a class="btn btn-link" href="Headers All.pdf" target="_blank" id="link2">Click Here</a>
                     </div>
                     <div class="col-md-12 sttp_org">
                        <strong>Header Format Help</strong>
                        <a class="btn btn-link" href="Headers STTP Organised.pdf" target="_blank" id="link2">Click Here</a>
                     </div>
                     <div class="col-md-12 oc_org">
                        <strong>Header Format Help</strong>
                        <a class="btn btn-link" href="Headers Online Organised.pdf" target="_blank" id="link2">Click Here</a>
                     </div>
                     <div class="col-md-12 iv_org">
                        <strong>Header Format Help</strong>
                        <a class="btn btn-link" href="Headers IV Organised.pdf" target="_blank" id="link2">Click Here</a>
                     </div>
                     <div class="col-md-12 guestlec">
                        <strong>Header Format Help</strong>
                        <a class="btn btn-link" href="Headers GuestLec.pdf" target="_blank" id="link2">Click Here</a>
                     </div>
                     <div class="col-md-12 research">
                        <strong>Header Format Help</strong>
                        <a class="btn btn-link" href="Headers Research.pdf" target="_blank" id="link2">Click Here</a>
                     </div>
                     <div class="col-md-12 review">
                        <strong>Header Format Help</strong>
                        <a class="btn btn-link" href="Headers Review.pdf" target="_blank" id="link2">Click Here</a>
                     </div>
                     <div class="col-md-12">
                        <?php
                        if (isset($output) && file_exists('excels/failed_' . $file_name . '.' . $file_ext)) {
                           echo '<strong style="color:#ff0000">Failed Entries Details</strong>';
                           echo '<a class="btn btn-link" href="excels/failed_' . $file_name . '.' . $file_ext . '" target="_blank">Click Here</a>';
                        }
                        ?>
                     </div>
               </div>
               <div class="form-group col-md-12">
                  <a href="2_dashboard.php" type="button" class="demo btn btn-warning btn-lg">Cancel</a>
                  <button type="submit" value="submit" name="submit" class="demo btn btn-success pull-right btn-lg">Submit</button>
               </div>
               
               </form>
               &nbsp;&nbsp;&nbsp;&nbsp; Permitted Extensions - xsl, xls, xlsx, xltx, xlt
            </div>
         </div>
      </div>
   </section>
</div>
<!-- </body> -->
<?php
include_once('footer.php');
?>

</html>