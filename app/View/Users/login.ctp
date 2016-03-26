<div class="inner-page">
		<div class="wrap">
			<div class="center">
				<div id="form-page">
					<div class="sign">
					<h3>Sign In</h3>
<?php echo $this->Form->create('User'); ?>
	
	<div class="form-element">
		<label>Email Address:</label>
		<?php echo $this->Form->input('username',array('placeholder'=>'Username','label'=>false));?>
	</div>
	<div class="form-element">
		<label>Password:</label>
		<?php echo $this->Form->input('password',array('placeholder'=>'Password','label'=>false));?>
	</div>
	<div class="form-element submit">
		<input type="submit" value="Sign In">
	</div>
	<div style="text-align:center;">
		<a href='<?php echo $this->webroot;?>users/forget'>Forgot your password ?</a>
	</div>
	
	<?php echo $this->Form->end(); ?>

				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>
	</div>
<style>
.login-page input[type='submit'] {
	  background-color: #0dbff2;
}
.login-page input[type='text'] {
	  margin-bottom:30px;
}
</style>