
var totalArray = [
	[], [], []
];


$(document).ready(function() {
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
	});

});

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
	totalArray[meal-1][0] = mealProtein;
	totalArray[meal-1][1] = mealCarb;
	totalArray[meal-1][2] = mealFat;

	var proteinDisplay = $($('.macroDisplay')[(meal-1)*3+0]);
	var carbDisplay    = $($('.macroDisplay')[(meal-1)*3+1]);
	var fatDisplay     = $($('.macroDisplay')[(meal-1)*3+2]);

	var proteinGoal = proteinDisplay.attr('goal');
	var carbGoal    = carbDisplay.attr('goal');
	var fatGoal     = fatDisplay.attr('goal');

	updateMacroDisplay(proteinDisplay,mealProtein,proteinGoal);
	updateMacroDisplay(carbDisplay,mealCarb,carbGoal);
	updateMacroDisplay(fatDisplay,mealFat,fatGoal);
	updateTotalMacro();

}

function updateMacroDisplay(display,current,target){
	if(current==target){
		display.html(current+'g');
		display.addClass('green');
		display.removeClass('orange');
	}
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
