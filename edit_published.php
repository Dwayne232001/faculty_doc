<?php
session_start();
//check if user has logged in or not

if(!isset($_SESSION['loggedInUser'])){
    //send the user to login page
    header("location:index.php");
}
$_SESSION['currentTab']="books";

//connect to database
include_once("includes/connection.php");

//include custom functions files 
include_once("includes/functions.php");
include_once("includes/config.php");

$Fac_ID=$_SESSION['Fac_ID'];
$queryrun="SELECT * FROM facultydetails where Fac_ID=$Fac_ID";
$resultrun = mysqli_query($conn, $queryrun);
while($row=mysqli_fetch_assoc($resultrun)){
  $_SESSION['Dept']=$row['Dept'];
  $_SESSION['type']=$row['type'];
}


//setting error variables
$nameError="";
$flag = 1;
$emailError="";

// $Fac_ID=null;
// date_default_timezone_set("Asia/Kolkata");
$id = $_SESSION['id'];
if(isset($_POST['rid'])){
	$id = $_POST['rid'];
	$_SESSION['id']=$_POST['rid'];
}
    // $interac_id = $_SESSION['id'];
    // $query = "SELECT * from facInteraction";
	$query = "SELECT * from books_published where published_id = $id";

    $result = mysqli_query($conn,$query);
    $row = mysqli_fetch_assoc($result);
    //print_r($row);
    // $Fac_ID = $row['Fac_ID'];
    // $organized = $row['organised_by'];
    // $durationf = $row['date_from'];
    // $durationt = $row['date_to'];
    // $resource = $row['invitation'];
    // $award = $row['award'];
    // $topic = $row['topic'];
    // $details = $row['details'];
    // $tdate = $row['tdate'];
    // $paperpath=$row['invitation_path'];
	// $certipath=$row['certificate'];

    $first = $row['first_name'];
	$last = $row['last_name'];
	$type = $row['book_type'];
	$title = $row['title'];
	$edition = $row['edition'];
	$publisher = $row['publisher_name'];
	$chapter = $row['chapter_no'];
	$issn = $row['issn_no'];
	$date = $row['date'];
	$url = $row['url'];


	$fid = $_SESSION['Fac_ID'];	

	$query2 = "SELECT * from facultydetails where Fac_ID = $fid";
	$result2 = mysqli_query($conn,$query2);
	if($result2)
	{
		$row = mysqli_fetch_assoc($result2);
		$F_NAME = $row['F_NAME'];
	}
	$_SESSION['F_NAME'] = $F_NAME;
	   
//check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if(isset($_POST['update'])){
    //the form was submitted
    
    //check for any blank input which are required
        // $organized=validateFormData($_POST['organised_by']);
		// $organized = "'".$organized."'";
		
		// if ((strtotime($_POST['startDate'])) > (strtotime($_POST['endDate'])))
		// {
		// 	$nameError=$nameError."Start Date cannot be greater than end date<br>";
		// 	$flag = 0;
		// }	
   
        // $startDate=validateFormData($_POST['startDate']); 
		// $endDate=validateFormData($_POST['endDate']);
		
		// $time=time();
        // $start = new DateTime(date($startDate,$time));
        // $end = new DateTime(date($endDate,$time));
        // $days = date_diff($start,$end);
        // $noofdays = $days->format('%d');

		// $startDate = "'".$startDate."'";
        // $endDate = "'".$endDate."'";

        // $first=validateFormData($_POST['first_name']);
		// $first = "'".$first."'";
  
        // $last=validateFormData($_POST['last_name']);
        // $last = "'".$last."'";
   
        // $type=validateFormData($_POST['book_type']);
        // $type = "'".$type."'";
    
        // $title=validateFormData($_POST['title']);
        // $title = "'".$title."'";
   
        // $edition=validateFormData($_POST['edition']);
        // $edition = "'".$edition."'";
   
		// $publisher=validateFormData($_POST['publisher_name']);
        // $publisher = "'".$publisher."'";

        // $chapter=validateFormData($_POST['chapter_no']);
		// $chapter = "'".$chapter."'";
		
		// $issn=validateFormData($_POST['issn_no']);
		// $issn = "'".$issn."'";
	
        // $date=validateFormData($_POST['date']);
        // $date = "'".$date."'";

        // $url=validateFormData($_POST['url']);
        // $url = "'".$url."'";

        $time=time();
		$date_calc = new DateTime(date($date,$time));
		$month = $date_calc->format('n');
		$year = $date_calc->format('Y');


		
        // if($award = ""){
        // 	$award='NA';
        // }else{
		// 	$award=validateFormData($_POST['award']);
		// 	$award="$award";
		// }
   
    //following are not required so we can directly take them as it is
    // if(isset($_POST['applicable']))
	// {
	// 	// console.log($_POST['applicable']);
	// 	if($_POST['applicable'] == 2)
	// 	{
	// 		$paperpath='NULL';
	// 		$success=1;

	// 	}
	// 	else if($_POST['applicable'] == 3)
	// 	{
	// 		$paperpath='not_applicable';
	// 		$success=1;
	// 	}
	// 	else if($_POST['applicable'] == 1)
	// 	{
	// 		if(isset($_FILES['paper']))
	// 		{
	// 			$errors= array();
	// 			$fileName = $_FILES['paper']['name'];
	// 			$fileSize = $_FILES['paper']['size'];
	// 			$fileTmp = $_FILES['paper']['tmp_name'];
	// 			$fileType = $_FILES['paper']['type'];
	// 			$temp=explode('.',$fileName);
	// 			$fileExt=strtolower(end($temp));
	// 			date_default_timezone_set('Asia/Kolkata');
	// 			$targetName=$datapath."invitations/".$_SESSION['F_NAME']."_invitations_".date("d-m-Y H-i-s", time()).".".$fileExt;  
					  
	// 			if(empty($errors)==true) 
	// 			{
	// 				if (file_exists($targetName)) 
	// 				{   
	// 					unlink($targetName);
	// 				}      
	// 				$moved = move_uploaded_file($fileTmp,"$targetName");
	// 				if($moved == true){
	// 					$paperpath=$targetName;
	// 					$success=1;
	// 				}
	// 				else{
	// 				 //not successful
	// 				 //header("location:error.php");
	// 					echo "<h1> $targetName </h1>";
	// 				}
	// 			}else{
	// 				print_r($errors);
	// 					//header("location:else.php");
	//         	}
	// 		}
	// 	}
	// }

	// if(isset($_POST['applicable1']))
	// {
	// 	if($_POST['applicable1'] == 2)
	// 	{
	// 		$certipath='NULL';		
	// 		$success=1;		 
	// 	}
	// 	else if($_POST['applicable1'] == 3)
	// 	{
	// 		$certipath='not_applicable';
	// 		$success=1;		
	// 	}
	// 	else if($_POST['applicable1'] == 1)
	// 	{
	// 		if(isset($_FILES['certificate']))
	// 		{
	// 			$errors= array();
	// 			$fileName = $_FILES['certificate']['name'];
	// 			$fileSize = $_FILES['certificate']['size'];
	// 			$fileTmp = $_FILES['certificate']['tmp_name'];
	// 			$fileType = $_FILES['certificate']['type'];
	// 			$temp=explode('.',$fileName);
	// 			$fileExt=strtolower(end($temp));
	// 			date_default_timezone_set('Asia/Kolkata');
	// 			$targetName=$datapath."certificates/".$_SESSION['F_NAME']."_Certificates_".date("d-m-Y H-i-s", time()).".".$fileExt;  	  
	// 			if(empty($errors)==true) {
	// 				if (file_exists($targetName)) {   
	// 					unlink($targetName);
	// 				}      
	// 				$moved = move_uploaded_file($fileTmp,"$targetName");
	// 				if($moved == true){
	// 				 	$certipath=$targetName;
	// 				 	$success=1;		
	// 				}
	// 				else{
	// 					echo "<h1> $targetName </h1>";
	// 				}
	// 			}else{
	// 				print_r($errors);
	// 			}
	// 		}
	// 	}
	// }
    //checking if there was an error or not
  $query = "SELECT Fac_ID from facultydetails where Email='".$_SESSION['loggedInEmail']."';";
        $result=mysqli_query($conn,$query);
       if($result){
            $row = mysqli_fetch_assoc($result);
            $author = $row['Fac_ID'];
	   }
		// 		$succ = 0;
		// 		$success1 = 0;	
		// $tdate = date("Y-m-d h:i:sa");

		// $resource=$_POST['resource'];
       	// $topic=validateFormData($_POST['topic']);
       	// if($topic==""){
       	// 	$topic='NA';
       	// }
		// $details=validateFormData($_POST['details']);
		// if($details = ""){
		// 	$details='NA';
		// }
		// $replace_str = array('"', "'",'' ,'');
		// if(isset($_POST['award']))
		// 	$award = str_replace($replace_str, "", $award);
		// $replace_str = array('"', "'",'' ,'');
		// if(isset($_POST['details']) && $_POST['details']!="")
		// {
		// 	$details = $_POST['details'];
		// 	$details = "$details";
		// }else{
		// 	$details="NA";
		// }
		// $replace_str = array('"', "'",'' ,'');
		// if(isset($_POST['topic']))
		// {
		// 	$topic = str_replace($replace_str, "", $topic);
		// 	$topic = str_replace("rn",'', $topic);
		// }
		if($flag==1)
		{		
			$sql = "UPDATE books_published set first_name = '$first',
								last_name = '$last',
							    book_type = '$type',
								title ='$title',
								edition='$edition',
								publisher_name='$publisher',
								chapter_no = '$chapter',
                                issn_no = '$issn',
                                date = '$date',
                                url = '$url'
								WHERE published_id = '".$_SESSION['id']."'";

								// award = '$award',
								// topic = '$topic',
								// details = '$details',

			if ($conn->query($sql) === TRUE)
			{
                echo $sql;

				// if($_SESSION['type'] == 'hod')
				// 	{
				// 	   header("location:view_invited_hod_lec.php?alert=update");
				// 	}
				// 	else
				// 	{
				// 		header("location:2_dashboard_books.php?alert=update");
				// 	}
			}
			// else
			// {
			// 	if($_SESSION['type'] == 'hod')
			// 		{
			// 		   header("location:view_invited_hod_lec.php?alert=error");
			// 		}
			// 		else
			// 		{
			// 			header("location:2_dashboard_books.php?alert=error");
			// 		}
			// }
		}
	}
}
//close the connection
mysqli_close($conn);
?>
<?php include_once('head.php'); ?>
<?php include_once('header.php'); ?>
<?php include_once("includes/scripting.php");?>
<?php 
if($_SESSION['type'] == 'hod')
{
	  include_once('sidebar_hod.php');

}elseif ($_SESSION['type']=='cod' || $_SESSION['type']=='com' ) {
		include_once('sidebar_cod.php');
}
else{
	include_once('sidebar.php');
}
 ?>
<style>
.error
{
	color:red;
	border:1px solid red;
	background-color:#ebcbd2;
	border-radius:10px;
	margin:5px;
	padding:0px;
	font-family:Arial, Helvetica, sans-serif;
	width:510px;
}
.colour
{
	color:#ff0000;
}
.demo {
	width: 120px;
}

#form {
	width: 100% !important;
}
</style>

<div class="content-wrapper">
    <section class="content">
          <div class="row">
            <!-- left column -->
            <div class="col-md-8" id="form">
              <!-- general form elements -->
			  			  <br/><br/><br/>

              <div class="box box-primary">
                <div class="box-header with-border">
					<div class="icon">
					<i style="font-size:20px" class="fa fa-edit"></i>
					<h3 class="box-title"><b>Books/Chapter Published Form</b></h3>
					<br>
					<br>
					
            <form role="form" method="POST" class="row" action ="" style= "margin:10px;" enctype="multipart/form-data">
<?php
				if($flag==0)
				{
					echo '<div class="error">'.$nameError.'</div>';
				}	
			?>		

<?php 
// $replace_str = array('"', "'",'' ,'');
// if(isset($_POST['award']))
// $award = str_replace($replace_str, "", $award);

// $replace_str = array('"', "'",'' ,'');
// if(isset($_POST['topic']))
// {
// $topic = str_replace($replace_str, "", $topic);
// $topic = str_replace("rn",'', $topic);

// }

?>					
                <input type = 'hidden' name ='id' value = '<?php echo $id; ?>'>
				<input type="hidden" name="Udate" value="<?php echo date("Y-m-d h:i:sa"); ?>" />
				
	<?php if($_SESSION['type'] == 'hod' || $_SESSION['type'] == 'faculty' )
					{ ?>					

                    <div class="form-group col-md-6">
                        <label for="department_name">Department Name</label>
                        <input required type="text" class="form-control input-lg" id="department_name" name="department_name[]" value="<?php echo strtoupper($_SESSION['Dept']); ?>" readonly>
                    </div>
			
					<div class="form-group col-md-6">
                         <label for="faculty-name">Faculty Name(Author)</label>
                         <input required type="text" class="form-control input-lg" id="faculty-name" name="facultyName[]" value="<?php echo $F_NAME; ?>" readonly>
                    </div>

					<?php } ?>			

                    
                    <div class="form-group col-md-3">
                        <label for="fn_co-author">Co-Author (First Name)</label>
						<span class="colour"><b> *</b></span>
                        <input class="form-control input-lg" type="text" name="first_name[]" id="fn_co-author" placeholder="First Name" value="<?php echo $first ?>">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="ln_co-author">Co-Author (Last Name)</label>
						<span class="colour"><b> *</b></span>
                        <input class="form-control input-lg" type="text" name="last_name[]" id="ln_co-author" placeholder="Last Name" value="<?php echo $last ?>">
                    </div>

					<div class="form-group col-md-6">
                        <label for="book_type">Book Type</label>
						<span class="colour"><b> *</b></span>
                        <select required name="book_type" id="book_type" class="form-control input-lg" >
                            <option <?php if($type == "I") echo "selected = 'selected'" ?> name="Individual" value="Individual">Individual</option>
                            <option <?php if($type == "E") echo "selected = 'selected'" ?> name="Extended" value="Extended">Extended (Conference Proceeding as Chapter)</option>
                        </select>
                    </div>
					
					<div class="form-group col-md-6">
                         <label for="title_book">Title of Book/Chapter </label>
						 <span class="colour"><b> *</b></span>
                         <input type="text" required class="form-control input-lg" id="title_book" name="title[]" placeholder="" value="<?php echo $title ?>">
                    </div>

					<div class="form-group col-md-6">
                         <label for="Edition">Edition</label>
                         <input type="text" class="form-control input-lg" id="Edition" name="edition[]" placeholder="Numeric" value="<?php echo $edition ?>">
                    </div>

					<div class="form-group col-md-6">
                         <label for="name_of_publisher">Name of the Publisher</label>
						 <span class="colour"><b> *</b></span>
                         <input type="text" required class="form-control input-lg" id="name_of_publisher" name="publisher_name[]" value="<?php echo $publisher ?>">
                    </div>

                    <div class="form-group col-md-6">
                         <label for="chapter_num">Chapter Number</label>
                         <input type="text" class="form-control input-lg" id="chapter_num" name="chapter_no[]" placeholder="(If a chapter of the book)" value="<?php echo $chapter ?>">
                    </div>
					 
                    <div class="form-group col-md-6">
                    <label for="Month">Date</label>
					<span class="colour"><b> *</b></span>
                    <input required type="date" class="form-control input-lg" id="Month" name="date[]" value="<?php echo $date ?>">
                    </div>

                    <div class="form-group col-md-6">
                         <label for="number">ISSN/eISSN/ISBN Number</label>
                         <span class="colour"><b> *</b></span>
                         <input type="text" required class="form-control input-lg" id="number" name="issn_no[]" placeholder="" value="<?php echo $issn ?>">
                    </div>

                    <div class="form-group col-md-6">
                         <label for="book_chapter_url">Book/Chapter Link(URL)</label>
						 <span class="colour"><b> *</b></span>
                         <input  <?php if(isset($_POST['url'])) echo "value = $url"; ?> required type="url" class="form-control input-lg" id="location" name="url[]" value="<?php echo $url ?>">
                     </div>


					 <script>
					 
					 window.onload = function() {
						 mycheck1();
						mycheck2();
					 }
					 
					 function mycheck1(){
						var radio1 = document.getElementById("r1");
						var file1 = document.getElementById("f1");
						if(radio1.checked==true){
							file1.style.display = "block";
						}else{
							file1.style.display= "none";
						}
					}
					function mycheck2(){
						var radio2 = document.getElementById("r2");
						var file2 = document.getElementById("f2");
						if(radio2.checked==true){
							file2.style.display = "block";
						}else{
							file2.style.display= "none";
						}
					}

					 </script>
                    <br/>
                    <div class="form-group col-md-12">
	<?php if($_SESSION['type'] == 'hod')
					{ ?>				
                        <a href="view_invited_hod_lec.php" type="button" class="btn btn-warning btn-lg">Cancel</a>
					<?php }
					else{  ?>
        <a href="2_dashboard_books.php" type="button" class="btn btn-warning btn-lg">Cancel</a>
	
					<?php } ?>			
                        <button name="update"  type="submit" class="btn btn-success pull-right btn-lg">Submit</button>
                    </div>
                </form>
                </div>
              </div>
           </div>      
        </section>
</div>
<?php include_once('footer.php'); ?>