<?php 
	echo $this->EBHtml->css('/css/admin_style.css');
?>
<div class="span12 bookedSlots form">
	<div class="row-fluid">
		<div class="block">
			<div class="categoriesform">
				<p class="block-heading"><?php echo __('Add Booked Slot'); ?></p>
				<div id="chart-container" class="block-body collapse in">
					<div class="row-fluid rowdata">
					<?php echo $this->EBHtml->link(__('Back to list'), array('controller'=>'bookings' ,'action' => 'index'),array('class'=>'btn btn-primary eb-icon-back') ); ?>				</div>
					
                    <?php if(isset($show_ground)){?>
					<div class="row-fluid">
					<?php echo $this->EBForm->create('BookedSlot',array('url'=>array('1'),'class'=>'form-horizontal','type'=>'file')); ?>					<div class="control-group">
					<?php 
                    	//Setting Date
						$datelist = array();
						for($i=0;$i<14;$i++){
							$datelist[date('Y-m-d',strtotime('+'.$i.' days'))] = date('jS M y',strtotime('+'.$i.' days'));
						}
                    	echo $this->EBForm->input('tempdate',array('label'=>'Date','id'=>'startDate','options'=>$datelist,'empty'=>'Select Date','class'=>'change_ground')); 
                    	?>
					<?php	echo $this->EBForm->input('ground_id',array('id'=>'just_ground','class'=>'change_ground'));?>
					</div>
					<?php }else{?>					
					<div class="row-fluid">
					<?php echo $this->EBForm->create('Booking',array('controller'=>'bookings','action'=>'payment','class'=>'form-horizontal','type'=>'file')); ?>
                    <div class="control-group">
                        <?php	echo $this->EBForm->hidden('ground_id',array('value'=>$gid));?>
                        <?php	echo $this->EBForm->hidden('user_id',array('value'=>$this->Session->read("Auth.User.id")));?>
                        <!-- Booking Layout -->
                        <div id="ajax_bl">Loading...</div>
					</div>
						<div class="control-group"><?php echo $this->EBForm->end(array('label'=>__('Submit'), 'class'=>'btn btn-primary')); ?></div>
					</div>
					<script>
						//Booking Layout
					    $.get("<?php echo $this->webroot; ?>grounds/booking_layout/<?php echo $gid; ?>/<?php echo $start_date; ?>/<?php echo $count; ?>", function(data, status){
													$("#ajax_bl").html(data);
													$('#no_of_court').unbind('change');
												    $('#no_of_court').change(function(){
												    	window.location = BASE_URL+"booked_slots/add_slots/<?php echo $gid; ?>/<?php echo $start_date; ?>/"+$(this).val();
												    });
													bl_init();
						});
					</script>
                    <?php }?>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
    $(".change_ground").change(function(){
		window.location = BASE_URL+"booked_slots/add_slots/"+$('#just_ground').val()+"/"+$('#startDate').val()+"/"+"1";    
    });
</script>