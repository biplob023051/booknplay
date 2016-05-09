<div class="span12 coupons form">
	<div class="row-fluid">
		<div class="block">
			<div class="categoriesform">
				<p class="block-heading"><?php echo __('Add Coupon'); ?></p>
				<div id="chart-container" class="block-body collapse in">
					<div class="row-fluid rowdata">
					<?php echo $this->EBHtml->link(__('Back to list'), array('action' => 'index'),array('class'=>'btn btn-primary eb-icon-back') ); ?>				</div>
					<div class="row-fluid">
					<?php echo $this->EBForm->create('Coupon',array('class'=>'form-horizontal','type'=>'file')); ?>					<div class="control-group">
							<?php
								echo $this->EBForm->input('code', array('placeholder' => __('Enter coupon code')));
								echo $this->EBForm->input('amount', array('placeholder' => __('Enter discount amount')));
								echo $this->EBForm->input('applicable_for', array('options' => $userOptions));
								echo $this->EBForm->input('not_applicable_grounds', array('placeholder' => __('Enter ground ids comma separated')));
							?>
	
					</div>
						<div class="control-group"><?php echo $this->EBForm->end(array('label'=>__('Submit'), 'class'=>'btn btn-primary')); ?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<style type="text/css">
.error-message {
	color: red;
}
</style>