<?php
include("includes/connection.php");

$sql="CREATE TABLE co_ordinator(
	cod_id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	fac_id int(11) NOT NULL,
	date_from date,
	cod_name varchar(50),
	cod_status varchar(15)
)AUTO_INCREMENT=10001";

$sql1="ALTER TABLE co_ordinator ADD CONSTRAINT cod_fac_id FOREIGN KEY(Fac_ID) REFERENCES facultydetails(Fac_ID) on UPDATE CASCADE";

if ($conn->query($sql) === TRUE && $conn->query($sql1) === TRUE) {
    echo "Table created and altered successfully";
} else {
    echo "Error creating table: " . $conn->error;
}
?>