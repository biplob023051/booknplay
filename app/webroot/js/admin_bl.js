function bl_init(){
	var num = 0;
	var month =['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec',];
	$('#base_price').text('-');
	$('#service_charge').text('-');
	$('#total_price').text('-');
	$('#selected_slots').text('-');
	
	//Setting no of court feature
	$('#no_of_court').change(function(){
		$('.book_sumbit_bl').unbind('click');
		$bookingListEntry = $(this).parentsUntil('.booking_list');
		 $.get(BASE_URL+"grounds/booking_layout/"+$(this).data('gid')+"/"+$(this).data('startdate')+"/"+$(this).val(), function(data, status){
			    $bookingListEntry.find('.ajax_bl_parent').show();
				$bookingListEntry.find(".ajax_bl").html(data);
				bl_init();
		   });
	       return;
	 });
	$('.single_slot').click(function(){
		var value = $(this).attr('value');
		var slot_value = value;
		var availClass = $(this).attr('class');
		console.log(availClass);
		if($(this).hasClass('available')){
			$(this).addClass('select');
			$(this).removeClass('available');
			var temp = new Date();
			console.log(value);
			//Setting Hours
			var hr = value.slice(-2)-1;
			value = value.slice(0,-2);
			
			//Setting Day
			value = value.slice(1);
			
			temp.setTime(temp.getTime() +  ((value-1) * 24 * 60 * 60 * 1000));
			temp.setHours(hr);
			temp.setMinutes(0);
			temp.setSeconds(0);
			var date = new Date(temp.getTime());
			num++;
			//Selected Slot
			//if($('#selected_slots').text() == '')
				//$('#selected_slots').append('<span class="dlabel label'+$(this).val()+'">'+date.getDate()+'-'+(month[date.getMonth()])+'-'+date.getFullYear()+' '+formatAMPM((date.getHours()))+'('+$('#no_of_court').val()+')'+'</span>');
				if($('#selected_slots').text() == '-') {
					$('#selected_slots').text(1);
				} else if($('#selected_slots').text() != '') {
					var currentValue = parseInt($("#selected_slots").text(),10);
					console.log(currentValue);
					currentValue++;
					$('#selected_slots').text(currentValue);
				} 
				$('<input>').attr({
					type: 'hidden',
					name: 'data[slots]['+slot_value+']',
					value: slot_value,
					class: 'slot_value_box'
				}).appendTo($(this).closest('#BookingPaymentForm'));
			} else if($(this).hasClass('select')){
				$( "input[value='"+slot_value+"']" ).remove();
				var currentValue = parseInt($("#selected_slots").text(),10);
				if(currentValue > 0) {
					currentValue = (currentValue - 1);
					$('#selected_slots').text(currentValue);
				}
				//Unselect slots
				$(this).addClass('available');
				$(this).removeClass('select');
				num--;
			}
			console.log('numsmmsm : '+num);
			var no_of_court = $('#no_of_court').val();
			var current_ground_id =$(this).closest('form').find('input[name="data[Booking][ground_id]"]').val();
			update_price(num*no_of_court, current_ground_id);
	});
	function update_price(no){
		if (typeof(no)==='undefined') no = 0;
		
		var base = no*base_price;
		var service = no*service_charge;
		var total = base+service;
		$('#base_price').text(base);
		$('#service_charge').text(service);
		$('#total_price').text(total);
	}
	function formatAMPM(hours) {
		  var ampm = hours >= 12 ? 'pm' : 'am';
		  hours = hours % 12;
		  hours = hours ? hours : 12;
		  var strTime = hours +''+ ampm;
		  return strTime;
		}
	
	//$('#s11,#s12,#s13,#s14,#s124,#s123,#s21,#s22,#s23,#s24,#s224,#s223,#s31,#s32,#s33,#s34,#s324,#s323,#s41,#s42,#s43,#s44,#s424,#s423,#s51,#s52,#s53,#s54,#s524,#s523,#s61,#s62,#s63,#s64,#s624,#s623,#s71,#s72,#s73,#s74,#s724,#s723').attr('disabled','disabled');
    
	  //$('.booking_layout').hide();
	 
	 $('.book_sumbit_bl').click(function(){
		 if(num > 0)
			 $(this).parentsUntil('.book_in_bl').submit();
		 else{
			 alert('No slot selected !');
			 return false;
		 }
			 
		 
	 });
	   
	 //Select Day slots
	 $('.bl_timedisplay').css('cursor','pointer');
	 $('.bl_timedisplay').click(function(){
		 $(this).parent().find('input[type="checkbox"]').trigger('click');
	 });
}
$(document).ready(function(){
	 $('.view_slot').click(function(){
		   $view_slot = $(this);
		   //Hide Slots
		   if($view_slot.html() == 'Hide Slots'){
			   $view_slot.html('View Slots')
			   $(".ajax_bl").html('');
			   $('.ajax_bl_parent').hide();
			   $('.book_sumbit_bl').unbind('click');
			   return true;
		   }
		   //Insert Form and attach BL to send to next page
		   $bookingListEntry = $(this).parentsUntil('.booking_list');
		   
		   $('.view_slot').html('View Slots');
		   $view_slot.html('Loading...');
		   
		   $(".ajax_bl").html('');
		   $('.ajax_bl_parent').hide();
		   $.get(BASE_URL+"grounds/booking_layout/"+$(this).val()+"/"+$('#search_start_date').val()+"/1", function(data, status){
			   $view_slot.html('Hide Slots');
			    $bookingListEntry.find('.ajax_bl_parent').show();
				$bookingListEntry.find(".ajax_bl").html(data);
				bl_init();
		   });
	       return true;
	 });
});