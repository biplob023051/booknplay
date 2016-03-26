	<?php echo $this->EBHtml->css("nanoscroller.css");
	echo $this->EBHtml->script("main");
	echo $this->EBHtml->script("jquery.nanoscroller");
	?>
	<div id="price-outer" class="section">
		<div class="price_detail">
		<div class="wrap">


			<?php 
			echo $this->EBForm->create('Ground',array('action'=>'search','id'=>"search_ground_form",'class'=>'form-horizontal','type'=>'POST', 'onsubmit'=>"return searchPost()")); ?> 
			<?php echo $this->EBForm->input('locality',array('type'=>'hidden','label'=>false,'div'=>false,'value'=>'chennai')); ?>
			<?php echo $this->EBForm->input('group_id',array('type'=>'hidden','label'=>false,'div'=>false,'value'=>$reqData['Ground']['req_gid'])); ?>
			<?php echo $this->EBForm->input('area',array('type'=>'hidden','label'=>false,'value'=>$reqData['Ground']['area'])); ?>
			<?php echo $this->EBForm->input('Visitor.latitude',array('type'=>'hidden','label'=>false,'value'=>$reqData['Visitor']['latitude'])); ?>
			<?php echo $this->EBForm->input('Visitor.longitude',array('type'=>'hidden','label'=>false,'value'=>$reqData['Visitor']['longitude'])); ?>
			<?php echo $this->EBForm->input('Visitor.url',array('type'=>'hidden','label'=>false,'value'=>$reqData['Visitor']['url'])); ?>
			<?php if (!empty($reqData['Ground']['all_area'])) : ?>
				<?php foreach ($reqData['Ground']['all_area'] as $key => $value) : ?>
					<input type="hidden" value="<?php echo $value; ?>" name="data[Ground][all_area][<?php echo $key; ?>]" id="area_<?php echo $value . $key; ?>">
				<?php endforeach; ?>
			<?php endif; ?>
			<ul>
							<li class="" style="text-align:left">Filters: </li>
				<li>
					<img src="<?php echo $this->webroot;?>img/sport-icon1.png">
					<a href="#" id="selected_sports"><?php echo $reqData['Ground']['group_id'];?></a>
					<ul class="sub_menu" style="right:0px; margin-left: 0;left: inherit;">
						<?php foreach($groups as $sport_info_id => $sport_info) : ?>
							<li><a href="JavaScript:updateLocation('<?php echo $sport_info_id;?>','<?php echo str_replace(' ', '_', strtolower($sport_info));?>')" value="<?php echo $sport_info_id;?>" id="sports_<?php echo $sport_info_id;?>"><?php echo $sport_info;?></a></li>
						<?php endforeach;?>
					</ul>
				</li>
				<li><img src="<?php echo $this->webroot;?>img/location.png">
					<a href="#" id="selected_location"><?php echo $reqData['Ground']['area'];?></a>
					<ul class="sub_menu" id ="desktop_ground_area" style="max-height:250px;overflow:hidden">
						
					</ul>
				</li>
				<li style="text-align:right;">
					
						<?php
							$prev_date = date('Y-m-d', strtotime($reqData['Ground']['date'] .' -1 day'));
						?>
						<?php if ($prev_date >= date('Y-m-d')) : ?>
							<a href="JavaScript:setSearchDate('<?php echo date('l, d F', strtotime($reqData['Ground']['date']) - (24*60*60));?>', '<?php echo date('Y-m-d', strtotime($reqData['Ground']['date']) - (24*60*60));?>')" value="<?php echo date('l, d F', strtotime($reqData['Ground']['date']) - (24*60*60));?>" id="date_<?php echo date('l, d F', strtotime($reqData['Ground']['date']) - (24*60*60));?>"><b> &laquo;</b></a>
						<?php endif; ?>
						<span id="selected_date"> <?php echo date('l, d F', strtotime($reqData['Ground']['date']));?>
						</span>
						<a href="JavaScript:setSearchDate('<?php echo date('l, d F', strtotime($reqData['Ground']['date']) + (24*60*60));?>', '<?php echo date('Y-m-d', strtotime($reqData['Ground']['date'])+ (24*60*60));?>')" value="<?php echo date('l, d F', strtotime($reqData['Ground']['date']) + (24*60*60));?>" id="date_<?php echo date('l, d F', strtotime($reqData['Ground']['date']) + (24*60*60));?>"><b> &raquo;</b></a>
				</li>
			</ul>
			<?php echo $this->EBForm->input('date',array('type'=>'hidden','label'=>false,'div'=>false,'value'=>$reqData['Ground']['date'])); ?>
			</form>
		</div>
		</div>
		<div class="wrap">
			<div class="center">

				<?php foreach($grounds as $ground) : ?>
				<div class="booking_list detail-box">
				<div class="container_12">
					<div class="top-section">
						<div class="left bl_info">
							<strong><?php echo $ground['Ground']['name'];?>| <span class="<?php echo (!empty($ground['Ground']['gallery']))?"fbg":"";?> fa fa-picture-o fa-1x"></span></strong><br>
							<i><?php echo $ground['Type']['type'];?></i>
							<p><?php echo $ground['Ground']['address_line_1'];?><br>
								<?php echo $ground['Ground']['address_line_2'];?> <?php echo $ground['Ground']['locality'];?><br>
								<!-- <?php echo $ground['Ground']['phone'];?><br> -->
								
							</p>
							<div class='galla' style="display:none;">
								<?php if(!empty($ground['Ground']['gallery'])){
								foreach($ground['Ground']['gallery'] as $k=>$image){?>
									<a href="<?php echo $this->webroot.$image;?>" data-lightbox='galla'></a>                                        	
								<?php }}?>
							</div>
						</div>
						<div class="right">
							<strong>Rs.<?php echo (isset($ground['Ground']['entry1_value']))?((isset($ground['Ground']['entry2_value']))?$ground['Ground']['entry1_value']." / Rs.".$ground['Ground']['entry2_value']:$ground['Ground']['entry1_value']):"3x00";?> 
							
							<img class="question" src="<?php echo $this->webroot;?>img/qquestion.png">
							</strong>
							<br>
							<i><b><?php if(!empty($ground['Ground']['price_description'])) { echo $ground['Ground']['price_description'];} else { echo '(Pay Rs.100 per slot online and the rest when you reach the court)';} ?> </b></i>
							<div class="offer-price" style="display:block;">
								<span><?php echo $ground['Ground']['entry1_label'];?></span><strong>Rs. <?php echo $ground['Ground']['entry1_value'];?></strong><br>
								<span><?php echo trim($ground['Ground']['entry2_label']);?></span><strong>Rs. <?php echo $ground['Ground']['entry2_value'];?></strong>
							</div>
							
							
							
							<br>
							<a href="javascript:void(0)" class="close_slot" style="display:none;"></a>
							<!--<a href="javascript:void(0)" class=" view_slot">view slot</a>-->
							<button class="normal-button small reverse view_slot slot-btn" value='<?php echo $ground['Ground']['id'];?>'>View Slots</button>
							
						</div>
						<div class="clear"></div>
					</div>
					<div class="happy_hours">
						<p><?php echo (!empty($ground['Ground']['offer']))?$ground['Ground']['offer']:"";?></p>
					</div>
					<div class='ajax_bl_parent'>
						<form class='book_in_bl' action="<?php echo $this->webroot;?>bookings/payment" method="POST" id="booking_form_id_<?php echo $ground['Ground']['id'];?>">
							<input type="hidden" name="data[Booking][ground_id]" value="<?php echo $ground['Ground']['id'];?>" />
							<?php echo $this->EBForm->input('date',array('type'=>'hidden','label'=>false,'div'=>false,'value'=>$reqData['Ground']['date'])); ?>
							<div class='ajax_bl'></div>
							<div style="float:right;"><input class="normal-button small reverse book_sumbit_bl continue" type='submit' name='Book' value='Book' style="margin-top:0px;"></div>
							<div class="clear"></div>
						</form>
					</div>
					</div>
				</div>
				
				<!-- detail box end -->
				<?php endforeach; ?>
				</div>
			
			<div class="clear"></div>
			<br>
			
		</div>
		</div>
		
		<script>
var latitude = '', longitude = '';
var data_url = '<?php echo $reqData["Visitor"]["url"]; ?>';
<?php if (!empty($reqData['Visitor']['latitude']) && !empty($reqData['Visitor']['longitude'])) : ?>
	latitude = <?php echo $reqData['Visitor']['latitude']; ?>;
	longitude = <?php echo $reqData['Visitor']['longitude']; ?>;
<?php endif; ?>
$(document).ready(function(){
	if ((latitude != '') && (longitude != '')) {
		updateSortLocation('<?php echo $reqData['Ground']['req_gid'];?>', '<?php echo $reqData['Ground']['group_id'];?>');
	} else {
		updateLocation('<?php echo $reqData['Ground']['req_gid'];?>', '<?php echo $reqData['Ground']['group_id'];?>');
	}
});

$(function() {
	$('#home_date_container .input-group.date').datepicker({format : 'yyyy-mm-dd',todayHighlight : true});
	$("#home_date_container .input-group.date").on("changeDate", function(event) {
		$("#GroundDate").val($("#home_date_container div").datepicker('getFormattedDate'));
		$('#date_shortcut_tomorrow').removeClass('active');
		$('#date_shortcut_today').removeClass('active');
	});
});

function updateSortLocation(groupId, groupDivId) {
	$('#GroundGroupId').val(groupId);
	$('#desktop_ground_area').html('<li>Loading...</li>');
	$.get(BASE_URL+"grounds/short_distance_area_list/"+groupId+"/"+latitude+"/"+longitude, {data_url: data_url}, function(data, status){
		$('#desktop_ground_area').html(data);
	});
}

function updateLocation(groupId, groupDivId) {
	$('#selected_sports').text($('#sports_'+groupId).text());
	if ((latitude != '') && (longitude != '')) {
		updateSortLocation(groupId, groupDivId);
	} else {
		$('#GroundGroupId').val(groupId);
		$('#desktop_ground_area').html('<li>Loading...</li>');
		$.get(BASE_URL+"grounds/area_filter_list/"+groupId, {data_url: data_url}, function(data, status){
			$('#desktop_ground_area').html(data);
			//selectLocation('ambattur0');
		});
	}
}

function selectLocation(locality) {
	$("#GroundArea").val($('#'+locality).text());
	$("#selected_location").text($('#'+locality).text());
	$('#VisitorUrl').val($('#'+locality).text());
	$("#search_ground_form").submit();
}

function setSearchDate(cdate, cdateforhidden) {
	$("#selected_date").text(cdate);
	$("#GroundDate").val(cdateforhidden);
	$("#search_ground_form").submit();
	
}

function searchPost(){
	var action_url = $("#search_ground_form").attr("action");
	var sport_type = $('#selected_sports').text();
	var sport_type = sport_type.replace(" ", "_");
	var location_search = $('#VisitorUrl').val();
	var location_search_res = location_search.replace("/", "_");
	var location_search_res_spaace = location_search_res.replace(" ", "_");
	var newParam = "/"+sport_type.toLowerCase()+"/"+location_search_res_spaace.toLowerCase();
	action_url += newParam;
	$("#search_ground_form").attr("action", action_url);
	console.log(action_url);
    return true;
}
</script>
 <style>
.fbg{
	cursor:pointer;
}
</style>
<script>
	$(document).ready(function(){
		$('.fbg').click(function(){
		$(this).closest('.bl_info').find('.galla a').first().trigger('click')
			setTimeout(function(){
				$('.lb-nav a.lb-prev').css('background-position-y','49%');								
			},2000);
		});
	});
</script>

<style type="text/css">
	.price_detail ul li ul li a.active {
	    color: #ffffff !important;
	    background: #33629a;
	}
</style>