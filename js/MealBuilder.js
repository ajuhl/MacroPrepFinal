//update function for macroSlider movement
function updateMacroFromSlider(event){
	var source = event.target || event.srcElement;
	var index = $(source).attr('index');
	macroDisplays = $('.macroDisplay');
	$(macroDisplays[index]).html(source.value+'g');
	if(index%3==1){
		$('#proteinGoal').html('Current Total: '+source.value+'g');
	}else if(index%3==2){
		
	}else{
		
	}

}

function formatRepo (repo) {
	if (repo.loading) return repo.text;

	var markup = "<div>"+repo.name+"</div>"

	return markup;
}

function formatRepoSelection (repo) {
	return repo.full_name || repo.text;
}

//add food select2 to corresponding meal
function addFoodToContainer(event){
	var source = event.target || event.srcElement;
	foodContainers = $('.foodContainer');
	$.get('./inc/foodSelect.php', function(data){
		$(foodContainers[$(source).attr('index')]).append(data);
		$('.foodSelect').select2({
			placeholder: 'Select a food',
			/*ajax: {
				type:"POST",
				dataType : "json",
				url      : "./inc/foods.json",
				processResults: function (data) {
					console.log(data);
					return {
						results: $.map(data, function(obj) {
							return { id: obj.id, text: obj.name };
						})
					};
				}
			}*/
		});
	});
}

$(document).ready(function() {
	//load select2 functionality
	$('.foodSelect').select2({
		placeholder: 'Select a food',
	});

	//pair sliders and displays for macros
	macroDisplays = $('.macroDisplay');
	macroSliders = $('.macroSlider');
	macroSliders.on('input', updateMacroFromSlider );
	macroSliders.each(function(index, element){
		$(element).attr('index',index);
		$(macroDisplays[$(element).attr('index')]).html(element.value+'g');
	});

	//pair add food buttons with food divs
	addFoodButtons = $('.addFood');
	addFoodButtons.on('click', addFoodToContainer);
	addFoodButtons.each(function(index, element){
		$(element).attr('index',index);
	});

});
