<html>
<head>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<title>Macro Prep Test Suite</title>
	<style>
		h2{ margin-bottom:0px;}
		p{  margin-top:8px;}
		a{  color:blue !important;}
	</style>
</head>

<body>
	<h1>Macro Prep Test Suite</h1>

	<h2>Non-Negative Least Squares Algorithm</h2>
	<p><a href="nnlsTest.php">test random NNLS optimization problems</a><br>(may take a moment to load)</p>

	<h2>Food Choosing System</h2>
	<p>
		<form action="../MealBuilder.php" method="POST" id="foodChooser">
			<input type="hidden" name="protein" value="100"/>
			<input type="hidden" name="carb" value="100"/>
			<input type="hidden" name="fat" value="100"/>
			<input type="hidden" name="mealQty" value="3"/>
		</form>
		<a href="javascript:$('#foodChooser').submit();">test food selection page</a>
	</p>

	<h2>Calorie and Macro Recommendations</h2>
	<p><a href="../HealthFunctions.php">test recommendation functions</a></p>
<body>

</html>
