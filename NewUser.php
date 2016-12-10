<?php
require_once('./inc/serverConnect.php');

$user = $_POST['username'];
$password = $_POST['password'];
$query = "SELECT user_id FROM Users";
$insert = "INSERT INTO Users (user_id,user_pw) VALUES ('".$user."','".$password."')";
$continue = 1;
$result = $conn->query($query);

echo "<html>
			<head><link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'></head>
				<body>";
if($result->num_rows > 0){
	while($row = $result->fetch_assoc()){
		if($row['user_id'] == $user || $user == null){
			echo "<p>User already exists.</p><br>
								<a href='https://people.eecs.ku.edu/~hcrisp/MacroPrepFinal/NewUser.html' class='btn btn-info' role='button'>Try again</a>";
			$continue = 0;
			return;
		}
	}
}
if($result->num_rows == 0 || $continue == 1){
	$conn->query($insert);
	echo "<p>User '".$user."' has been created. Click 'Home' and log in with your username and password. </p><br>
				<a href='https://people.eecs.ku.edu/~hcrisp/MacroPrepFinal/index.html' class='btn btn-info' role='button'>Home</a>";
}
echo "</body>
		</html>";
/* close connection */
$conn->close();
?>
