<div class="inner-page">
		<div class="wrap">
			<div class="center">
				<div id="form-page">
					<div class="sign">
						<h3>Reset your password</h3>
			<?php echo $this->Form->create('User'); ?>
			<div class="form-element">
				<label>Current Password:</label>
				<?php
					echo $this->Form->input('email',array('placeholder'=>'Email','label'=>false,'type'=>'text'));
				?>
			</div>
			<div class="form-element submit">
				<input type="submit" value="Reset">
			</div>
			<?php echo $this->Form->end(); ?>
	<div class="clear"></div>
</div>
</div>

				<div class="clear"></div>
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
.login-page input[type='email'] {
	  margin-bottom:30px;
}
</style>