<?php
require_once('./inc/serverConnect.php');

$user = $_POST['username'];
$password = $_POST['password'];
$query = "SELECT user_id FROM Users";
$insert = "INSERT INTO Users (user_id,user_pw) VALUES ('".$user."','".$password."')";
$continue = 1;
$result = $conn->query($query);

echo "<!DOCUMENT>
<html>
<style>

.macro {
	top: 300px;
	left: 700px;
	margin: auto;
	border-radius: 15px 50px 30px;
	padding: 20px;
	border: 2px solid black;
	width: 500px;
	box-shadow: 1px 2px 4px rgba(0, 0, 0, .5);
	background: linear-gradient(135deg, rgba(252,227,0,1) 0%, rgba(255,242,173,1) 52%, rgba(252,227,0,1) 100%);
}

h1{
	margin: auto;
  margin-bottom: 20px;
  border-radius: 25px;
  border: 2px solid rgb(230, 230, 0);
  background: linear-gradient(to bottom, rgba(0,0,0,1) 0%,rgba(25,25,25,1) 10%,rgba(25,25,25,1) 70%,rgba(51,51,51,1) 85%,rgba(102,102,102,1) 100%);
  padding: 20px;
  width: 450px;
  height: 50px;
  font-size: 40px;
  text-align: center;
  color: rgba(252,227,0,1);
  border: 2px solid black;
}

.submit {
  background-color: black;
  color: rgba(252,227,0,1);
  border-radius: 2px;
  -webkit-transition-duration: 0.4s;
  transition-duration: all 0.4s;
  border: 1px solid black;
  padding: 16px 16px;
  text-align: center;
  cursor: pointer;
  padding: 10px;
}

input[type=text] {
  width: 200px;
  height: 25px;
  -webkit-transition: width 0.4s, height 0.4s;
  transition: width 0.4s, height 0.4s;
  border: 2px solid black;
  text-align: center;
  margin: 5px;
}
input[type=password] {
  width: 200px;
  height: 25px;
  -webkit-transition: width 0.4s, height 0.4s;
  transition: width 0.4s, height 0.4s;
  border: 2px solid black;
  text-align: center;
  margin: 5px;
}

</style>
	<head>
		<title>MacroPrep</title>
	</head>
	<body>
		<div class='macro'>
		<h1>MacroPrep</h1>";
if($result->num_rows > 0){
	while($row = $result->fetch_assoc()){
		if($row['user_id'] == $user || $user == null){
			echo "<p>User already exists.</p><br>
								<form action='index.html'>
									<input type='submit'  class='submit' value='Try again'>
								</form>";
			$continue = 0;
			return;
		}
	}
}
if($result->num_rows == 0 || $continue == 1){
	$conn->query($insert);
	header('location: index.html');
}
echo "</body>
		</html>";
/* close connection */
$conn->close();
?>
