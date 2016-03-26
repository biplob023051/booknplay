<div class="span12 schedules form">
	<div class="row-fluid">
		<div class="block">
			<div class="categoriesform">
				<p class="block-heading"><?php echo __('Add Schedule'); ?></p>
				<div id="chart-container" class="block-body collapse in">
					<div class="row-fluid rowdata">
					<?php echo $this->EBHtml->link(__('Back to list'), array('action' => 'index'),array('class'=>'btn btn-primary eb-icon-back') ); ?>				</div>
					<div class="row-fluid">
					<?php echo $this->EBForm->create('Schedule',array('class'=>'form-horizontal','type'=>'file')); ?>					<div class="control-group">
							<?php
							echo $this->EBForm->input('date',array('type'=>'text'));							
							echo $this->EBForm->hidden('slots',array('id'=>'schedule_advance_ui'));
							echo $this->EBForm->hidden('prices',array('id'=>'schedule_advance_ui'));?>
							<?php 
							echo $this->EBForm->hidden('ground_id',array('value'=>$id));
							?>
							<div class="schedule_slot_ui">
							<div style="float:left">
							<div class="labeld">Slots / Prices</div>
							</div>
							<div style="float:left">
							<table cellpadding="5" cellspacing="5" border="0">
				            	<?php 
					            	for($j=1;$j<=Configure::read ( 'slots_per_day' );$j++){ 
				            	?>
								<tr>
								<td>
				                <input type="checkbox" id="<?php echo 's'.$j;?>" name="data[slots][<?php echo 's'.$j;?>]" value="<?php echo 's'.$j;?>" class='single_slot'/><label for="<?php echo 's'.$j;?>"><span><?php echo ((($j-1)<12)?((((($j-1)==0))?12:($j-1))):((((($j)==13))?12:($j-12-1)))).((($j-1)<12)?'a':'p');?></span></label>
								</td><td> / </td><td>
								<input type="number" id="<?php echo 's'.$j;?>" name="data[prices][<?php echo 's'.$j;?>]" placeholder="<?php echo 's'.$j;?>" value = '100' class='single_slot' maxlength="5" style="display: inline-block;width: 38px;height: 18px;margin: -1px 5px 0 0;vertical-align: middle;border: 1px solid #ccc;border-bottom: double #ccc;-webkit-border-radius: 2px;-moz-border-radius: 2px;border-radius: 2px;text-align: center;"/>
								
								</td>
								</tr>
				                <?php }?>
								</table>
								</div>
								<div style="clear:both"></div>
				            	
    						</div>
							
							
					</div>
						<div class="control-group"><?php echo $this->EBForm->end(array('label'=>__('Submit'), 'class'=>'btn btn-primary')); ?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
var slots = <?php echo Configure::read ( 'slots_per_day' );?>;
</script>
<?php echo $this->EBHtml->css('/adminpanel/stylesheets/schedule_slot.css');?>
<?php echo $this->EBHtml->script('/adminpanel/javascripts/schedule_slot.js');?>
<?php echo $this->EBHtml->script('jquery-ui.multidatespicker.js');?>
<script>
$(function() {
	$('#ScheduleDate').multiDatesPicker({
		dateFormat : 'yy-mm-dd'
	});
})
</script>


