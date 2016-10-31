<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once('serverConnect.php');
global $conn;

$totalProtein = $_POST['protein'];
$totalCarb = $_POST['carb'];
$totalFat = $_POST['fat'];
$mealQty = $_POST['meals'];
$foodQty = 3; //will eventually be specific to each meal
$proteinPerMeal = nutrientDivision($totalProtein,$mealQty);
$carbPerMeal = nutrientDivision($totalCarb,$mealQty);
$fatPerMeal = nutrientDivision($totalFat,$mealQty);

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

function rref($matrix)
{
    $lead = 0;
    $rowCount = count($matrix);
    if ($rowCount == 0)
        return $matrix;
    $columnCount = 0;
    if (isset($matrix[0])) {
        $columnCount = count($matrix[0]);
    }
    for ($r = 0; $r < $rowCount; $r++) {
        if ($lead >= $columnCount)
            break;        {
            $i = $r;
            while ($matrix[$i][$lead] == 0) {
                $i++;
                if ($i == $rowCount) {
                    $i = $r;
                    $lead++;
                    if ($lead == $columnCount)
                        return $matrix;
                }
            }
            $temp = $matrix[$r];
            $matrix[$r] = $matrix[$i];
            $matrix[$i] = $temp;
        }        {
            $lv = $matrix[$r][$lead];
            for ($j = 0; $j < $columnCount; $j++) {
                $matrix[$r][$j] = $matrix[$r][$j] / $lv;
            }
        }
        for ($i = 0; $i < $rowCount; $i++) {
            if ($i != $r) {
                $lv = $matrix[$i][$lead];
                for ($j = 0; $j < $columnCount; $j++) {
                    $matrix[$i][$j] -= $lv * $matrix[$r][$j];
                }
            }
        }
        $lead++;
    }
    return $matrix;
}

echo "<html>
			<head>
				<style>
					table, th, tr, td {
						border: 1px solid black;
						border-collapse: collapse;
					}
					th, tr, td {
						padding: 5px;
					}
					th {
						text-align: left;
					}
				</style>
				</head>
				<body>";
//	$m = meal increment
for($m=0; $m<$mealQty; $m++){

	// $f = food increment
  for($f=0; $f<$foodQty; $f++){
    $foodID = $_POST['m'.$m.'f'.$f];
    $presult = $conn->query('SELECT `protein`,`carbs`,`fat` FROM `foods` WHERE `id`='.$foodID);
  	$row = $presult->fetch_row();
	//	$n = nutrient increment
    for($n=0; $n<3; $n++){
      $servings[$n][$f] = $row[$n];
    }
  }
  $servings[0][$foodQty] = $proteinPerMeal[$m];
  $servings[1][$foodQty] = $carbPerMeal[$m];
  $servings[2][$foodQty] = $fatPerMeal[$m];
  
  $servings = rref($servings);
  
 echo "<h2>Meal ".($m+1)."</h2>
			<h4>Protein: ".$proteinPerMeal[$m]."g, Carbs: ".$carbPerMeal[$m]."g, Fats: ".$fatPerMeal[$m]."g</h4>
			<table>
				<tr>
					<th>Food</th>
					<th>Serving Size (g)</th>
				</tr>";
 for($f=0; $f<$foodQty; $f++){
    $foodID = $_POST['m'.$m.'f'.$f];
    $presult = $conn->query('SELECT `name` FROM `foods` WHERE `id`='.$foodID);
  	$name = $presult->fetch_row();
	echo "
				<tr>
					<td>".$name[0]."</td>
					<td>".round($servings[$f][$foodQty],0,PHP_ROUND_HALF_UP)."</td>
				</tr>";

  }
  echo 	"</table><br>";
}

echo "</body>
	</html>";

echo PHP_EOL;
?>
