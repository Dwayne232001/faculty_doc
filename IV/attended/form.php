<script>
$(document).ready(function(){
  $(".1").attr("class","active");
});
</script>

<style>
.error {
    color: red;
}
</style>

<?php 
ob_start();
if(session_status() == PHP_SESSION_NONE)
{
    //session has not started
    session_start();
}
$conn=connection();
$_SESSION['currentTab']="iv";


//$conn=mysqli_connect('localhost','root','','preyash');

if($_SESSION['username']=="")
{
  header("refresh:1,url=index.php");
  die("Login Required");
}
else
{
  $username = $_SESSION['username'];
}

$count = $GLOBALS['count']; //will be found in IV.php

$flag=array();
//-----------------------------
//$dateerr = array();
$derror = array();
$nameerr = array();
$inderr = array();
$cityerr = array();
$perr = array();
$permission=array();
$report = array();
$certificate= array();
//-------------------------------
 $f_id = array();
 //$ivdate= array();
 $ind= array();
 $city= array();
 $purpose= array();
 $from = array();
 $to = array();
//-------------------------------
$id = -999;

function test_input($data) 
{
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}



//this cond is must, if user jumps from page of updating to add, session[id] will be considered from update
if(isset($_SESSION['id']) && !isset($_GET['count']) ) 
{
 $id = $_SESSION['id']; 
 $employee=mysqli_fetch_assoc(IV("*",$attended,$id,"select")); //function will be found in IVSql.php
 $count = 1;
}

function isNoError($flags)
{
		for($i=0;$i<$GLOBALS['count'];$i++)
		 {	
			 if($flags[$i]==0)
			 {
			 	continue;
			 }
			 else
			 {
			 	return 0;
			 }

		 }
		 return 1;
}
//setting arrays----------------------
for($i=0;$i<$count;$i++)
{
 $flag[$i] = 0;
 //--------------------
 //$dateerr[$i] = "";
 $derror[$i] = "";
 $nameerr[$i] = "";
 $inderr[$i] = "";
 $cityerr[$i] = "";
 $perr[$i] = "";
 //------------------------
 $permission[$i]="";
 $report[$i] = "";
 $certificate[$i]= "";
}


if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	if(isset($_POST['add']))
	{

		//the form was submitted
    	// $fname_array=$_POST['fname'];
    	//$fname = $_POST['fname'];
    	$f_id_array = $_POST['fid'];
		//$ivdate_array = $_POST['ivdate'];
		$ind_array = $_POST['ind'];
		$city_array = $_POST['city'];
		$purpose_array = $_POST['purpose'];
		$from_array= $_POST['from'];
		$to_array= $_POST['to'];
		//$tdate_array= CURRENT_TIMESTAMP();
		
		
		for($i=0; $i<count($from_array);$i++)
		{
			$flag[$i]=0;
			$f_id[$i] = mysqli_real_escape_string($conn,$f_id_array[$i]);
			//$ivdate[$i] = mysqli_real_escape_string($conn,$ivdate_array[$i]);
			$ind[$i] = mysqli_real_escape_string($conn,$ind_array[$i]);
			$city[$i] = mysqli_real_escape_string($conn,$city_array[$i]);
			$purpose[$i] = mysqli_real_escape_string($conn,$purpose_array[$i]);	
			$from[$i] = mysqli_real_escape_string($conn,$from_array[$i]);
			$to[$i] = mysqli_real_escape_string($conn,$to_array[$i]);
			//$tdate[$i]= mysqli_real_escape_string($conn,$tdate_array[$i]);
			/*if(empty($_POST['ivdate'][$i]))
			{
				$dateerr[$i]="Please enter a date";
				$flag[$i]++;
			}*/
			if(empty($_POST['ind'][$i]))
			{
				$inderr[$i]="Please enter the details";
				$flag[$i]++;
			}
			if(empty($_POST['city'][$i]))
			{
				$cityerr[$i]="Enter the city";
				$flag[$i]++;
			}
			else
			{
				$city[$i] = test_input($_POST['city'][$i]);
				if (!preg_match("/^[a-zA-Z]*$/",$city[$i]))
				{
					$cityerr[$i] = "City name cannot contain number,space";
					$flag[$i]++;
				}
			}
			if(empty($_POST['purpose'][$i]))
			{
				$perr[$i]="Please enter Purpose";
				$flag[$i]++;
			}
			if(empty($_POST['from'][$i]) && empty($_POST['to'][$i]))
			{
				$derror[$i]="Enter the duration";
				$flag[$i]++;
			}
			else if((strtotime($_POST['from'][$i]))>(strtotime($_POST['to'][$i])))
			{
				$derror[$i]="Incorrect date entered. Date from cannot be greater than Date to";
				$flag[$i]++;
			}
		
		}
		
		if(isNoError($flag))
		{	 	
			for($i=0;$i<$count;$i++)	
			{

				$val = array($id,$f_id[$i],$ind[$i],$city[$i],$purpose[$i],/*$ivdate[$i],*/$permission[$i],$report[$i],$certificate[$i],$from[$i],$to[$i]);
				if($id!=-999)
				{				
					$result = IV("what",$attended,$val,"update");					
					//$sql="UPDATE attended set f_name ='$fname' ,ind ='$ind', city='$city', purpose='$purpose', date='$ivdate' where f_id= $id;";		
				}
				else
				{					
				 	$result = IV("what",$attended,$val,"insert");				 	
				 	//$sql="INSERT INTO attended (f_name,ind,city,purpose,date) VALUES ('$fname','$ind','$city','$purpose','$ivdate')";
				}
			}
			if($id!=-999)
			{
				unset($_SESSION['id']);		
				header("location:IV.php?x=IV/select_menu/edit_menu.php&alert=update&type=attended");	
			}	
			else
			{
				header("location:IV.php?x=IV/select_menu/edit_menu.php&alert=success&type=attended");
			}
		}	
	}
}
?>


  <!-- Content Header (Page header) -->
     
    <section class="content">
            <!-- left column -->
            <div class="col-md-8">
										  			  <br/><br/><br/>

              <!-- general form elements -->
              	<div class="box box-primary">

                	<div class="box-header with-border">
                 		<i style="font-size:20px" class="fa fa-edit"></i>
					<h3 class="box-title"><b>IV activity attended Form</b></h3>
                     <br/> 
					<b><a href="menu.php?menu=10 " style="font-size:15px;">IV activity </a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="IV.php?x=IV/select_menu/addcount.php" style="font-size:15px;">&nbsp;No. of Entries to be added</a><span style="font-size:17px;">&nbsp;&rarr;</span><a href="#" style="font-size:15px;">&nbsp;Add IV </a></b>					 
			<br/>  <br/>  <h2 class="box-title" align="right"> Last updated on: 				

				<?php
				    $query = "SELECT Fac_ID from facultydetails where Email='".$_SESSION['loggedInEmail']."';";
					
                    $result=mysqli_query($conn,$query);
					
                    if($result){
                    $row = mysqli_fetch_assoc($result);
                    $author = $row['Fac_ID'];
	                }
					if ($author == 1)
					{
				    $sql= "SELECT tdate from iv_attended ORDER BY id DESC LIMIT 1;";
					
					$result1= mysqli_query($conn,$sql);
					if ($result1)
					{
						$row1= mysqli_fetch_assoc ($result1);
						$latest= $row1['tdate'];
						echo $latest;
					}
					else
					{
						echo "No entries added yet";
					}
					}
					else
					{
					 $sql= "SELECT tdate from iv_attended where fac_id = '".$author."' ORDER BY p_id DESC LIMIT 1;";
					
					$result1= mysqli_query($conn,$sql);
					if ($result1)
					{
						$row1= mysqli_fetch_assoc ($result1);
						$latest= $row1['tdate'];
						echo $latest;
					}
					else
					{
						echo "No entries added yet";
					}	
					}
					mysqli_close($conn);
					?>						
                	</div><!-- /.box-header -->
                
                <!-- form start -->
				<form role="form" method="POST" class="row" action= "" style= "margin:10px;" >	
				<?php
			
					for($k=0; $k<$count ; $k++)
					{
						//echo $f_id[$k];$ind[$k],$city[$k],$purpose[$k],$ivdate[$k],$permission[$k],$report[$k],$certificate[$k],$from[$k],$to[$k];
						//echo isNoError($flag);
						//show faculty names multiple time only when HOD, not when user
						echo "<div class='form-group col-md-12 box-header with-border'>";
                        if($_SESSION['username'] == 'hodextc@somaiya.edu' || $_SESSION['username'] == 'member@somaiya.edu' || $_SESSION['username'] == 'hodcomp@somaiya.edu')
                        	echo "<label for='faculty-name'>Faculty Name</label>";
                    	else
                    		if($k==0)
                    			echo "<label for='faculty-name'>Faculty Name</label>";


                    	if($id!=-999) //not a new entry i.e editing as id is set
                    	{
                    		$f_name = mysqli_fetch_assoc(getFacultyDetails($employee['f_id']))['F_NAME'];
                    		$f_id = $employee['f_id'];
                    	}
                    	else //new Entry
                    	{	
                    		$f_name = $_SESSION['loggedInUser'];
                    		$f_id = $_SESSION['f_id'];	
                    	}

                    	//if HOD then give drop down
                    	if($_SESSION['username'] == 'hodextc@somaiya.edu' || $_SESSION['username'] == 'member@somaiya.edu' || $_SESSION['username'] == 'hodcomp@somaiya.edu')
                    	{
                    	?>
                    	<select name="fid[]" class="form-control input-lg" > 

                    	<?php
                              $temp="";
                              $temp = getFacultyDetails($temp);
                              while($fac=mysqli_fetch_assoc($temp))
                                {
                                    if($fac[Email]!="hodextc@somaiya.edu" && $fac[Email]!="member@somaiya.edu" && $fac[Email]!="hodcomp@somaiya.edu" ) //not HOD
                                    {
										if($id!=-999 && $fac['Fac_ID']==$f_id) //not a new entry i.e editing as id is set
                                      		echo "<option value = '$fac[Fac_ID]' SELECTED>$fac[F_NAME]</option>";
										else if(isset($_POST['fid']) && $fac['Fac_ID']==$_POST['fid'][$k])
                                      		echo "<option value = '$fac[Fac_ID]' SELECTED>$fac[F_NAME]</option>";
										else
											echo "<option value = '$fac[Fac_ID]'>$fac[F_NAME]</option>";
									}
                                }
                        ?>
                        </select>

                        <?php        
                    	}
                    	else
                    	{
							if($k==0)//when normal user, show the name only once
                    		 {
                    		 echo "<input required type='hidden' name='fid[]' value=$f_id >"; //for faculty id
                    		 echo "<input required type='text' class='form-control input-lg' id='faculty-name' name='fname' value='$f_name' readonly>";
                    		 }
                    		 else
                    		 {
                    		 echo "<input required type='hidden' name='fid[]' value=$f_id >"; //for faculty id
                    		 echo "<input required type='hidden' class='form-control input-lg' id='faculty-name' name='fname' value='$f_name'>";
                    		 }
                    	}	
                    	?>

                         
                </div><br/> <br/> <br/> <br/> 
                	 <div class="form-group col-md-12">
                		 <label >Industry Name</label><span class="required">*</span>         
         	 			 <input type="textarea" rows="5" cols="5" class="form-control" name="ind[]" value='<?php if(isset($_POST['ind'][$k])){ echo $_POST['ind'][$k];} else if($id!=-999){ echo $employee['ind'];}?>'>
          				 <span class="error"><?php if($flag[$k]!=0){echo $inderr[$k];} ?></span>
                	 </div>
                	 <!--<?php/*
                     <div class="form-group col-md-6">
                         <label>Date of visit:</label><span class="required">*</span>
          				 <input type="date" name="ivdate[]" class="form-control" value='<?php if(isset($_POST['ivdate'][$k])){echo $_POST['ivdate'][$k];} else if($id!=-999){ echo $employee['date'];}?>'>
         				 <span class="error"><?php if($flag[$k]!=0){echo $dateerr[$k];} ?></span>
                     </div>*/?>-->
                     <div class="form-group col-md-12">
                         <label >Purpose</label><span class="required">*</span>        
          				<textarea rows="5" cols="5" class="form-control" name="purpose[]"><?php if(isset($_POST['purpose'][$k])){echo $_POST['purpose'][$k];} else if($id!=-999){ echo $employee['purpose'];} ?></textarea>
          				<span class="error"><?php if($flag[$k]!=0){echo $perr[$k];} ?></span>
                     </div>

                     <div class="form-group col-md-8"> 
                         <label>City</label><span class="required">*</span>
          				 <input type="text" class="form-control" name="city[]" value=<?php if(isset($_POST['city'][$k])){echo $_POST['city'][$k];} else if($id!=-999){ echo $employee['city'];} ?>>
          				 <span class="error"><?php if($flag[$k]!=0){echo $cityerr[$k];} ?></span>
                     </div>

					<div class="form-group col-md-12">	
						<label>Duration</label><span class="required"> *</span>
						<br>
						<b>From:</b> &emsp;<input type="date" name="from[]" placeholder="from" value="<?php if(isset($_POST['from'][$k])){echo $_POST['from'][$k];} else if($id!=-999){ echo $employee['t_from'];}?>">	
						&emsp;
						<b>To:</b>&emsp;<input type="date" name="to[]" placeholder="to" value="<?php if(isset($_POST['to'][$k])){echo $_POST['to'][$k];} else if($id!=-999){ echo $employee['t_to'];}?>"><br>
					<span class="error"><?php if($flag[$k]!=0){echo $derror[$k];} ?></span>			
				
					</div>
                     <p>**********************************************************************************</p>
     
                   <?php
					}
				   ?>
					<br/>
                    <div class="form-group col-md-12">
                         <a href="IV.php?x=IV/select_menu/addcount.php" type="button" class="btn btn-warning btn-lg">Cancel</a>

                         <input name="add" type="submit" class="btn btn-success pull-right btn-lg">
                    </div>
        		</form>
    			</div>
	 		</div>         
    </section>
       
       