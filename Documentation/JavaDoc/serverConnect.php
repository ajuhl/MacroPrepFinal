<?php
/**
* connect to a database
* @param $servername the name of the server you want to connect to
* @param $username the userneame for the database
* @param $password the password for the database
* @param $db_name the name of the database to connect to
* \cite learned from w3schools.com at one point time
*/
$servername = "mysql.eecs.ku.edu";
$username = "sdiffin";
$password = "Password123!";
$db_name = "sdiffin";

//create a connection to database
$conn = mysqli_connect($servername, $username, $password, $db_name);

//test connection
if($conn->connect_error){
	die("connection failed: " . $conn->connect_error);
}

?>
