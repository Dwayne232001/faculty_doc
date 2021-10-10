<?php
//ob_start();
//session_start();

$server="localhost";
$username="root";
$password="";
/*
if(isset($_SESSION['username'])){if($_SESSION['username'] == ('hodextc@somaiya.edu'))
{
	$db="department";
}
else if($_SESSION['username'] == ('hodcomp@somaiya.edu'))
{
	$db="department";
}
else
}
*/
	$db="faculty_doc_portal";
	
$conn = mysqli_connect($server,$username,$password,$db);
$connect = new PDO("mysql:host=localhost;dbname=faculty_doc_portal", "root", "");

if(!$conn){
    die("Connection failed".mysqli_connect_error());
}
?>