<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once('serverConnect.php');
global $conn;

$protein = $_POST['protein'];
$carb = $_POST['carb'];
$fat = $_POST['fat'];
$meals = $_POST['meals'];

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

for($i=0; $i<intval($_POST['meals']); $i++){
	//total goals for i-th meal
	echo '<input id="m'.$i.'protein" type="hidden" value="'.$proteinArr[$i].'">';
	echo '<input id="m'.$i.'carb" type="hidden" value="'.$carbArr[$i].'">';
	echo '<input id="m'.$i.'fat" type="hidden" value="'.$fatArr[$i].'">';
	
	//macronutrient values for protein selected
	$proteinID = $_POST['m'.$i.'protein'];
	$presult = $conn->query('SELECT `protein`,`fat`,`carbs` FROM foods WHERE `id`='.$proteinID);
	$row = $presult->fetch_row();
	echo '<input id="m'.$i.'proteinp" type="hidden" value="'.$row[0].'">';
	echo '<input id="m'.$i.'proteinc" type="hidden" value="'.$row[1].'">';
	echo '<input id="m'.$i.'proteinf" type="hidden" value="'.$row[2].'">';
	
	//macronutrient values for carb selected
	$carbID = $_POST['m'.$i.'carb'];
	$presult = $conn->query('SELECT `protein`,`fat`,`carbs` FROM foods WHERE `id`='.$carbID);
	$row = $presult->fetch_row();
	echo '<input id="m'.$i.'carbp" type="hidden" value="'.$row[0].'">';
	echo '<input id="m'.$i.'carbc" type="hidden" value="'.$row[1].'">';
	echo '<input id="m'.$i.'carbf" type="hidden" value="'.$row[2].'">';
	
	//macronutrient values for fat selected
	$fatID = $_POST['m'.$i.'fat'];
	$presult = $conn->query('SELECT `protein`,`fat`,`carbs` FROM foods WHERE `id`='.$fatID);
	$row = $presult->fetch_row();
	echo '<input id="m'.$i.'fatp" type="hidden" value="'.$row[0].'">';
	echo '<input id="m'.$i.'fatc" type="hidden" value="'.$row[1].'">';
	echo '<input id="m'.$i.'fatf" type="hidden" value="'.$row[2].'">';
	
	echo PHP_EOL;
}

?>