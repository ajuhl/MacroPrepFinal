<!--
@file MealBuilder.php
@date 11/1/2016
@brief Lets the user choose food for each meal
-->

<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once('./inc/serverConnect.php');
require_once('nnls.php');
?>

<html>

<head>
	<link href="css/style.css" rel="stylesheet"/>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<link href="css/select2.min.css" rel="stylesheet" />
	<script src="js/select2.min.js"></script>
	<script src="js/MealBuilder2.js"></script>
</head>

<body>

<form action="MealBuilder2.php" action="POST">
<?php
$totalProtein = $_POST['protein'];
$totalCarb = $_POST['carb'];
$totalFat = $_POST['fat'];
$mealQty = $_POST['mealQty'];
$currProteinTotal = 0;
$currCarbTotal = 0;
$currFatTotal = 0;

for($m=1;$m<=$mealQty;$m++){
$curMealProtein = $_POST['m'.$m.'protein'];
$curMealCarb = $_POST['m'.$m.'carb'];
$curMealFat = $_POST['m'.$m.'fat'];

$foods = array();
$foodNutrients = array();
$foodIndex=1;
$mealIsEmpty=true;
while(isset($_POST['m'.$m.'food'.$foodIndex])){
	$foodID = $_POST['m'.$m.'food'.$foodIndex];
	$result = $conn->query('SELECT * FROM `foods` WHERE `id`='.$foodID);
	$row = $result->fetch_assoc();
	$foods[$foodIndex-1] = $row;
	$foodNutrients[$foodIndex-1] = array($row['protein'],$row['carb'],$row['fat']);
	$foodIndex++;
	$mealIsEmpty=false;
}

if($mealIsEmpty==false){
	$macroGoals = array(array($curMealProtein),array($curMealCarb),array($curMealFat));

	$foodNutrients = new Math_Matrix($foodNutrients);
	$foodNutrients->transpose();
	$foodNutrients = $foodNutrients->getData();

	$calc = new nnls($foodNutrients,$macroGoals);
	$servingSizes = $calc->get_x();
	$servingSizes->transpose();
	$servingSizes = $servingSizes->getData()[0];
}
?>
	<div class="meal">
		<h1>Meal <?php echo $m; ?></h1>
		<table>
		<tr>
		 <td><h2>Protein:</h2></td>
		 <td><div class="macroDisplay" goal="<?php echo $curMealProtein?>"></div></td>
		</tr>
		<tr>
		 <td><h2>Carbs:</h2></td>
		 <td><div class="macroDisplay" goal="<?php echo $curMealCarb?>"></div></td>
		</tr>
		<tr>
		 <td><h2>Fat:</h2></td>
		 <td><div class="macroDisplay" goal="<?php echo $curMealFat?>"></div></td>
		</tr>
		</table>
		<div class="foodWrapper">
			<?php
			for($x=0;$x<$foodIndex-1;$x++){
			?>
				<div class="servingContainer">
					<?php echo $foods[$x]['name'].'<br>';?>
					<input type="range" class="servingSlider"
						meal="<?php echo $m ?>"
						id="<?php echo $foods[$x]['id']?>"
						protein="<?php echo $foods[$x]['protein']?>"
						carb="<?php echo $foods[$x]['carb']?>"
						fat="<?php echo $foods[$x]['fat']?>"
						serving="<?php echo $foods[$x]['measure']?>"
						value="<?php echo $servingSizes[$x] ?>"
						min="0" max="15" step="0.01"/>
					<div class="servingDisplay"></div>
				</div>
			<?php
			}
			?>
		</div>
	</div>

<?php

}
?>

</form>
<div class="goals">
	<h1>Protein</h1>
		<div id="proteinGoal" class="goal green">Current Total: <?php echo $currProteinTotal; ?>g</div><div class="goal">Daily Goal: <?php echo $totalProtein; ?>g</div>
	<h1>Carbs</h1>
		<div id="carbGoal" class="goal green">Current Total: <?php echo $currCarbTotal; ?>g</div><div class="goal">Daily Goal: <?php echo $totalCarb; ?>g</div>
	<h1>Fat</h1>
		<div id="fatGoal" class="goal green">Current Total: <?php echo $currFatTotal; ?>g</div><div class="goal">Daily Goal: <?php echo $totalFat; ?>g</div>
</div>
</body>

</html>
