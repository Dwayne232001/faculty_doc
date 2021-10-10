<?php

include("includes/connection.php");

$sql="CREATE TABLE committee(
	com_id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	fac_id int(11) NOT NULL,
	date_from date,
	com_name varchar(50),
	com_status varchar(15)
)AUTO_INCREMENT=10001";

$sql1="ALTER TABLE committee ADD CONSTRAINT com_fac_id FOREIGN KEY(Fac_ID) REFERENCES facultydetails(Fac_ID) on UPDATE CASCADE";

if ($conn->query($sql) === TRUE && $conn->query($sql1) === TRUE) {
    echo "Table created and altered successfully";
} else {
    echo "Error creating table: " . $conn->error;
}
?>