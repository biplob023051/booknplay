<div class="users form">
<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Reset your password'); ?></legend>
	<?php
		echo $this->Form->input('username');
		echo $this->Form->input('password');
		echo $this->Form->input('confirm_password');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Reset')); ?>
</div>
