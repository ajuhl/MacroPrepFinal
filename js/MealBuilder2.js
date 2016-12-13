
var totalArray = [[0,0,0],[0,0,0],[0,0,0],[0,0,0]];


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
	updateDisplayForMeal(meal);

}

function updateServingDisplay(index){
	var slider = $($('.servingSlider')[index]);
	var original = slider.attr('serving');
	var servings = slider.val();
	var servingDisplay = $($('.servingDisplay')[index]);
	servingDisplay.html( scaleServingSize(original,servings) );
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

}

function updateTotalMacro()
{
	var proteinTotal = 0;
	var carbTotal = 0;
	var fatTotal= 0;

	for(var i = 0; i<totalArray.length; i++)
	{
		proteinTotal += totalArray[i][0];
		carbTotal += totalArray[i][1];
		fatTotal += totalArray[i][2];
	}
  $('#proteinGoal').html('Current Total: '+proteinTotal+'g');
		if(proteinTotal==document.totalProtein){
			$('#proteinGoal').addClass('green');
			$('#proteinGoal').removeClass('orange');
		}else{
			$('#proteinGoal').removeClass('green');
			$('#proteinGoal').addClass('orange');
		}

	$('#carbGoal').html('Current Total: '+carbTotal+'g');
		if(carbTotal==document.totalCarb){
			$('#carbGoal').addClass('green');
			$('#carbGoal').removeClass('orange');
		}else{
			$('#carbGoal').removeClass('green');
			$('#carbGoal').addClass('orange');
		}
	$('#fatGoal').html('Current Total: '+fatTotal+'g');
		if(fatTotal==document.totalFat){
			$('#fatGoal').addClass('green');
			$('#fatGoal').removeClass('orange');
		}else{
			$('#fatGoal').removeClass('green');
			$('#fatGoal').addClass('orange');
		}

}
