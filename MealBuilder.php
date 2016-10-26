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
	</style>
</head>

<body>

<form action="MealPlan.php" method="POST">

<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
$protein = $_POST['protein'];
$carb = $_POST['carbs'];
$fat = $_POST['fats'];
$meals = $_POST['numberMeals'];

//hidden value fields to pass this data into MealPlan.php
echo '<input type="hidden" name="protein" value="'.$protein.'">';
echo '<input type="hidden" name="carb" value="'.$carb.'">';
echo '<input type="hidden" name="fat" value="'.$fat.'">';
echo '<input type="hidden" name="meals" value="'.$meals.'">';

/*$protein = 252;
$carb = 137;
$fat = 37;
$meals = 4;*/

function nutrientArray($nutrient, $meals){
  $part = $nutrient/$meals;
  $part = floor($part);
  $whole = $part * $meals;
  $dif= $nutrient - $whole;

  for($i=0;$i<$meals;$i++){
    $arr[$i] = $part;
    if($dif != 0){
      $arr[$i] = $arr[$i]+1;
      $dif--;
    }
  }
  return $arr;
}
//calculate nutrient values for each meal based on daily goal and number of meals
$proteinArr = nutrientArray($protein,$meals);
$carbArr = nutrientArray($carb,$meals);
$fatArr = nutrientArray($fat,$meals);

/*print_r($proteinArr);
echo "<br>";
print_r ($carbArr);
echo "<br>";
print_r ($fatArr);*/

require_once('serverConnect.php');


global $conn;

for($i = 0; $i < $meals; $i++)//for each meal
{
  echo ("Meal ".($i+1)."<br>");
  
  //display goal for protein
  echo ("Protein: $proteinArr[$i]g<br>");
  //list all mainly protein foods from database into select box
  echo '<select class="js-example-basic-single" name="m'.$i.'protein">';
  $result = $conn->query('SELECT `name`,`id` FROM `foods` WHERE `polarization`=\'p\'');
  while($row = $result->fetch_assoc()){
     echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
  }
  echo '</select><br>';

  //display goal for carbs
  echo ("Carbs: $carbArr[$i]g<br>");
  //list all mainly carb foods from database
  echo '<select class="js-example-basic-single" name="m'.$i.'carb">';
  $result = $conn->query('SELECT `name`,`id` FROM `foods` WHERE `polarization`=\'c\'');
  while($row = $result->fetch_assoc()){
     echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
  }
  echo '</select><br>';

  //display goal for fat
  echo ("Fats: $fatArr[$i]g<br>");
  //list all mainly fat foods from database
  echo '<select class="js-example-basic-single" name="m'.$i.'fat">';
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
