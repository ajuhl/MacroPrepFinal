<?php
require_once('./inc/serverConnect.php');

$user = $_COOKIE['user'];
$query= "SELECT name,doc FROM Meals WHERE user_id = '".$user."'";
$result = $conn->query($query);
echo "<html>

			<style>
				table, th, td {
					border: 1px solid black;
					border-collapse: collapse;
					margin:auto;
				}
				th, td {
					padding: 5px;
				}
				th {
					margin-bottom: 20px;
					border: 2px solid rgb(230, 230, 0);
					background: linear-gradient(to bottom, rgba(0,0,0,1) 0%,rgba(25,25,25,1) 10%,rgba(25,25,25,1) 70%,rgba(51,51,51,1) 85%,rgba(102,102,102,1) 100%);
					padding: 10px;
					font-size: 20px;
					text-align: center;
					color: rgba(252,227,0,1);
					border: 2px solid black;
				}
				tr{
					background: rgba(252,227,0,1);
				}
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


form{
	display: inline;
	text-align: center;
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
.buttons{
	text-align: center;
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
echo "<br><div class='buttons'>
								<form action='MealPlanner.html'>
									<input type='submit'  class='submit' value='Input Macros'>
								</form>
								<form action='HealthFunctions.php'>
									<input type='submit'  class='submit' value='Calculate Macros'>
								</form>
					</div>";
$conn>close();
?>
