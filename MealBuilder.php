<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<link href="select2.min.css" rel="stylesheet" />
	<script src="select2.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function() {
	  $(".js-example-basic-single").select2({placeholder:'Select a food'});
	});
	</script>
	<style>
		select,.select2{
			width:500px !important;
		}
	</style>
</head>

<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
$protein = $_POST['protein'];
$carb = $_POST['carbs'];
$fat = $_POST['fats'];
$meals = $_POST['numberMeals'];

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

for($i = 0; $i < $meals; $i++)
{
  echo ("Meal ".($i+1)."<br>");

  echo ("Protein: $proteinArr[$i]g<br>");
  echo '<select class="js-example-basic-single" name="m'.$i.'protein">';
  $result = $conn->query('SELECT `name`,`id` FROM `foods` WHERE `polarization`=\'p\'');
  while($row = $result->fetch_assoc()){
     echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
  }
  echo '</select><br>';

  echo ("Carbs: $carbArr[$i]g<br>");
  echo '<select class="js-example-basic-single" name="m'.$i.'carb">';
  $result = $conn->query('SELECT `name`,`id` FROM `foods` WHERE `polarization`=\'c\'');
  while($row = $result->fetch_assoc()){
     echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
  }
  echo '</select><br>';

  echo ("Fats: $fatArr[$i]g<br>");
  echo '<select class="js-example-basic-single" name="m'.$i.'fat">';
  $result = $conn->query('SELECT `name`,`id` FROM `foods` WHERE `polarization`=\'f\'');
  while($row = $result->fetch_assoc()){
     echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
  }
  echo '</select><br><br>';

}

?>
