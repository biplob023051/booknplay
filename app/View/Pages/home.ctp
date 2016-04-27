<div class="section">
		<div class="wrap">
			<div class="center heading">
				<h1>Book Your Favorite Sports Venue in 3 Quick Steps...</h1>
			</div>
			<div class="clear"></div>
			<?php echo $this->EBForm->create('Ground',array('action'=>'search','id'=>"contact-formm",'class'=>'form-horizontal','type'=>'POST', 'onsubmit'=>"return searchPost()")); ?> 
			<?php echo $this->EBForm->input('locality',array('type'=>'hidden','label'=>false,'div'=>false,'value'=>'chennai')); ?>
			<?php echo $this->EBForm->input('group_id',array('type'=>'hidden','label'=>false,'div'=>false,'value'=>7)); ?>
			<div id="section1">
				<div class="center head">1. SPORT</div>
				<div class="center">
				<?php 
				$sport_selected = 0;
				foreach($groups as $sport_info_id => $sport_info) : ?>
					<a href="JavaScript:updateLocation('<?php echo $sport_info_id;?>','<?php echo str_replace(' ', '_', strtolower($sport_info));?>')" id="<?php echo str_replace(' ', '_', strtolower($sport_info));?>" class="sport <?php if($sport_selected==0) { echo "active";} ?>" value="<?php echo $sport_info_id;?>">
						<div class="sport-icon-container">
							<div class="sport-icon"></div>
						</div>
						<div class="sport-name"><?php echo $sport_info;?></div>
					</a>
				<?php
				$sport_selected++;
				endforeach;?>
					
				</div>
			</div>
			<div id="section2">
				<div class="center head">2. LOCATION</div>
				<div id="error-message" class="hide">
					<div class="alert alert-danger">
					    <a href="javascript:void(0)" class="close" title="close">×</a> You can select upto 4 locations
					</div>
				</div>
				<ul class="list desktop" id="desktop_ground_area">
					
				</ul>
				
				<?php echo $this->EBForm->input('area',array('class'=>'mobile','label'=>false,'style'=>'width: 100%; font-size: 24px;','div'=>false,'options'=>$area)); ?>
				
			</div>
			<div id="section3">
				<div class="center head">3. DATE</div>

				<a href="JavaScript:setSearchDate(1);" id="date_shortcut_today" class="date-shortcut active">
					Today
					<div class="date"><?php echo date('l, d F');?></div>
				</a>
				<a href="JavaScript:setSearchDate(2);" class="date-shortcut" id="date_shortcut_tomorrow">
					Tomorrow
					<div class="date"><?php echo date('l, d F',strtotime('+1 day',time()));?></div>
				</a>
				<br/>
				<span class="caption">&nbsp;</span>
				
				<div id="home_date_container">
				<div>
				
				</div>
				</div>
			<br/>
			<?php echo $this->EBForm->input('date',array('type'=>'hidden','value'=>date('Y-m-d'))); ?>
			<div stlye="display: none" id="all_areas">
			</div>
			<?php echo $this->EBForm->input('Visitor.latitude',array('type'=>'hidden','label'=>false)); ?>
			<?php echo $this->EBForm->input('Visitor.longitude',array('type'=>'hidden','label'=>false)); ?>
			<?php echo $this->EBForm->input('Visitor.url',array('type'=>'hidden','label'=>false)); ?>
			<input type="submit" id="find-venues" value="Find Venues" />
			</div>
		</div>
		<div class="wrap placeholder">
			<div class="box box1"><img src="<?php echo $this->webroot;?>img/banner1.jpg"></div>
			<div class="box box2"><img src="<?php echo $this->webroot;?>img/banner2.jpg"></div>
			<div class="box box3"><img src="<?php echo $this->webroot;?>img/banner3.jpg"></div>
		</div>
		<div class="clear"></div>
	</div>
	
	<script>
	var latitude, longitude;
$(document).ready(function(){
	updateLocation(7, 'badminton');
	if (navigator.geolocation) {
    	navigator.geolocation.getCurrentPosition(function(position) {  
		  latitude = position.coords.latitude;
		  longitude = position.coords.longitude;
		  $("#VisitorLatitude").val(latitude);
		  $("#VisitorLongitude").val(longitude);
		  updateSortLocation(7, 'badminton');
		});
        //navigator.geolocation.getCurrentPosition(showLocation);
    } else { 
        alert('Geolocation is not supported by this browser.');
    }
	
});

function updateSortLocation(groupId, groupDivId) {
	$('#GroundGroupId').val(groupId);
	$('.sport').removeClass('active');
	$('#'+groupDivId).addClass('active');
	$('#desktop_ground_area').html('<li>Loading...</li>');
	$.get(BASE_URL+"grounds/short_distance_area_list/"+groupId+"?lat="+latitude+"&long="+longitude, function(data, status){
		$('#desktop_ground_area').html(data);
		$('#all_areas').html(''); // empty hidden area input
		$("#desktop_ground_area li").each(function(n) {
			if (n < 4) {
				selectLocation($(this).children().attr('id'));
			}
      	});
	});
	$('#GroundArea').html('<option>Loading...</option>');
	$('#GroundArea').prop('disabled', 'disabled');
	$.get(BASE_URL+"grounds/short_distance_area/"+groupId+"?lat="+latitude+"&long="+longitude, function(data, status){
		$('#GroundArea').html(data);
		if(data != "")
			$('#GroundArea').prop('disabled', false);
	});
	
}

$(function() {
	var date = new Date();
	date.setDate(date.getDate());

	$('#home_date_container div').datepicker({
		format : 'yyyy-mm-dd',
		todayHighlight : true,
		startDate: date,
		weekStart : 1
	});
	$("#home_date_container div").on("changeDate", function(event) {
		$("#GroundDate").val($("#home_date_container div").datepicker('getFormattedDate'));
		$('#date_shortcut_today').removeClass('active');
		$('#date_shortcut_tomorrow').removeClass('active');
		
		$('#date_shortcut_tomorrow').removeClass('active');
		$('#date_shortcut_today').removeClass('active');
	});
});
function updateLocation(groupId, groupDivId) {
	if (typeof latitude != 'undefined' && latitude != '') {
	  updateSortLocation(groupId, groupDivId);
	} else {
		$('#GroundGroupId').val(groupId);
		$('.sport').removeClass('active');
		$('#'+groupDivId).addClass('active');
		$('#desktop_ground_area').html('<li>Loading...</li>');
		$.get(BASE_URL+"grounds/area_filter_list/"+groupId, function(data, status){
			$('#desktop_ground_area').html(data);
			$('#all_areas').html(''); // empty hidden area input
			$("#desktop_ground_area li").each(function(n) {
				if (n < 2) {
					selectLocation($(this).children().attr('id'));
				}
	      	});
		});
		$('#GroundArea').html('<option>Loading...</option>');
		$('#GroundArea').prop('disabled', 'disabled');
		$.get(BASE_URL+"grounds/area_filter/"+groupId, function(data, status){
			$('#GroundArea').html(data);
			if(data != "")
				$('#GroundArea').prop('disabled', false);
		});
	}
}

function selectLocation(locality) {

	if ($('#'+locality).hasClass('active')) {
		$('#'+locality).removeClass('active');
		$("#area_"+locality).remove();
		$('#all_areas').append('<input type="hidden">'); // Dummy input
		setUrlHiddenInput();
		$("#error-message").addClass('hide');
	} else {
		if ($('#desktop_ground_area .active').length <= 3) {
			$('#'+locality).addClass('active');
			$('#all_areas').append('<input type="hidden" value="'+$('#'+locality).text()+'" name="data[Ground][all_area]['+$('#all_areas').children().length+']" id="area_'+locality+'">');
			setUrlHiddenInput();
		} else {
			$("#error-message").removeClass('hide');
		}
	}
}

$(document).on('click', '.close', function(e) {
	e.preventDefault();
	$(this).parent().parent().addClass('hide');
});

function setUrlHiddenInput() {
	var all_areas_name = '';
	$.each($("#all_areas input[type='hidden']"), function (index, value) {
	    if ($(value).val() != '') {
	    	if (all_areas_name == '') {
	    		all_areas_name = $(value).val();
	    	} else {
	    		all_areas_name += '-'+$(value).val();
	    	}
	    }
	});
	console.log(all_areas_name);
	$('#VisitorUrl').attr('value', all_areas_name);
}

function setSearchDate(flag) {
	if(flag ==2) {
		$('#date_shortcut_today').removeClass('active');
		$('#date_shortcut_tomorrow').addClass('active');
		$("#GroundDate").val('<?php echo date('Y-m-d',strtotime('+1 day',time()));?>');
	} else if(flag ==1) {
		$('#date_shortcut_tomorrow').removeClass('active');
		$('#date_shortcut_today').addClass('active');
		$("#GroundDate").val('<?php echo date('Y-m-d');?>');
	}
	$('.table-condensed tbody tr td').removeClass('active');
	
}
function loadArea(){
	$('#GroundArea').html('<option>Loading...</option>');
	$('#GroundArea').prop('disabled', 'disabled');
	$.get(BASE_URL+"grounds/area_filter/"+$("#GroundGroupId").val(), function(data, status){
			$('#GroundArea').html(data);
			if(data != "")
				$('#GroundArea').prop('disabled', false);
	});
}

function searchPost(){
	var action_url = $("#contact-formm").attr("action");
	var sport_type = $('#section1 div.center a.active').attr('id');
	var location_search = $('#VisitorUrl').val();
	var location_search_res = location_search.replace("/", "_");
	var location_search_res_spaace = location_search_res.replace(" ", "_");
	var newParam = "/"+sport_type.toLowerCase()+"/"+location_search_res_spaace.toLowerCase();
	action_url += newParam;
	$("#contact-formm").attr("action", action_url);
	console.log(action_url);
    return true;
}

$(document).on('change', '#GroundArea', function(e){
	$('#all_areas').find('input:hidden').val($(this).val());
	$('#VisitorUrl').val($(this).val());
});


</script>

<style type="text/css">
.close {
	float: right;
	font-weight: bold;
}
.alert-danger {
    color: #a94442;
    background-color: #f2dede;
    border-color: #ebccd1;
}
.alert {
    /*padding: 15px;*/
    margin-bottom: 2px;
    border: 1px solid transparent;
    border-radius: 4px;
}
.hide {
	display: none;
}
</style>