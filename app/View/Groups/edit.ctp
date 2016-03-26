<div class="span12 groups form">
	<div class="row-fluid">
		<div class="block">
			<div class="categoriesform">
				<p class="block-heading"><?php echo __('Edit Group'); ?></p>
				<div id="chart-container" class="block-body collapse in">
					<div class="row-fluid rowdata">
					<?php echo $this->EBHtml->link(__('Back to list'), array('action' => 'index'),array('class'=>'btn btn-primary eb-icon-back') ); ?>				</div>
					<div class="row-fluid">
					<?php echo $this->EBForm->create('Group',array('class'=>'form-horizontal','type'=>'file')); ?>					<div class="control-group">
							<?php
		echo $this->EBForm->input('id');
		echo $this->EBForm->input('type_group');
	?>
	
					</div>
						<div class="control-group"><?php echo $this->EBForm->end(array('label'=>__('Submit'), 'class'=>'btn btn-primary')); ?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>