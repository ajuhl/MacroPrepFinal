<?php
require_once('./inc/serverConnect.php');

$user = $_POST['username'];
$password = $_POST['password'];
$query = "SELECT user_id,user_pw FROM Users";
$result = $conn->query($query);


echo "<html>
			<head><link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'></head>
				<body>";
if($result->num_rows > 0){
$exists = 0;
	while($row = $result->fetch_assoc()){
		if($row['user_id'] == $user && $row['user_pw'] == $password){
			echo "<p>Welcome Back ".$user."!</p><br>
								<a href='https://people.eecs.ku.edu/~ajuhl/eecs448/Final/MealPlanner.html' class='btn btn-info' role='button'>Input Macros</a>
								<a href='https://people.eecs.ku.edu/~ajuhl/eecs448/Final/HealthFunctions.php' class='btn btn-info' role='button'>Calculate Macros</a>
								<a href='https://people.eecs.ku.edu/~ajuhl/eecs448/Final/Saved.php' class='btn btn-info' role='button'>View Saved MacroPreps</a>";
								$exists = 1;
								setcookie("user", $user, time() + 43200, '/', null, true, true );
			return;
		}
		else if($row['user_id'] == $user && $row['user_pw'] == $password){
			echo "<p>Incorrect username or password<p><br>
							<a href='https://people.eecs.ku.edu/~ajuhl/eecs448/Final/ExistingUser.html' class='btn btn-info' role='button'>Try again</a>";
							$exists = 1;
			return;
		}
	}
	if($exists == 0){
		echo "<p>Incorrect username or password<p><br>
						<a href='https://people.eecs.ku.edu/~ajuhl/eecs448/Final/ExistingUser.html' class='btn btn-info' role='button'>Try again</a>";
	}
}

if($result->num_rows == 0){
	$conn->query($insert);
	echo "<p>Click 'Home' then 'New User' to create an account.</p><br>
				<a href='https://people.eecs.ku.edu/~ajuhl/eecs448/Final/index.html' class='btn btn-info' role='button'>Home</a>";
}
echo "</body>
		</html>";
/* close connection */
$conn->close();
?>