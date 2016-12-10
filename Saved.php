<?php
require_once('./inc/serverConnect.php');

$user = $_COOKIE['user'];
$query= "SELECT name,doc FROM Meals WHERE user_id = '".$user."'";
$result = $conn->query($query);
echo "<html>

			<head><link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'></head>
			<style>
				table, th, td {
					border: 1px solid black;
					border-collapse: collapse;
				}
				th, td {
					padding: 5px;
				}
				th {
					text-align: left;
				}
				</style>
				<body>	";

if($result->num_rows > 0){
	echo "<table>
						<tr>
							<th>MacroPrep</th>
							<th>Details</th>
						</tr>";
	while($row = $result->fetch_assoc()){
		echo "
						<tr>
							<td>".$row['name']."</td>
							<td>".$row['doc']."</td>
						</tr>";							
	}
	echo	 "</table>
				</body>
			</html>";
}
if($result->num_rows == 0){
	 echo "<p>0 results</p>";
}
echo "<br><a href='https://people.eecs.ku.edu/~ajuhl/eecs448/Final/MealPlanner.html' class='btn btn-info' role='button'>Input Macros</a>
				<a href='https://people.eecs.ku.edu/~ajuhl/eecs448/Final/HealthFunctions.php' class='btn btn-info' role='button'>Calculate Macros</a>";
$conn>close();
?>