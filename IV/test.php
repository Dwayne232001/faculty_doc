<?php
include_once('IVSql.php');
$file ="report";
$type = "IV_attended";
$id = 38;
$iv=mysqli_fetch_object(IV($file,$type,$id,"select"));
if(strcmp($iv->$file,"NULL")!=0 && strcmp($iv->$file,"")!=0 &&strcmp($iv->$file,"not_applicable")!=0)//check if already has image uploaded
             	{
 	            	echo $iv->$file;
             	}

?>