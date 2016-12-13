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
		td{
			vertical-align:top;
			border:1px solid #aaa;
			padding:8px;
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
				<?php
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
				?>
			</td>
			<td>
				<?php
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
				?>
			</td>
		</tr>
	</table>
</body>

</html>
