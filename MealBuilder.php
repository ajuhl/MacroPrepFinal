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
  echo '<select class="js-example-basic-single">';
  $result = $conn->query('SELECT `name`,`id` FROM `foods` WHERE `polarization`=\'c\'');
  while($row = $result->fetch_assoc()){
     echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
  }
  echo '</select>';

  echo ("Carbs: $carbArr[$i]g<br>");


  echo ("Fats: $fatArr[$i]g<br><br><br>");


}

?>
