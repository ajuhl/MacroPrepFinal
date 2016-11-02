<!--
@file MealPlan.php
@date 11/1/2016
@brief Meal by meal plan constructed based on user inputs
-->

<head>
  <style>
    table, th, tr, td {
      margin: auto;
      border: 1px solid black;
      border-collapse: collapse;
    }
    th, tr, td {
      padding: 5px;
    }
    th {
      text-align: left;
      color: rgba(252,227,0,1);
      background: linear-gradient(to bottom, rgba(0,0,0,1) 0%,rgba(25,25,25,1) 10%,rgba(25,25,25,1) 70%,rgba(51,51,51,1) 85%,rgba(102,102,102,1) 100%);
    }
    td {
      font-weight: bold;
    }
    tr:hover{
      background: rgba(204,184,0,1);
    }
    div {
			border-radius: 50px 15px 30px 50px;
			padding: 20px;
			border: 2px solid black;
			width: 550px;
			box-shadow: 1px 2px 4px rgba(0, 0, 0, .5);
			background: linear-gradient(135deg, rgba(252,227,0,1) 0%, rgba(255,242,173,1) 52%, rgba(252,227,0,1) 100%);
    }
    h2 {
    margin: auto;
    margin-bottom: 20px;
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
    h4 {
      margin: auto;
      margin-bottom: 20px;
	    padding: 20px;
	    width: 450px;
	    height: 20px;
	    font-size: 20px;
	    text-align: center;
	    color: black;
    }
  </style>
  </head>
<body>
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
		//	echo "<br>";
				for($x=0; $x<$rowCount; $x++){
		for($y=0; $y<$columnCount; $y++){
		//	echo $matrix[$x][$y]." ";
		}
	//	echo "<br>";
	}
//	echo "<br>";
        }        {
            $lv = $matrix[$r][$lead];
		//	echo ($lv." lead value<br>");
            for ($j = 0; $j < $columnCount; $j++) {
                $matrix[$r][$j] = $matrix[$r][$j] / $lv;
				//echo ($matrix[$r][$j]." divide lead value from ".$r.$j." value<br>");
            }
			//echo "<br>";
					for($x=0; $x<$rowCount; $x++){
		for($y=0; $y<$columnCount; $y++){
		//	echo $matrix[$x][$y]." ";
		}
	//	echo "<br>";
	}
//	echo "<br>";
        }
        for ($i = 0; $i < $rowCount; $i++) {
            if ($i != $r) {
                $lv = $matrix[$i][$lead];

			//	echo ($lv." lead value<br>");
                for ($j = 0; $j < $columnCount; $j++) {
                    $matrix[$i][$j] -= $lv * $matrix[$r][$j];
			//	echo ($matrix[$i][$j]." multiply lead value and ".$r.$j." value<br>");
                }
		//echo "<br>";
		for($x=0; $x<$rowCount; $x++){
		for($y=0; $y<$columnCount; $y++){
	//		echo $matrix[$x][$y]." ";
		}
	//	echo "<br>";
	}
//	echo "<br>";
            }
        }
        $lead++;
    }
//	echo "<br>";
			for($x=0; $x<$rowCount; $x++){
		for($y=0; $y<$columnCount; $y++){
			//echo $matrix[$x][$y]." ";
		}
	//	echo "<br>";
	}
//	echo "<br>";
    return $matrix;

}


//	$m = meal increment
for($m=0; $m<$mealQty; $m++){

	// $f = food increment
  for($f=0; $f<$foodQty; $f++){
    $foodID = $_POST['m'.$m.'f'.$f];
    $presult = $conn->query('SELECT `protein`,`carb`,`fat` FROM `foods` WHERE `id`='.$foodID);
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


 echo "<div><h2>Meal ".($m+1)."</h2>
			<h4>Protein: ".$proteinPerMeal[$m]."g, Carbs: ".$carbPerMeal[$m]."g, Fats: ".$fatPerMeal[$m]."g</h4>
			<table>
				<tr>
					<th>Food</th>
					<th>Serving Size</th>
				</tr>";
 for($f=0; $f<$foodQty; $f++){
    $foodID = $_POST['m'.$m.'f'.$f];
    $presult = $conn->query('SELECT `name`,`measure` FROM `foods` WHERE `id`='.$foodID);
  	$nameMeasure = $presult->fetch_row();
	$measure = $nameMeasure[1];
	$measure = explode(" ", $measure, 2);
	$servingSize = $servings[$f][$foodQty] * $measure[0];
	echo "
				<tr>
					<td>".$nameMeasure[0]."</td>
					<td>".round($servingSize,1,PHP_ROUND_HALF_UP)." ".$measure[1]."</td>
				</tr>";

  }
  echo 	"</table></div><br>";
}

echo PHP_EOL;
?>
</body>
</html>
