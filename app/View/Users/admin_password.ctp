<div class="container well">
<?php echo $this->EBForm->create('User',array('class'=>'ebsignup span4')); ?>
		<legend><?php echo __('Reset your password'); ?></legend>
	<?php
		echo $this->EBForm->input('old_pass',array('type'=>'password'));
		echo $this->EBForm->input('new_pass',array('type'=>'password'));
		echo $this->EBForm->input('password',array('type'=>'password'));
	?>
	<div class="control-group">
		<label class="control-label"></label>
			<?php echo $this->EBForm->end(array('label'=>'Reset','class'=>'btn btn-danger')); ?>
	</div>
</div>
