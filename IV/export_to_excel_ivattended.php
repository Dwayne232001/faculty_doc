<?PHP
  // Original PHP code by Chirp Internet: www.chirp.com.au
  // Please acknowledge use of this code by including this header.
session_start();
$sql = $_SESSION['table_query']; //query returned
include '../Includes/connection.php'; //connection
$isAnalysis = 0; //is analysis

if(isset($_GET['flag']))
{
 $GLOBALS['isAnalysis'] = 1; 
} 

  function cleanData(&$str) //function used for cleaning the data, without this date will be printed as #######
  {
    if($str == 't') $str = 'TRUE';
    if($str == 'f') $str = 'FALSE';
    if(preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str)) {
      $str = "'$str";
    }
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }

  // filename for download
  $filename = "Department_IV" . date('dmY') . ".csv"; //can change the extension to .csv
  
  $colnames = [
  'F_NAME' =>"Faculty Name",
    'ind' => "Industry Name",
    'city' => "City",
    'purpose' => "Purpose",
    
  ];

  header("Content-Disposition: attachment; filename=\"$filename\"");
  header("Content-Type: text/csv");

  $out = fopen("php://output", 'w');

  $flag = false;
  $result = mysqli_query($conn,$sql) or die("Couldn't execute query:<br>" . mysqli_error($conn). "<br>" . mysqli_errno($conn)); 
  //$result = pg_query("SELECT * FROM table ORDER BY field") or die('Query failed!');
  if($isAnalysis==1)
  {
    $temp=array("Activity Name","Total Number of Activities");
    fputcsv($out, $temp,',','"');
    $temp= array($_GET['type'],$_GET['count']);
    fputcsv($out, $temp,',','"');
    $temp=array("---------------------------------------------------------------------------");
    fputcsv($out,$temp,',','"');
    $temp=array("Date",$_GET['date']);
    fputcsv($out, $temp,',','"');
    $temp=array("---------------------------------------------------------------------------");
    fputcsv($out,$temp,',','"');
  }
  while($row = mysqli_fetch_assoc($result)) 
  {
    if(!$flag) 
    {
      // display field/column names as first row
      $column_name = array();
      $names = mysqli_fetch_fields($result) ;
      foreach($names as $name){
		if($name->name != 't_audience'  ) 		  
			$column_name[] = $colnames[$name->name];
        //print ($colnames[$name->name] . $sep);
      }
      
      fputcsv($out, $column_name, ',', '"');
      //fputcsv($out, array_keys($row), ',', '"'); //fputcsv is used to put the csv data
      $flag = true; //after making this true, the key names wont be printed again
    }
    $row['t_to']  = date("d-m-Y",strtotime($row['t_to']));
    $row['t_from']= date("d-m-Y",strtotime($row['t_from']));
    array_walk($row, __NAMESPACE__ . '\cleanData');//walk each element of array into cleandata function    
    fputcsv($out, array_values($row), ',', '"'); //putting values in .csv files without any formatting but as cleanedData
  }

  fclose($out);
  exit;
?>