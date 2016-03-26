if(typeof slots != 'undefined')
	var slots = 24; 
var fillData = prepareEmptySlot(slots);
var editData = $('#schedule_advance_ui').val();
if(editData){
	fillData = editData;
	var editDataArr = editData.split(',');
	for(var i=0;i<editDataArr.length;i++){
		if(editDataArr[i] == 1)
			$('#s'+(i+1)).prop('checked', true);
	}
}
$('#schedule_advance_ui').val(fillData);
function prepareEmptySlot(no){
	var temp = [];
	for(var i=0;i<no;i++){
		temp[i] = 0; 
	}
	return temp.join();
}
function enableSlot(data,no){
	var temp = data.split(',');
	temp[no-1] = 1;
	return temp.join();
}
function disableSlot(data,no){
	var temp = data.split(',');
	temp[no-1] = 0;
	return temp.join();
}

//Operations

$('.schedule_slot_ui').find('.single_slot').click(function(){
	var slot = $(this).val();
	var num = slot.slice(1);
	if($(this).is(":checked"))
		fillData = enableSlot(fillData,num);
	else
		fillData = disableSlot(fillData,num);
		
	$('#schedule_advance_ui').val(fillData);
});
//For Index Page
$('.change_ui_slots').each(function(){
	var value = $(this).text();
	$(this).text('');
	value = value.split(',');
	for(var i=0;i<value.length;i++)
	{
		if(value[i] == 1)
			$(this).append('<span class="single_slot ss_enable">'+(((i)<12)?(((((i)==0))?12:(i))):(((((i+1)==13))?12:(i-12))))+(((i)<12)?"a":"p")+'</span>')
		else
			$(this).append('<span class="single_slot">'+(((i)<12)?(((((i)==0))?12:(i))):(((((i+1)==13))?12:(i-12))))+(((i)<12)?"a":"p")+'</span>')
	}
		
});