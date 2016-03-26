<div class="span12 bookings form">
	<div class="row-fluid">
		<div class="block">
			<div class="categoriesform">
				<p class="block-heading"><?php echo __('Edit Booking'); ?></p>
				<div id="chart-container" class="block-body collapse in">
					<div class="row-fluid rowdata">
					<?php echo $this->EBHtml->link(__('Back to list'), array('action' => 'index'),array('class'=>'btn btn-primary eb-icon-back') ); ?>				</div>
					<div class="row-fluid">
					<?php echo $this->EBForm->create('Booking',array('class'=>'form-horizontal','type'=>'file')); ?>					<div class="control-group">
							<?php
		echo $this->EBForm->input('id');
		echo $this->EBForm->input('status');
		echo $this->EBForm->input('payment_method');
		echo $this->EBForm->input('initiator');
		echo $this->EBForm->input('amount');
		echo $this->EBForm->input('ground_id');
	?>
	
					</div>
						<div class="control-group"><?php echo $this->EBForm->end(array('label'=>__('Submit'), 'class'=>'btn btn-primary')); ?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>