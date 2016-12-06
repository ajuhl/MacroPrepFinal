<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once('serverConnect.php');
global $conn;

require_once 'Math/Matrix.php';

/*
NNLS based off of pseudocode provided by:

Lawson, Charles L.; Hanson, Richard J. (1995). Solving Least Squares Problems. SIAM.

Inputs:
	a real-valued matrix A of dimension m × n
	a real-valued vector y of dimension m
	a real value t, the tolerance for the stopping criterion
Initialize:
	Set P = ∅
	Set R = {1, ..., n}
	Set x to an all-zero vector of dimension n
	Set w = Aᵀ(y − Ax)
Main loop: while R ≠ ∅ and max(w) > t,
	Let j in R be the index of max(w) in w
	Add j to P
	Remove j from R
	Let AP be A restricted to the variables included in P
	Let s be vector of same length as x. Let sP denote the sub-vector with indexes from P, and let sR denote the sub-vector with indexes from R.
	Set sP = ((AP)ᵀ AP)−1 (AP)ᵀy
	Set sR to zero
	While min(sP) ≤ 0:
		Let α = min[xi / (xi - si)] for i in P where si ≤ 0
		Set x to x + α(s - x)
		Move to R all indices j in P such that xj = 0
		Set sP = ((AP)ᵀ AP)−1 (AP)ᵀy
		Set sR to zero
	Set x to s
	Set w to Aᵀ(y − Ax)
*/

class nnls{
	public $A = null;
	public $y; 								//given constraint matrix
								//given target values

	private $rowSize;
								//row size of matrix A
	private $columnSize;				//column size of matrix A
	private	$R_P;							//tracker array
	private $zeroArray;					//Array of size columnSize filled with zero's
	private	$x;								//solution vector
	private $t;	//tolerance
	private $w;
	private $s;
	private $A_t;							//transpose of A
	private	$AP;								//Matrix composed of column(s) of A, who's index(s) in R_P = 1;
	private $AP_t;							// transpose of AP.
	private $sP;




	public function __construct($array, $vector){
		$this->A = new Math_Matrix($array);
		$size = $this->A->getSize();
		$this->rowSize = $size[0];
		$this->columnSize = $size[1];
		$this->A_t = $this->A->cloneMatrix();
		$this->A_t->transpose();
		$this->y = new Math_Matrix($vector);
		$this->R_P = [];
		$this->zeroArray = [];
		for($i=0; $i<$this->columnSize; $i++)
		{
			$this->R_P[$i] = 0;
			$this->zeroArray[$i] = array(0);
		}
		$this->x = new Math_Matrix($this->zeroArray); //solution vector
		$this->set_w();
		$this->AP = new Math_Matrix($data=null);
		$this->AP_t = new Math_Matrix($data=null);
		$this->s = new Math_Matrix($this->zeroArray);
		$this->sP = new Math_Matrix($data=null);
		$this->t = (1/10000000000);

		$this->run();
		
	}

	private function run(){
		$R_PisFull = 0;
		while($R_PisFull != $this->columnSize && $this->w->getMax() > $this->t)
		{
			$maxIndex = $this->w->getMaxIndex();
			$maxIndex = $maxIndex[0];
			$this->R_P[$maxIndex] = 1;
			$this->set_s();

			while($this->sP->getMin() <= 0)
			{
				$alpha = 1;
				for($i=0; $i<$this->columnSize; $i++)
				{
					if($this->R_P[$i] == 1){
						$sValue = $this->s->getElement($i, 0);

						if($sValue < 0)
						{
							$xValue = $this->x->getElement($i, 0);
							$min = $xValue/($xValue-$sValue);
							if($min < $alpha)
							{
								$alpha = $min;
							}
						}
					}

				}
				$sClone = $this->s->cloneMatrix();
				$sClone->sub($this->x);
				$sClone->scale($alpha);
				$this->x->add($sClone);
				for($i=0; $i<$this->columnSize; $i++)
				{
					$xValue = $this->x->getElement($i, 0);
					if($xValue == 0)
					{
						$this->R_P[$i] = 0;
					}
				}
				$this->set_s();
			}

			$this->x = $this->s->cloneMatrix();
			$this->set_w();
			$R_PisFull = array_sum($this->R_P);
		}
	}

	private function set_w(){

		//	Set w to A_t(y − Ax)
		$this->w = $this->A->cloneMatrix();

		//multiply:  Ax
		$this->w->multiply($this->x);

		//subtract: y - Ax
		$yClone = $this->y->cloneMatrix();
		$yClone->sub($this->w);
		$this->w = $this->A_t->cloneMatrix();

		//multiply: A_t(y − Ax)
		$this->w->multiply($yClone);
	}

	private function set_AP(){

		//Matrix composed of column(s) of A, who's index(s) in R_P = 1;
		$arrAP_t = [];
		$indexArrAP_t = 0;
		for($i=0; $i<$this->columnSize; $i++)
		{
			if($this->R_P[$i] == 1)
			{
				$arrAP_t[$indexArrAP_t] = $this->A->getCol($i);
				$indexArrAP_t++;
			}
		}
		if($arrAP_t != null)
		{
			$this->AP_t = new Math_Matrix($arrAP_t);
			$this->AP = $this->AP_t->cloneMatrix();
			$this->AP->transpose();
		}
		else
		{
			$this->AP = new Math_Matrix($data=null);
			$this->AP_t = new Math_Matrix($data=null);
		}

	}

	private function set_s(){

		//Set sP = (AP_t AP)^-1 (AP_t)y
		$this->set_AP();
		$this->sP = $this->AP_t->cloneMatrix();

		//multiply: AP_t and AP
		$this->sP->multiply($this->AP);

		//(AP_t AP)^-1
		$this->sP->invert();

		//multiply: (AP_t AP)^-1 (AP_t)
		$this->sP->multiply($this->AP_t);

		//multiply: (AP_t AP)^-1 (AP_t)y
		$this->sP->multiply($this->y);

		// set s
		$sP_index=0;
		for($i=0; $i<$this->columnSize; $i++)
		{
			if($this->R_P[$i] == 1)
			{
				$this->s->setRow($i, $this->sP->getRow($sP_index));
				$sP_index++;
			}
			else
			{
				$this->s->setRow($i, $this->zeroArray[0]);
			}
		}
	}

	public function print_x(){
		echo nl2br($this->x->toString());
	}
	
	public function get_x(){
		return($this->x);
	}
	
	public function set_y($vector){
		$this->y = new Math_Matrix($vector);
		$this->R_P = [];
		$this->zeroArray = [];
		for($i=0; $i<$this->columnSize; $i++)
		{
			$this->R_P[$i] = 0;
			$this->zeroArray[$i] = array(0);
		}
		$this->x = new Math_Matrix($this->zeroArray); //solution vector
		$this->set_w();
		$this->AP = new Math_Matrix($data=null);
		$this->AP_t = new Math_Matrix($data=null);
		$this->s = new Math_Matrix($this->zeroArray);
		$this->sP = new Math_Matrix($data=null);
		$this->run();
	}
	

}
?>
