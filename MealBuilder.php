<?php

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
    if($diff != 0){
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

for($i = 0; $i < $meals; $i++)
{
  echo ("Meal ".($i+1)."<br>");
  echo ("Protein: $proteinArr[$i]g<br>");
  echo ("Carbs: $carbArr[$i]g<br>");
  echo ("Fats: $fatArr[$i]g<br><br><br>");
}

?>
