<html>
	<head>
		<title>NNLS Random Tester</title>
		<style>
			.food{ margin:6px 0px;}
		</style>
	</head>

<body>

	<p>Refresh the page to load 3 new random problems.</p>

<?php

function scaleServingSize($original,$servings){
	$originalAmount = floatval($original);
	$measure = str_replace('"',"&quot;",substr($original,strpos($original,' ')));
	$newAmount =  number_format($servings*$originalAmount, 2, '.', ',');
	return ($newAmount.$measure);
}

error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once('../inc/serverConnect.php');
require_once('../nnls.php');

for($meal=1;$meal<=3;$meal++){
	echo '<h3>Random Meal '.$meal.'</h3>';
	$proteinGoal = rand(150,250);
	$carbGoal = rand(150,250);
	$fatGoal = rand(50,100);
	echo '<p>Random Macro Goals:<br>';
	echo 'Protein: '.$proteinGoal.'<br>';
	echo 'Carbs: '.$carbGoal.'<br>';
	echo 'Fat: '.$fatGoal.'</p>';
	$macroGoals = array(array($proteinGoal),array($carbGoal),array($fatGoal));

	$foodNutrients = array();
	$foods = array();
	$numFoods = rand(2,7);
	for($food=0;$food<$numFoods;$food++){
		$result = $conn->query('SELECT * FROM foods ORDER BY RAND() LIMIT 1');
		$row = $result->fetch_assoc();
		$foods[$food] = $row;
		$foodNutrients[$food] = array($row['protein'],$row['carb'],$row['fat']);
	}
	$foodNutrients = new Math_Matrix($foodNutrients);
	$foodNutrients->transpose();
	$foodNutrients = $foodNutrients->getData();

	$calc = new nnls($foodNutrients,$macroGoals);
	$servingSizes = $calc->get_x();
	$servingSizes->transpose();
	$servingSizes = $servingSizes->getData()[0];
	
	echo '<hr>Random Foods: (servings optimized)';
	$protein=0;
	$carb=0;
	$fat=0;
	for($food=0;$food<$numFoods;$food++){
		if($servingSizes[$food]>0){
			echo '<p class="food">'.$foods[$food]['name'].'<br>';
			echo scaleServingSize($foods[$food]['measure'],$servingSizes[$food]).'</p>';
			$protein += $foods[$food]['protein'] * $servingSizes[$food];
			$carb    += $foods[$food]['carb'] * $servingSizes[$food];
			$fat     += $foods[$food]['fat'] * $servingSizes[$food];		
		}
	}

	echo '<hr><p>Resulting Macros:<br>';
	echo 'Protein: '.round($protein).'<br>';
	echo 'Carbs: '.round($carb).'<br>';
	echo 'Fat: '.round($fat).'</p><hr>';
}

?>

</body>

</html>
