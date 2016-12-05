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

<form action="MealBuilder2.php" action="POST">
<?php
$totalProtein = $_POST['protein'];
$totalCarb = $_POST['carb'];
$totalFat = $_POST['fat'];
$mealQty = $_POST['mealQty'];

for($m=1;$m<=$mealQty;$m++){
$curMealProtein = $_POST['m'.$m.'protein'];
$curMealCarb = $_POST['m'.$m.'carb'];
$curMealFat = $_POST['m'.$m.'fat'];
?>
	<div class="meal">
		<h1>Meal <?php echo $m; ?></h1>
		<table>
		<tr>
		 <td><h2>Protein:</h2></td>
		 <td><div class="macroDisplay"><?php echo $curMealProtein; ?>g</div></td>
		</tr>
		<tr>
		 <td><h2>Carbs:</h2></td>
		 <td><div class="macroDisplay"><?php echo $curMealCarb; ?>g</div></td>
		</tr>
		<tr>
		 <td><h2>Fat:</h2></td>
		 <td><div class="macroDisplay"><?php echo $curMealFat; ?>g</div></td>
		</tr>
		</table>
		<div class="foodWrapper">
			<?php
			$foodIndex=1;
			while(isset($_POST['m'.$m.'food'.$foodIndex])){
				$foodID = $_POST['m'.$m.'food'.$foodIndex];
				$result = $conn->query('SELECT `name` FROM `foods` WHERE `id`='.$foodID);
				$row = $result->fetch_assoc();?>
				<div class="servingContainer">
					<?php echo $row['name'].'<br>';?>
					<input type="range" class="servingSlider"/>
				</div>
				<?php
				$foodIndex++;
			}
			?>
		</div>
	</div>
<?php
}
?>

</form>

</body>

</html>
