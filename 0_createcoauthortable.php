<?php

include("includes/connection.php");

$sql="CREATE TABLE co_author(
	c_id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	p_id int(11) NOT NULL,
	c_name varchar(50) 
)AUTO_INCREMENT=10001";

$sql1="ALTER TABLE co_author ADD CONSTRAINT c_p_id FOREIGN KEY(P_ID) REFERENCES faculty(P_ID) on UPDATE CASCADE";

if ($conn->query($sql) === TRUE && $conn->query($sql1) === TRUE) {
    echo "Table created and altered successfully";
} else {
    echo "Error creating table: " . $conn->error;
}
?>