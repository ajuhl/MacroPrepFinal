<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<link href="select2.min.css" rel="stylesheet" />
	<script src="select2.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function() {
	  $(".js-example-basic-single").select2({
			placeholder: "Select a food",
			allowClear: true
			});
	});
	</script>
	<style>
		select,.select2{
			width:500px !important;
		}
	</style>
</head>

<body>

<form action="MealPlan.php" method="POST">

<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
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

require_once('serverConnect.php');


global $conn;

for($m = 0; $m < $mealQty; $m++)
{
  echo ("<h2>Meal ".($m+1)." Macro's</h2>");
  echo "<h4>Protein: ".$proteinPerMeal[$m]."g, Carbs: ".$carbPerMeal[$m]."g, Fats: ".$fatPerMeal[$m]."g</h4>";
  echo ("Protein Selection<br>");
  echo '<select class="js-example-basic-single" name="m'.$m.'f0">';
  $result = $conn->query('SELECT `name`,`id` FROM `foods` WHERE `polarization`=\'p\'');
  while($row = $result->fetch_assoc()){
     echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
  }
  echo '</select><br>';

  echo ("Carb Selection<br>");
  echo '<select class="js-example-basic-single" name="m'.$m.'f1">';
  $result = $conn->query('SELECT `name`,`id` FROM `foods` WHERE `polarization`=\'c\'');
  while($row = $result->fetch_assoc()){
     echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
  }
  echo '</select><br>';

  echo ("Fat Selection<br>");
  echo '<select class="js-example-basic-single" name="m'.$m.'f2">';
  $result = $conn->query('SELECT `name`,`id` FROM `foods` WHERE `polarization`=\'f\'');
  while($row = $result->fetch_assoc()){
     echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
  }
  echo '</select><br><br>';

}

?>

<input type="submit" value="Submit"></input>

</form>

</html>
