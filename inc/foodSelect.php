			<select class="foodSelect">
				<option></option>
<?php
				error_reporting(E_ALL);
				ini_set("display_errors", 1);
				require_once('serverConnect.php');
				$result = $conn->query('SELECT `name`,`id` FROM `foods`');
				while($row = $result->fetch_assoc()){
					echo '				<option value="'.$row['id'].'">'.$row['name'].'</option>'.PHP_EOL;
				}
				?>
			</select>
