<html>
<head>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<title>Macro Prep Test Suite</title>
	<style>
		h2{  margin:40px 0px 0px 0px;}
		p{   margin: 5px;}
		a{   color:blue !important;}
		form{margin:0;}
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
		<p><a href="javascript:$('#foodChooser').submit();">test food selection page</a></p>
	</p>

	<h2>Calorie and Macro Recommendations</h2>
	<p><a href="../HealthFunctions.php">test recommendation functions</a></p>

	<h2>Meal Memory</h2>
	<p>
		search for a user's meals:
		<form action="../Saved.php" method="POST" id="loadMeals">
			<label for="testuser">User ID: </label>
			<input type="text" name="testuser"></input>
			<input type="submit" value="Search"></input>
		</form>
	</p>

	<h2>User System</h2>
	<p>
		add a user:
		<form action="../NewUser.php" method="post" >
			User: <input type="text" name="username" id="username" placeholder="username" required><br>
			Pass:  <input type="text" name="password" id="password" placeholder="password" required><br>
			<input type="hidden" name="test" value="test"/>
			<input type="submit" value="Submit">
		</form>
		<a href="http://mysql.eecs.ku.edu/" target="_blank">(link to check SQL table)</a>
	</p>

<body>

</html>
