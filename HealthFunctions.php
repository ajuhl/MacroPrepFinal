<?php

error_reporting(E_ALL&(~E_NOTICE));
session_start();
$calc_mode=1;
?>
<style type="text/css">
.calculator_div
{
	font-family:verdana, arial, sans-serif;
	border:2pt solid #4444FF;
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
	$dailyFat = $_POST["lbs"] * 0.4;
	$dailyProtein = $_POST["lbs"] * $_POST["activity"];

    if($_POST["gender"]=='male')
	{
		$BMR= 66.5 + (6.3 * $_POST["lbs"]) + (12.9 * $inch) - (6.8 * $_POST["age"]);
	}
	else
	{
		$BMR= 655 + (4.3 * $_POST["lbs"]) + (4.7 * $inch) - (4.7 * $_POST["age"]);
	}

    /* activity?
    if($calc_mode)
    {
        $extra_energy=$BMR*$_POST["activity"];
        $energy_needs=round($BMR+$extra_energy);
    }*/
}
?>

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
					<input id="height"  name="height" type="radio" value="cm" onclick="showHide('cm','feet','CM','labelh');showHide('cm','inch','CM','labelh');" <?php if($_SESSION["calc_bmr_height"]=="cm") echo "checked"; else { if(!isset($_SESSION["calc_bmr_heigth"])) echo "checked";}?> />
					<label style="width:75px;display:inline;float:none;">cm</label>
					<input id="height" name="height" type="radio" value="feet" onclick="showHide('feet','cm','feet/inch','labelh');showHide('inch','cm','feet/inch','labelh');" <?php if($_SESSION["calc_bmr_height"]=="feet") echo "checked"; ?> />
					<label style="width:75px;display:inline;float:none;">feet/inch</label>

	</p>
		<p><label >&nbsp;</label>
					<input type="text" name="cm" id="cm" size="4" onkeyup="IsNumber(this.id);CmToFt(this.value,'feet','inch');" value="<?php echo $_SESSION["calc_bmr_cm"];?>">
					<input type="text" name="feet" id="feet" size="4" onkeyup="IsNumber(this.id);FtToCm('feet','inch','cm');" style="display:none;" value="<?php echo $_SESSION["calc_bmr_feet"]; ?>">
					<input type="text" name="inch" id="inch" size="4" onkeyup="IsNumber(this.id);FtToCm('feet','inch','cm');" style="display:none;" value="<?php echo $_SESSION["calc_bmr_inch"]; ?>">
					<span id=labelh >
					<?php if($_SESSION["calc_bmr_height"]=="feet"):?>
					feet/inch
					<SCRIPT LANGUAGE="javascript">
					showHide('feet','cm','feet/inch','labelh');
                    showHide('inch','cm','feet/inch','labelh');
					</SCRIPT>
					<?php else:?>cm<?php endif;?>
                   </span>
	</p>


  <p><label>Daily Activity:</label> <select name="activity">
    <option value="0.8">No sport/exercise</option>
    <option value="0.9">Light activity (sport 1-3 times per week)</option>
    <option value="1.0">Moderate activity (sport 3-5 times per week)</option>
    <option value="1.1">High activity (everyday exercise)</option>
    <option value="1.2">Extreme activity (professional athlete)</option>
    </select></p>


	<div style="text-align:center;clear:both;"><input type="submit" value="Calculate!"></div>
	<input type="hidden" name="calculator_ok" value="1">
	</form>


<?php if(!empty($_POST['calculator_ok'])):?>
    <div id="table">
    	<div class="rowheader" style="background-color:#4BACE6;">
    					Your BMR is: <?php echo number_format($BMR); ?> calories/day
    	</div>
        <?php if($calc_mode):?>
        <div class="rowheader" style="background-color:#4BACE6;">
						<p>Total amount of Protein needed per day: <?php echo number_format($dailyProtein)?>g per day</p>
						<p>Total amount of Fat needed per day: <?php echo number_format($dailyFat)?>g per day</p>
						<p>Total amount of Carbs per day: <?php echo number_format(($BMR - ($dailyProtein*4 + $dailyFat*9))/4)?>g per day</p>
        </div>
        <?php endif;?>
    </div>
<?php endif;?>

</div>