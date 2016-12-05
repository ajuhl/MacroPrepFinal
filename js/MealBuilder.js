//update function for macroSlider movement
function updateMacroFromSlider(event){
	var source = event.target || event.srcElement;
	var index = $(source).attr('index')-1;
	macroSliders = $('.macroSlider');
	macroDisplays = $('.macroDisplay');
	$(macroDisplays[index]).html(source.value+'g');
	var total = 0;
	for(x=0;x<document.mealQty;x++){
		total += parseInt(macroSliders[(x*3)+(index%3)].value);
		console.log(total);
	}
	if(index%3==0){
		$('#proteinGoal').html('Current Total: '+total+'g');
		if(total==document.totalProtein){
			$('#proteinGoal').addClass('green');
			$('#proteinGoal').removeClass('orange');
		}else{
			$('#proteinGoal').removeClass('green');
			$('#proteinGoal').addClass('orange');
		}
	}else if(index%3==1){
		$('#carbGoal').html('Current Total: '+total+'g');
		if(total==document.totalProtein){
			$('#carbGoal').addClass('green');
			$('#carbGoal').removeClass('orange');
		}else{
			$('#carbGoal').removeClass('green');
			$('#carbGoal').addClass('orange');
		}
	}else{
		$('#fatGoal').html('Current Total: '+total+'g');
		if(total==document.totalProtein){
			$('#fatGoal').addClass('green');
			$('#fatGoal').removeClass('orange');
		}else{
			$('#fatGoal').removeClass('green');
			$('#fatGoal').addClass('orange');
		}
	}

}

//add food select2 to corresponding meal
function addFoodToContainer(event){
	var source = event.target || event.srcElement;
	foodContainers = $('.foodContainer');
	var mealIndex = $(source).attr('index');
	var foodIndex = foodContainers[mealIndex-1].children.length/2+1;
	/*$.get('./inc/foodSelect.php', function(data){
		$(foodContainers[mealIndex-1]).append(data);
		$('[name="newFood"]')[0].name = 'm'+mealIndex+'food'+foodIndex;
		$('.foodSelect').select2({
			placeholder: 'Select a food'
		});
	});*/
	var newFoodSelect = $($('.foodSelectTemplate').contents()[1]).clone();
	$(foodContainers[mealIndex-1]).append(newFoodSelect);
	$('[name="newFood"]')[1].name = 'm'+mealIndex+'food'+foodIndex;
	$('.foodSelect').select2({
		placeholder: 'Select a food'
	});
}

$(document).ready(function() {
	//create globals with info from last page
	document.totalProtein = parseInt($('[name="protein"]')[0].value);
	document.totalCarb = parseInt($('[name="carb"]')[0].value);
	document.totalFat = parseInt($('[name="fat"]')[0].value);
	document.mealQty = parseInt($('[name="mealQty"]')[0].value);

	//load select2 functionality
	$('.foodSelect').select2({
		placeholder: 'Select a food',
	});

	//pair sliders and displays for macros
	macroDisplays = $('.macroDisplay');
	macroSliders = $('.macroSlider');
	macroSliders.on('input', updateMacroFromSlider );
	macroSliders.each(function(index, element){
		$(element).attr('index',index+1);
		$(macroDisplays[$(element).attr('index')-1]).html(element.value+'g');
	});

	//pair add food buttons with food divs
	addFoodButtons = $('.addFood');
	addFoodButtons.on('click', addFoodToContainer);
	addFoodButtons.each(function(index, element){
		$(element).attr('index',index+1);
	});

});
