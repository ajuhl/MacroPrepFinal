<?php

$protein = $_POST['protein'];
$carb = $_POST['carb'];
$fat = $_POST['fat'];
$meals = $_POST['numberMeals'];

/*$protein = 252;
$carb = 137;
$fat = 37;
$meals = 4;*/

function nutrientArray($nutrient){
  $part = $nutrient/$meals;
  $part = floor($part);
  $whole = $part * $meals;
  $dif= $nutrient - $whole;

  for($i=0;$i<$meals;$i++){
    $arr[$i] = $part;
    if($diff != 0){
      $arr[$i] = $arr[$i]+1;
      $dif--;
    }
  }
  return $arr;
}


$proteinArr = nutrientArray($protein);
$carbArr = nutrientArray($carb);
$fatArr = nutrientArray($fat);

echo $proteinArr."<br>";
echo $carbArr."<br>";
echo $fatArr."<br>";

?>
