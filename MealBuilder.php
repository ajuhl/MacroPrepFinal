<!--
@file MealBuilder.php
@date 11/1/2016
@brief Lets the user choose food for each meal
-->

<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once('./inc/serverConnect.php');
?>

<html>

<head>
	<link href="css/style.css" rel="stylesheet"/>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<link href="css/select2.min.css" rel="stylesheet" />
	<script src="js/select2.min.js"></script>
	<script src="js/MealBuilder.js"></script>
</head>

<body>

<form action="MealPlan.php" action="POST">
<?php
$totalProtein = $_POST['protein'];
$totalCarb = $_POST['carb'];
$totalFat = $_POST['fat'];
$mealQty = $_POST['mealQty'];

echo '<input type="hidden" name="protein" value="'.$totalProtein.'">';
echo '<input type="hidden" name="carb" value="'.$totalCarb.'">';
echo '<input type="hidden" name="fat" value="'.$totalFat.'">';
echo '<input type="hidden" name="meals" value="'.$mealQty.'">';

function nutrientDivision($nutrientTotal, $mealQty){
  $individual = floor($nutrientTotal/$mealQty);
  $total = $individual * $mealQty;
  $leftOver = $nutrientTotal - $total;

  for($i=0;$i<$mealQty;$i++){
    $nutrientDivided[$i] = $individual;
    if($leftOver != 0){
      $nutrientDivided[$i] = $nutrientDivided[$i]+1;
      $leftOver--;
    }
  }
  return $nutrientDivided;
}

$proteinPerMeal = nutrientDivision($totalProtein,$mealQty);
$carbPerMeal = nutrientDivision($totalCarb,$mealQty);
$fatPerMeal = nutrientDivision($totalFat,$mealQty);
for($m=1;$m<=$mealQty;$m++){
?>
	<div class="meal">
		<h1>Meal <?php echo $m; ?></h1>
		<table>
		<tr>
		 <td><h2>Protein:</h2></td>
		 <td><input type="range" class="macroSlider" max="<?php echo $totalProtein; ?>" value="<?php echo $proteinPerMeal[$m-1]; ?>"/></td>
		 <td><div class="macroDisplay"></div></td>
		</tr>
		<tr>
		 <td><h2>Carbs:</h2></td>
		 <td><input type="range" class="macroSlider" max="<?php echo $totalCarb; ?>" value="<?php echo $carbPerMeal[$m-1]; ?>"/></td>
		 <td><div class="macroDisplay"></div></td>
		</tr>
		<tr>
		 <td><h2>Fat:</h2></td>
		 <td><input type="range" class="macroSlider" max="<?php echo $totalFat; ?>" value="<?php echo $fatPerMeal[$m-1]; ?>"/></td>
		 <td><div class="macroDisplay"></div></td>
		</tr>
		</table>
		<div class="foodWrapper">
			<div class="foodContainer">
				
			</div>
			<input class="addFood" type="button" value="+"/>
		</div>
	</div>
<?php
}
?>
	<div class="goals">
		<h1>Protein</h1>
			<div id="proteinGoal" class="goal"></div><div class="goal">Daily Goal: <?php echo $totalProtein; ?>g</div>
		<h1>Carbs</h1>
			<div id="carbGoal" class="goal"></div><div class="goal">Daily Goal: <?php echo $totalCarb; ?>g</div>
		<h1>Fat</h1>
			<div id="fatGoal" class="goal"></div><div class="goal">Daily Goal: <?php echo $totalFat; ?>g</div>
	</div>
</form>

</body>

</html>
