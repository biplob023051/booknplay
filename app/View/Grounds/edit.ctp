<div class="span12 grounds form">
	<div class="row-fluid">
		<div class="block">
			<div class="categoriesform">
				<p class="block-heading"><?php echo __('Edit Ground'); ?></p>
				<div id="chart-container" class="block-body collapse in">
					<div class="row-fluid rowdata">
					<?php echo $this->EBHtml->link(__('Back to list'), array('action' => 'index'),array('class'=>'btn btn-primary eb-icon-back') ); ?>				</div>
					<div class="row-fluid">
					<?php echo $this->EBForm->create('Ground',array('class'=>'form-horizontal','type'=>'file')); ?>					<div class="control-group">
							<?php
		echo $this->EBForm->input('id');
		echo $this->EBForm->input('name');
		echo $this->EBForm->input('type_id');
		echo $this->EBForm->input('address_line_1');
		echo $this->EBForm->input('address_line_2');
		echo $this->EBForm->input('count');
		echo $this->EBForm->input('locality');
		echo $this->EBForm->input('city');
		echo $this->EBForm->input('state');
		echo $this->EBForm->input('pin');
		echo $this->EBForm->input('phone');
		echo $this->EBForm->input('active');
		echo $this->EBForm->input('user_id');
		echo $this->EBForm->input('rating');
		echo $this->EBForm->input('entry1_label');
		echo $this->EBForm->input('entry1_value');
		echo $this->EBForm->input('entry2_label');
		echo $this->EBForm->input('entry2_value');
		echo $this->EBForm->input('tips');
		echo $this->EBForm->input('offer');
		echo $this->EBForm->input('google_maps');
		echo $this->EBForm->input('latitude');
		echo $this->EBForm->input('longitude');
		echo $this->EBForm->input('price_description');
		
	?>
	
					</div>
						<div class="control-group"><?php echo $this->EBForm->end(array('label'=>__('Submit'), 'class'=>'btn btn-primary')); ?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>