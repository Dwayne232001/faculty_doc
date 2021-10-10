<?php
ob_start();
session_start();
$_SESSION['currentTab'] = "paper";

include 'includes/connection.php';
$table = "faculty"; 
$filename = "paper_publication_all"; 
$sql = $_SESSION['sql'];

$result = mysqli_query($conn,$sql) or die("Couldn't execute query:<br>" . mysqli_error(). "<br>" . mysqli_errno()); 
$file_ending = "xls";
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=$filename.xls");
header("Pragma: no-cache"); 
header("Expires: 0");
$sep = "\t"; 
$names = mysqli_fetch_fields($result) ;
foreach($names as $name){
	if($name->name != 'P_ID' && $name->name != 'Fac_ID' && $name->name != 'paper_path' && $name->name != 'certificate_path' && $name->name != 'report_path' && $name->name != 'Mobile' && $name->name != 'Email' && $name->name != 'Password' && $name->name != 'token') 

    print ($name->name . $sep);
    }
print("\n");
while($row = mysqli_fetch_row($result)) {
    $schema_insert = "";
    for($j=2; $j<=16;$j++) {
		
        if(!isset($row[$j]))
            $schema_insert .= "NULL".$sep;
        elseif ($row[$j] != "")
            $schema_insert .= "$row[$j]".$sep;
        else
            $schema_insert .= "".$sep;
		
    }
    $schema_insert = str_replace($sep."$", "", $schema_insert);
    $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
    $schema_insert .= "\t";
    print(trim($schema_insert));
    print "\n";
}
/*$connect = mysqli_connect("localhost", "root", "", "department");
$output = '';

 $query = "SELECT * FROM faculty";
 $result = mysqli_query($connect, $query);
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" bordered="1">  
                    
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
    <tr>  
                         <td>'.$row["Fac_ID"].'</td>  
                         <td>'.$row["Date_from"].'</td>  
           
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=download.xls');
  echo $output;
 }*/

?>

