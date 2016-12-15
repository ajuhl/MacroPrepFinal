<!--
@file ShoppingList.php
@date 12/12/2016
@brief Displays Meals and shopping list
-->

<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once('./inc/serverConnect.php');

function scaleServingSize($original,$servings){
	$originalAmount = floatval($original);
	$measure = str_replace('"',"&quot;",substr($original,strpos($original,' ')));
	$newAmount =  number_format($servings*$originalAmount, 2, '.', ',');
	return ($newAmount.$measure);
}
?>

<html>

<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
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
					background:rgba(252,227,0,1);
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


.submit {
  display: inline;
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
form{
	display: inline;
	margin: auto;
	}
.buttons{
	text-align: center;
}

				</style>
</head>

<body>
	<table>
		<tr>
			<th>Daily Meal Plan</th>
			<th>Shopping List</th>
		</tr>
		<tr>
			<td>
				<?php ob_start();
				$foodIndex = 0;
				foreach ($_POST['foodsPerMeal'] as $mealIndex => $numFoods){
					echo '<h3>Meal '.($mealIndex+1).'</h3>';
					for($x=0;$x<$numFoods;$x++){
						if($_POST['servings'][$foodIndex]>0){
							$result = $conn->query('SELECT * FROM `foods` WHERE `id`='.$_POST['foodIDs'][$foodIndex]);
							$row = $result->fetch_assoc();
							echo '<p>'.$row['name'].'</br>';
							echo scaleServingSize($_POST['measures'][$foodIndex],$_POST['servings'][$foodIndex]).'</br></p>';
						}
						$foodIndex++;
					}
					if($numFoods==0){
						echo '<p>You didn\'t select any foods for this meal!</p>';
					}
				}
				$meal = ob_get_flush();
				?>
			</td>
			<td>
				<?php
				ob_start();
				$totalOfFoods = array();
				foreach($_POST['servings'] as $index=>$serving){
					if($serving>0){
						if(!isset($totalOfFoods[$_POST['foodIDs'][$index]])){
							$totalOfFoods[$_POST['foodIDs'][$index]] = 0;
						}
						$totalOfFoods[$_POST['foodIDs'][$index]]+=$serving;
					}
				}
				foreach($totalOfFoods as $foodID=>$serving){
					$result = $conn->query('SELECT * FROM `foods` WHERE `id`='.$foodID);
					$row = $result->fetch_assoc();
					echo '<p>'.$row['name'].'</br>';
					echo scaleServingSize($row['measure'],$serving).' total</br></p>';
				}
				$shopping = ob_get_flush();
				?>
			</td>
		</tr>
	</table>
<?php
$user = $_COOKIE['user'];
$insert = "INSERT INTO Meals (user_id, meal, shopping) VALUES ('".$user."', '".$meal."','".$shopping."')";
$conn->query($insert);
?>
					<div class='buttons'>
								<form action='MealPlanner.html'>
									<input type='submit'  class='submit' value='Input Macros'>
								</form>
								<form action='HealthFunctions.php'>
									<input type='submit'  class='submit' value='Calculate Macros'>
								</form>
								<form action='Saved.php'>
									<input type='submit'  class='submit' value='View Saved MacroPreps'>
								</form>
					</div>
</body>

</html>
