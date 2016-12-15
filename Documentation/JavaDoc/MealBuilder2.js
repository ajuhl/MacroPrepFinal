// Array to hold current total values of Protein, Carb, and Fat from each meal,
// up to 10 meals
var totalArray = [[0,0,0],[0,0,0],[0,0,0],[0,0,0],[0,0,0],[0,0,0],[0,0,0],[0,0,0],[0,0,0],[0,0,0]];


$(document).ready(function() {
	//create globals with info from last page
	document.totalProtein = parseInt($('[name="protein"]')[0].value);
	document.totalCarb = parseInt($('[name="carb"]')[0].value);
	document.totalFat = parseInt($('[name="fat"]')[0].value);

	servingSliders = $('.servingSlider');
	servingSliders.on('input', updateSlidersAndDisplays);
	servingSliders.each(function(index, element){
		$(element).attr('index',index);
	});

	$('.meal').each(function(index){
		var meal = index+1;
		updateDisplayForMeal(meal);
	});

	$('.servingSlider').each(function(index){
		updateServingDisplay(index);
		console.log(index);
	});

});

//Updates the displayed values of the servings and macronutrients as
// the values change based on user input
function updateSlidersAndDisplays(event){
	var source = $(event.target || event.srcElement);
	var meal = source.attr('meal');
	var sliderIndex = source.attr('index');
	updateServingDisplay(sliderIndex);
	//updateServingsFromChange(sliderIndex);
	updateDisplayForMeal(meal);
	source.attr('prevValue',source.val());
}


function updateServingDisplay(index){
	var slider = $($('.servingSlider')[index]);
	var original = slider.attr('serving');
	var servings = slider.val();
	var servingDisplay = $($('.servingDisplay')[index]);
	servingDisplay.html( scaleServingSize(original,servings) );
}

function updateServingsFromChange(index){
	var slider = $($('.servingSlider')[index]);
	var meal = slider.attr('meal');
	var wasIncrease = ( slider.val() - slider.attr('prevValue') ) > 0;

	var proteinDisplay = $($('.macroDisplay')[(meal-1)*3+0]);
	var carbDisplay    = $($('.macroDisplay')[(meal-1)*3+1]);
	var fatDisplay     = $($('.macroDisplay')[(meal-1)*3+2]);
	var proteinGoal = proteinDisplay.attr('goal');
	var carbGoal    = carbDisplay.attr('goal');
	var fatGoal     = fatDisplay.attr('goal');
	var proteinDiff = proteinGoal - proteinDisplay.attr('current');
	var carbDiff    = carbGoal - carbDisplay.attr('current');
	var fatDiff     = fatGoal - fatDisplay.attr('current');
	var totalDiff = Math.abs(proteinDiff) + Math.abs(carbDiff) + Math.abs(fatDiff);

	var mealSliders = $('input[type=range][meal='+meal+'][index!='+index+']');
	var smallestDiff = 0;
	var cont = true;
	var change = (wasIncrease) ? -0.01 : 0.01;
	while(cont){
		var possibleDiffs = new Array();
		mealSliders.each(function(index,element){
				var newProteinDiff = proteinDiff + change * $(element).attr('protein');
				var newCarbDiff    = carbDiff + change * $(element).attr('carb');
				var newFatDiff     = fatDiff + change * $(element).attr('fat');
				possibleDiffs[index] = Math.abs(newProteinDiff) + Math.abs(newCarbDiff) + Math.abs(newFatDiff);
		});
		smallestDiff = Math.min(...possibleDiffs);
		if(smallestDiff<totalDiff){
			var changeIndex = possibleDiffs.indexOf(smallestDiff);
			var changeSlider = mealSliders[changeIndex];
			var changeSliderIndex = $(changeSlider).attr('index');
			changeSlider.value = parseFloat(changeSlider.value) + change;
			updateServingDisplay(changeSliderIndex);
			updateDisplayForMeal(meal);
			proteinDiff = proteinDiff + change * $(changeSlider).attr('protein');
			carbDiff    = carbDiff + change * $(changeSlider).attr('carb');
			fatDiff     = fatDiff + change * $(changeSlider).attr('fat');
			totalDiff = smallestDiff;
		}else{
			cont = false;
		}
	}
	/*mealSliders.each(function(index,element){
		var newDiff = 0;
		while(newDiff<totalDiff){
			var change = (wasIncrease) ? -0.01 : 0.01;
			var newProteinDiff = proteinDiff + change * $(element).attr('protein');
			var newCarbDiff    = carbDiff + change * $(element).attr('carb');
			var newFatDiff     = fatDiff + change * $(element).attr('fat');
			newDiff = Math.abs(newProteinDiff) + Math.abs(newCarbDiff) + Math.abs(newFatDiff);
			if(newDiff<totalDiff){
				element.value = parseFloat(element.value) + change;
				updateServingDisplay(index);
				updateDisplayForMeal(meal);
				totalDiff = newDiff;
			}
		}
	});*/
}

function scaleServingSize(original,servings){
	var originalAmount = parseFloat(original);
	var measure = original.substr(original.indexOf(' '));
	var newAmount =  (servings*originalAmount).toFixed(1);
	return (newAmount+measure);
}

function updateDisplayForMeal(meal){
	var sliders = $('input[type=range][meal='+meal+']');
	var	mealProtein = 0;
	var	mealCarb = 0;
	var	mealFat = 0;

	sliders.each(function(index,element){
		mealProtein += parseInt( parseFloat($(element).attr('protein')) * parseFloat($(element).val()) );
		mealCarb += parseInt( parseFloat($(element).attr('carb')) * parseFloat($(element).val()) );
		mealFat += parseInt( parseFloat($(element).attr('fat')) * parseFloat($(element).val()) );
	});

	//Adds values of total protein, carb, and fat from this specific meal
	// Protein in the first index, Carbs in the second, and Fats in the third
	// Ex: totalArray[0][0] would be the amount of protein for the first meal
	totalArray[meal-1][0] = mealProtein;
	totalArray[meal-1][1] = mealCarb;
	totalArray[meal-1][2] = mealFat;

	var proteinDisplay = $($('.macroDisplay')[(meal-1)*3+0]);
	var carbDisplay    = $($('.macroDisplay')[(meal-1)*3+1]);
	var fatDisplay     = $($('.macroDisplay')[(meal-1)*3+2]);

	var proteinGoal = proteinDisplay.attr('goal');
	var carbGoal    = carbDisplay.attr('goal');
	var fatGoal     = fatDisplay.attr('goal');

	//Update display for each macronutrient
	updateMacroDisplay(proteinDisplay,mealProtein,proteinGoal);
	updateMacroDisplay(carbDisplay,mealCarb,carbGoal);
	updateMacroDisplay(fatDisplay,mealFat,fatGoal);
	updateTotalMacroCounter();

}

//Displays current total value of macronutrient for meal
function updateMacroDisplay(display,current,target){
	//Displays a green color if the value is equal to the goal
	if(current==target){
		display.html(current+'g');
		display.addClass('green');
		display.removeClass('orange');
	}
	//Determines how much the value is over or under from goal and displays red
	else{
		var suffix = (current>target) ? 'over':'under';
		var difference = Math.abs(current-target);
		var differenceString = '<span class="servingDifference">'+difference+'g '+suffix+' (Goal: '+target+'g)</span>';
		display.html(current+'g  '+differenceString);
		display.removeClass('green');
		display.addClass('orange');
	}
	display.attr('current',current);
}

//Updates the total macronutrient counter based on all values from all meals
function updateTotalMacroCounter()
{
	var proteinTotal = 0;
	var carbTotal = 0;
	var fatTotal= 0;

//Adds all the values from each meal into specific variables
	for(var i = 0; i<totalArray.length; i++)
	{
		proteinTotal += totalArray[i][0];
		carbTotal += totalArray[i][1];
		fatTotal += totalArray[i][2];
	}

	//Update the running total protein value with calculated total from all meals
  $('#proteinGoal').html('Current Total: '+proteinTotal+'g');
	//Change color of text depending on if it is equal to the total daily goal
		if(proteinTotal==document.totalProtein){
			$('#proteinGoal').addClass('green');
			$('#proteinGoal').removeClass('orange');
		}else{
			$('#proteinGoal').removeClass('green');
			$('#proteinGoal').addClass('orange');
		}
	//Update the running total carbs value with calculated total from all meals
	$('#carbGoal').html('Current Total: '+carbTotal+'g');
	//Change color of text depending on if it is equal to the total daily goal
		if(carbTotal==document.totalCarb){
			$('#carbGoal').addClass('green');
			$('#carbGoal').removeClass('orange');
		}else{
			$('#carbGoal').removeClass('green');
			$('#carbGoal').addClass('orange');
		}
	//Update the running total fats value with calculated total from all meals
	$('#fatGoal').html('Current Total: '+fatTotal+'g');
	//Change color of text depending on if it is equal to the total daily goal
		if(fatTotal==document.totalFat){
			$('#fatGoal').addClass('green');
			$('#fatGoal').removeClass('orange');
		}else{
			$('#fatGoal').removeClass('green');
			$('#fatGoal').addClass('orange');
		}

}
