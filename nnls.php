<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once('serverConnect.php');
global $conn;

require_once 'Math/Matrix.php';

/*
Initialize:
Set P = null
Set R = {1, ..., n}
Set x to an all-zero vector of dimension n
Set w = A_t(y − Ax)
*/
echo nl2br("INITIALIZE--------------------------------------\n");
$A = array(
	array(4.0,4,6,4),
	array(5.0,5,5,4),
	array(5.0,5,5,6),
); //food macros
$A = new Math_Matrix($A);
$A_t = $A->cloneMatrix();
$A_t->transpose(); //Transpose of A
$size = $A->getSize();
$m = $size[0]; //rows
$n = $size[1];//columns

$y = array(
	array(50.0),
	array(45.0),
	array(25.0),
); //target macros
$y = new Math_Matrix($y);
$R_P= [];
$x_arr = [];
for($i=0; $i<$n; $i++)
{
	$R_P[$i] = 0;
	$x_arr[$i] = array(0);
}

$x = new Math_Matrix($x_arr);
echo nl2br("A:\n".$A -> toString()."rows: ".$m."\n columns: ".$n."\n\nA_t:\n".$A_t->toString()."\n y:\n".$y->toString()."\n x:\n".$x->toString());
$t = (1/10000000000);
$w = 0;
echo nl2br("\n w:");
set_w();
$AP = new Math_Matrix($data=null);
$AP_t = new Math_Matrix($data=null);
$s = new Math_Matrix($x_arr);
$sP = new Math_Matrix($data=null);
echo nl2br("\n s:\n".$s->toString());




nnls();
/*
Inputs:
a real-valued matrix A of dimension m × n
a real-valued vector y of dimension m
a real value t, the tolerance for the stopping criterion
*/
function nnls(){
	global $t, $w, $n, $R_P, $AP, $s, $sP, $x;
	$loop = 0;
	while($loop != $n && $w->getMax() > $t)
	{
		echo nl2br("\nLOOP---------------------------------------------------------\n");
		$j = $w->getMaxIndex();
		$j = $j[0];
		echo nl2br("Max Index of w: ".$j);
		$R_P[$j] = 1;
		echo nl2br("\nR_P:\n");
		echo implode(", ", $R_P);
		set_s();
		echo nl2br($sP->getMin()."\n Inner Loop if vlaue above <= zero\n");
		while($sP->getMin() <= 0)
		{
			echo nl2br("\nINNER LOOP--------------------------------------------------\n");
			$min = 1;
			echo nl2br("\nx:\n".$x->toString()."s:\n".$s->toString());
			for($i=0; $i<$n; $i++)
			{
				if($R_P[$i] == 1){
					$b = $s->getElement($i, 0);

					if($b <= 0)
					{
						$c = $x->getElement($i, 0);
						$d = $c/($c-$b);
						echo "<br>".$d;
						if($d < $min)
						{
							$min = $d;
						}
					}
				}

			}
			$temp = $s->cloneMatrix();
			$temp->sub($x);
			$temp->scale($min);
			$x->add($temp);
			for($i=0; $i<$n; $i++)
			{
				$c = $x->getElement($i, 0);
				if($c == 0)
				{
					$R_P[$i] = 0;
				}
			}

			set_s();
			echo nl2br("\nR_P:\n");
			echo implode(", ", $R_P);

		}
		$x = $s->cloneMatrix();
		echo nl2br("\nx: \n".$x->toString());
		set_w();
		$loop = array_sum($R_P);
		echo "w max: ".$w->getMax();
	}
	echo nl2br("\nx: \n".$x->toString());

}

function set_w()
{
	global $A, $w, $x, $y, $A_t;
	echo nl2br("\nSet w to A_t(y − Ax)\n\n");
	$w = $A->cloneMatrix();
	echo nl2br("Step1: Ax\nA:\n".$w->toString()." multiply \nx:\n".$x->toString()." Ax equals:\n");
	$w->multiply($x);
	echo nl2br($w->toString());
	echo nl2br("\nStep 2: y - Ax\ny:\n".$y->toString()."minus\nAx:\n".$w->toString()."y - Ax equals:\n");
	$temp = $y->cloneMatrix();
	$temp->sub($w);
	echo nl2br($temp->toString());
	$w = $A_t->cloneMatrix();
	echo nl2br("\nStep 3: A_t(y − Ax)\n A_t:\n".$w->toString()."multiply\n(y − Ax):\n".$y->toString()."A_t(y − Ax) equals: \n");
	$w->multiply($temp);
	echo nl2br("w:\n".$w->toString());
}

function set_AP()
{
	global $R_P, $A, $AP, $AP_t, $n;
	$k = 0;
	$j = [];
	for($i=0; $i<$n; $i++)
	{
		if($R_P[$i] == 1)
		{
			$j[$k] = $A->getCol($i);
			$k++;
		}
	}
	if($j != null)
	{
		$AP_t = new Math_Matrix($j);
		$AP = $AP_t->cloneMatrix();
		$AP->transpose();
		echo nl2br("\nAP:\n".$AP->toString()."\nAP_t:\n".$AP_t->toString());
	}
	else
	{
		$AP = new Math_Matrix($data=null);
		$AP_t = new Math_Matrix($data=null);
	}

}

function set_s()
{
	global $AP_t, $AP, $y, $n, $sP, $R_P, $s, $x_arr;
		set_AP();
		echo nl2br("\nSet sP = (AP_t AP)^-1 (AP_t)y\n");
		$sP = $AP_t->cloneMatrix();
		$sP->multiply($AP);
		echo nl2br("\nAP_t:\n".$AP_t->toString()."multpily\nAP:\n".$AP->toString()."AP_t AP equals:\n".$sP->toString());
		$sP->invert();
		echo nl2br("(AP_t AP)^-1 equals:\n".$sP->toString());
		$sP->multiply($AP_t);
		echo nl2br("AP_t AP)^-1 (AP_t) equals:\n".$sP->toString());
		$sP->multiply($y);
		echo nl2br("AP_t AP)^-1 (AP_t)y equals:\nsP:\n".$sP->toString());
		$k=0;
		$l=0;
		for($i=0; $i<$n; $i++)
		{
			if($R_P[$i] == 1)
			{
				$s->setRow($i, $sP->getRow($k));
				$k++;
			}
			else
			{
				$s->setRow($i, $x_arr[$l]);
				$l++;
			}
		}
		echo nl2br("s:\n".$s->toString());
}

?>
