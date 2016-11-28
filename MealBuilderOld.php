<!--
@file MealBuilder.php
@date 11/1/2016
@brief Lets the user choose food for each meal
-->

<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<link href="select2.min.css" rel="stylesheet" />
	<script src="select2.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function() {
	  $(".js-example-basic-single").select2({
			placeholder: 'Select a food'
			});
	});
	</script>
	<style>
		select,.select2{
			width:500px !important;
		}
		div {
			border-radius: 15px 50px 30px;
			padding: 20px;
			border: 2px solid black;
			width: 700px;
			box-shadow: 1px 2px 4px rgba(0, 0, 0, .5);
			background: linear-gradient(135deg, rgba(252,227,0,1) 0%, rgba(255,242,173,1) 52%, rgba(252,227,0,1) 100%);

		}

		h1 {
			border-radius: 25px;
	    border: 2px solid rgb(230, 230, 0);
	    background: linear-gradient(to bottom, rgba(0,0,0,1) 0%,rgba(25,25,25,1) 10%,rgba(25,25,25,1) 70%,rgba(51,51,51,1) 85%,rgba(102,102,102,1) 100%);
	    padding: 20px;
	    width: 450px;
	    height: 50px;
	    font-size: 40px;
	    text-align: center;
	    color: rgba(252,227,0,1);

	    border: 2px solid black;
		}

		h4{
			margin-bottom: 1px;
		}

		input[type=submit]{
			color: rgba(252,227,0,1);

			border-radius: 6px;
			font-size: 20px;
			background-color: black;
			text-align: center;
			cursor: pointer;
			padding: 16px 32px;
			-webkit-transition-duration: 0.4s;
		  transition-duration: all 0.4s;
		}

		input[type=submit]:hover{
		  box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);

		}

		input[type=submit]:active{
			transform: translateY(4px);
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
  $macroTotalPerMeal = $proteinPerMeal[$m]+$carbPerMeal[$m]+$fatPerMeal[$m];
  echo ("<div><h1>Meal ".($m+1)." Macro's</h1>");
  echo "<h3>Protein: ".$proteinPerMeal[$m]."g, Carbs: ".$carbPerMeal[$m]."g, Fats: ".$fatPerMeal[$m]."g</h3>";
  echo ("<h4>Protein Selection</h4>");
  echo '<select class="js-example-basic-single" name="m'.$m.'f0">';
  $proteinRatioPerMeal = $proteinPerMeal[$m] / $macroTotalPerMeal;
  $result = $conn->query('SELECT `name`,`id` FROM `foods` WHERE `polarization`=\'p\' AND `proteinRatio`>'.$proteinRatioPerMeal);
  while($row = $result->fetch_assoc()){
     echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
  }
  echo '</select><br>';

  echo ("<h4>Carb Selection</h4>");
  echo '<select class="js-example-basic-single" name="m'.$m.'f1">';
  $carbRatioPerMeal = $carbPerMeal[$m] / $macroTotalPerMeal;
  $result = $conn->query('SELECT `name`,`id` FROM `foods` WHERE `polarization`=\'c\' AND `carbRatio`>'.$carbRatioPerMeal);
  while($row = $result->fetch_assoc()){
     echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
  }
  echo '</select><br>';

  echo ("<h4>Fat Selection</h4>");
  echo '<select class="js-example-basic-single" name="m'.$m.'f2">';
  $fatRatioPerMeal = $fatPerMeal[$m] / $macroTotalPerMeal;
  $result = $conn->query('SELECT `name`,`id` FROM `foods` WHERE `polarization`=\'f\' AND `fatRatio`>'.$fatRatioPerMeal);
  while($row = $result->fetch_assoc()){
     echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
  }
  echo '</select></div><br><br>';

}

?>

<input type="submit" value="Submit"></input>

</form>

</html>
