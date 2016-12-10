<?php

error_reporting(E_ALL&(~E_NOTICE));
session_start();
$calc_mode=1;
?>
<style type="text/css">
.calculator_div
{
	font-family:verdana, arial, sans-serif;
	border:2pt solid black;
	padding:5px;
	width:330px;
	margin:auto;
}

label
{
	display:block;
	float:left;
	width:150px;
}
.label
{
	display:inline;
	float:none;
	width:75px;
	font-size:11px
}
.warning
{
	background:yellow;
	border:1pt solid red;
	padding:5px;
	font-weight:bold;
}

 #table{

	width:100%;

    }

  #row  {
	height:20px;
	width:100%;
    }
.rowheader
{
	padding:5px;
	font-size:14px;
	font-weight:bolder;
	color:white;
    text-align:center;
}

.macro {
	top: 300px;
	left: 700px;
	margin: auto;
	border-radius: 15px 50px 30px;
	padding: 20px;
	border: 2px solid black;
	width: 500px;
	box-shadow: 1px 2px 4px rgba(0, 0, 0, .5);
	background: linear-gradient(135deg, rgba(252,227,0,1) 0%, rgba(255,242,173,1) 52%, rgba(252,227,0,1) 100%);
}

h1{
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

.submit {
  background-color: black;
  color: rgba(252,227,0,1);
  border-radius: 2px;
  -webkit-transition-duration: 0.4s;
  transition-duration: all 0.4s;
  border: 1px solid black;
  padding: 16px 16px;
  text-align: center;
  cursor: pointer;
  padding: 10px;
}

input[type=text] {
  -webkit-transition: width 0.4s, height 0.4s;
  transition: width 0.4s, height 0.4s;
  border: 2px solid black;
  text-align: center;
  margin: 5px;
}

select {
  -webkit-transition: width 0.4s, height 0.4s;
  transition: width 0.4s, height 0.4s;
  border: 2px solid black;
  text-align: center;
  margin: 5px;
}
</style>
<script language="javascript">
function IsNumber(fldId)
{
  var fld=document.getElementById(fldId).value;

  if(isNaN(fld))
  {
		document.getElementById(fldId).value=fld.substring(0, fld.length-1);
		var newvalue=document.getElementById(fldId).value;
		IsNumber(fldId);
  }

  return;
}


function FtToCm(ftfld,infld,savefld)
{
    var ft=document.getElementById(ftfld).value;
    var inch=document.getElementById(infld).value;

    if(!isNaN(ft) && !isNaN(inch))
    {
        var allinch= ft * 12;
        allinch= parseInt(allinch) + parseInt(inch);

        var cm =allinch * 2.54;

        document.getElementById(savefld).value=Math.round(cm);
    }
    else
    {
        document.getElementById("feet").value=ft.substring(0, ft.length-1);
        document.getElementById("inch").value=inch.substring(0, inch.length-1);
    }
    //form2.field.value =lbs;
    //alert(field);
    return;
}

function CmToFt(cm,ftfld,infld)
{
    if(!isNaN(cm))
    {
        var newcm=cm * 0.3937;

        var ft = newcm / 12;
        var remain= newcm % 12;
        var inchs= remain;

        document.getElementById(ftfld).value=Math.round(ft);
        document.getElementById(infld).value=Math.round(inchs);
    }
    else
    {
        document.getElementById("cm").value=cm.substring(0, cm.length-1);
    }
    //form2.field.value =lbs;
    //alert(field);
    return;
}

function KgToLbs(kg,field)
{
    if(!isNaN(kg))
    {
        var lbs= kg * 2.2;
        document.getElementById(field).value=Math.round(lbs);
    }
    else
    {
        document.getElementById("kg").value=kg.substring(0, kg.length-1);
    }
    //form2.field.value =lbs;
    //alert(field);
    return;
}

function LbsToKg(lbs,field)
{
    if(!isNaN(lbs))
    {
        var kg= lbs / 2.2;
        document.getElementById(field).value=Math.round(kg);
    }
    else
    {
        document.getElementById("lbs").value=lbs.substring(0, lbs.length-1);
    }
    return;
}
function validateForm(frm)
{

	age=frm.age.value;
	kg=frm.kg.value;
	cm=frm.cm.value;

	if(age=="" || kg=="" || cm=="" )
	{
    	alert('Error: all fields are required!');
    	return false;
	}

	return;
}

function showHide(fldshow,fldhide,label,labelfld)
{
	var myTextelemShow = document.getElementById(fldshow);
	var myTextelemLabel = document.getElementById(labelfld);
	var myTextelemHide = document.getElementById(fldhide);
	if(myTextelemShow.style.display == 'none')
	{
    	myTextelemShow.style.display = 'inline' ;
    	myTextelemLabel.innerHTML = label;
	}
	if(myTextelemHide.style.display != 'none')
	{
	    myTextelemHide.style.display = 'none';
	}
}
</script>
<?php
if(!empty($_POST['calculator_ok']))
{
	// session storage
	foreach($_POST as $key=>$var) $_SESSION["calc_bmr_".$key]=$var;

	$inch=$_POST["feet"]*12+$_POST["inch"];

    if($_POST["gender"]=='male')
	{
		$BMR= 66.5 + (6.3 * $_POST["lbs"]) + (12.9 * $inch) - (6.8 * $_POST["age"]);
	}
	else
	{
		$BMR= 655 + (4.3 * $_POST["lbs"]) + (4.7 * $inch) - (4.7 * $_POST["age"]);
	}
		$goal = $_POST["goal"];
		$activity = $_POST["activity"];
        $TDEE=$BMR*$activity;
		
		
	            switch ($goal) {
                case "lose":
                    if ($TDEE <= 2000){
						$TDEE = 0.9 * $TDEE;
					}
                    if ($TDEE > 2000){
						$TDEE = 0.8 * $TDEE;
					}
                    $carbs = (0.40 * $TDEE / 4);
                    $protein = (0.40 * $TDEE / 4);
                    $fat = (0.20 * $TDEE / 9);
                    break;
                case "maintain":
                    $carbs = (0.45 * $TDEE / 4);
                    $protein = (0.30 * $TDEE / 4);
                    $fat = (0.25 * $TDEE / 9);
                    break;
                case "gain":
                    $TDEE += 500;
                    $carbs = (0.45 * $TDEE / 4);
                    $protein = (0.30 * $TDEE / 4);
                    $fat = (0.25 * $TDEE / 9);
                    break;
            }
}
?>
<head>
		<title>MacroPrep</title>
	</head>
	<body>
		<div class='macro'>
		<h1>MacroPrep</h1>";
<div class="calculator_div">
	<form method="post" name="form1" onsubmit="return validateForm(this);">
	<p><label>Your age:</label>
					<input type="text" size="7"  name="age" id="age" onkeyup="IsNumber(this.id)" value="<?php echo $_SESSION["calc_bmr_age"];?>" >
	</p>
	<p><label>Gender:</label>
					<input id="gender"  name="gender" type="radio" value="male" <?php if($_SESSION["calc_bmr_gender"]=="male") echo "checked"; else { if(!isset($_SESSION["calc_bmr_gender"])) echo "checked";}?> /> <label style="width:75px;display:inline;float:none;">Male</label>
					<input id="gender"  name="gender" type="radio" value="female" <?php if($_SESSION["calc_bmr_gender"]=="female") echo "checked"; ?>/> <label style="width:75px;display:inline;float:none;">Female</label>

	</p>
	<p><label>Your goal 	weight:</label>
					<input id="weight" name="weight" type="radio" value="lbs" onclick="showHide('lbs','kg','Lbs','labelw');" <?php if($_SESSION["calc_bmr_weight"]=="lbs") echo "checked"; else { if(!isset($_SESSION["calc_bmr_weight"])) echo "checked";}?> />
					<label style="width:75px;display:inline;float:none;">lbs</label>
					<input id="weight"  name="weight" type="radio" value="kg" onclick="showHide('kg','lbs','kg','labelw');" <?php if($_SESSION["calc_bmr_weight"]=="kg") echo "checked"; ?> />
					<label style="width:75px;display:inline;float:none;">kg</label>

	</p>
		<p><label >&nbsp;</label>
					<input type="text" name="lbs" id="lbs" size="4" onkeyup="LbsToKg(this.value,'kg');" value="<?php echo $_SESSION["calc_bmr_lbs"];?>">
					<input type="text" name="kg" id="kg" size="4" onkeyup="KgToLbs(this.value,'lbs');" style="display:none;" value="<?php echo $_SESSION["calc_bmr_kg"]; ?>">

					<span id="labelw">
					<?php if($_SESSION["calc_bmr_weight"]=="kg"):?>
							kg
    						<SCRIPT LANGUAGE="javascript">
    						showHide('kg','lbs','kg','labelw');
                            </SCRIPT>
                    <?php else:?>lbs<?php endif;?>
					</span>
	</p>


	<p><label>Your height:</label>
					<input id="height" name="height" type="radio" value="feet" onclick="showHide('feet','cm','ft/in','labelh');showHide('inch','cm','ft/in','labelh');" <?php if($_SESSION["calc_bmr_height"]=="feet") echo "checked"; else { if(!isset($_SESSION["calc_bmr_heigth"])) echo "checked";}?> />
					<label style="width:75px;display:inline;float:none;">ft/in</label>
					<input id="height"  name="height" type="radio" value="cm" onclick="showHide('cm','feet','cm','labelh');showHide('cm','inch','cm','labelh');" <?php if($_SESSION["calc_bmr_height"]=="cm") echo "checked"; ?> />
					<label style="width:75px;display:inline;float:none;">cm</label>
					

	</p>
		<p><label >&nbsp;</label>
					<input type="text" name="feet" id="feet" size="4" onkeyup="IsNumber(this.id);FtToCm('feet','inch','cm');" style="display:inline;" value="<?php echo $_SESSION["calc_bmr_feet"]; ?>">
					<input type="text" name="inch" id="inch" size="4" onkeyup="IsNumber(this.id);FtToCm('feet','inch','cm');" style="display:inline;" value="<?php echo $_SESSION["calc_bmr_inch"]; ?>">
					<input type="text" name="cm" id="cm" size="4" onkeyup="IsNumber(this.id);CmToFt(this.value,'feet','inch');" style="display:none;" value="<?php echo $_SESSION["calc_bmr_cm"];?>">
					<span id=labelh >
					<?php if($_SESSION["calc_bmr_height"]=="feet"):?>
					feet/inch
					<SCRIPT LANGUAGE="javascript">
					showHide('feet','cm','feet/inch','labelh');
                    showHide('inch','cm','feet/inch','labelh');
					</SCRIPT>
					<?php else:?>ft/in<?php endif;?>
                   </span>
	</p>

  <p><label>Goal:</label> <select name="goal" id="goal" required>
	<option selected disabled style='display: inline' value> -- select a Goal--</option>
    <option value="lose" <?php if (isset($goal) && $goal=="lose") echo "selected";?>>Lose Fat</option>
    <option value="maintain" <?php if (isset($goal) && $goal=="maintain") echo "selected";?>>Maintain</option>
    <option value="gain" <?php if (isset($goal) && $goal=="gain") echo "selected";?>>Gain Muscle</option>
    </select></p>
	
  <p><label>Activity Level:</label> <select name="activity" id="activity" required>
	<option selected disabled style='display: inline' value> -- select an Activity Level --</option>
	<option value="1" <?php if (isset($activity) && $activity=="1") echo "selected";?>>No sport/exercise</option>
    <option value="1.1" <?php if (isset($activity) && $activity=="1.1") echo "selected";?>>Light activity (sport 1-3 times per week)</option>
    <option value="1.2" <?php if (isset($activity) && $activity=="1.2") echo "selected";?>>Moderate activity (sport 3-5 times per week)</option>
    <option value="1.3" <?php if (isset($activity) && $activity=="1.3") echo "selected";?>>High activity (everyday exercise)</option>
    <option value="1.5" <?php if (isset($activity) && $activity=="1.5") echo "selected";?>>Extreme activity (twice per day exercise)</option>
    </select></p>
	


	<div style="text-align:center;clear:both;">
	<input type="submit" class='submit' value="Calculate!"></div>
	<input type="hidden" name="calculator_ok" value="1">
	</form>


<?php if(!empty($_POST['calculator_ok'])):?>
    <div id="table">
    	<div class="rowheader" style="background-color:black;">
    					BMR : <?php echo number_format($BMR); ?> calories/day<br>
						TDEE : <?php echo number_format($TDEE); ?> calories/day
    	</div>
        <?php if($calc_mode):?>
		<form action="MealPlanner.html" method="post">
        <div class="rowheader" style="background-color:black;">
						<p>Protein: <?php echo number_format($protein);?>g per day</p>
						<input type='hidden' name='protein' value='<?php echo number_format($protein);?>'/> 
						<p>Carbs: <?php echo number_format($carbs);?>g per day</p>
						<input type='hidden' name='carbs' value='<?php echo number_format($carbs);?>'/> 
						<p>Fat: <?php echo number_format($fat);?>g per day</p>
						<input type='hidden' name='fat' value='<?php echo number_format($fat);?>'/> 
							
        </div>
		
		<br><input type="submit" class="submit"  value="Use Calculated Macros">
		</form
        <?php endif;?>
    </div>
<?php endif;?>

</div>

