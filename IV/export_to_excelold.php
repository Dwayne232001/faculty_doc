<?php
session_start();
include '../Includes/connection.php'; 
$filename = "Activity_file"; 
$sql = $_SESSION['table_query'];
$result = mysqli_query($conn,$sql) or die("Couldn't execute query:<br>" . mysqli_error($conn). "<br>" . mysqli_errno($conn)); 


$colnames = [
  'F_NAME' =>"Faculty Name",
    'ind' => "Industry Name",
    'city' => "City",
    'purpose' => "Purpose",
    'date' => "Date",
    'permission' =>"Permission",
    'attendance' => "Attendance",
    'certificate' => "Certificate",
    'report' =>"Report",
    't_audience' => "Target Audience",
    't_to' => "To",
    't_from' => "From",
    'tdate' => "Updated at"
  ];


$file_ending = "xls";
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=$filename.xls");
header("Pragma: no-cache"); 
header("Expires: 0");
$sep = "\t"; 
$names = mysqli_fetch_fields($result) ;
foreach($names as $name){
    print ($colnames[$name->name] . $sep);
    }

print("\n");

while($row = mysqli_fetch_row($result)) {
    $schema_insert = "";
    for($j=0; $j<mysqli_num_fields($result);$j++) {
        if(!isset($row[$j]))
        {
            $schema_insert .= "NULL".$sep;
        }
        elseif ($row[$j] != "")
            $schema_insert .= "$row[$j]".$sep;
        else
        {
            $schema_insert .= "".$sep;
        }
    }
    $schema_insert = str_replace($sep."$", "", $schema_insert);
    $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
    $schema_insert .= "\t";
    print(trim($schema_insert));
    print "\n";
}
?>

