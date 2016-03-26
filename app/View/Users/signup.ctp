<div class="inner-page">
		<div class="wrap">
			<div class="center">
				<div id="form-page">
					<div class="sign">
						<h3>Sign Up</h3>
						
<?php echo $this->Form->create('User',array('id'=>'contact-form')); ?>
	
	<div class="form-element">
		<label>Your Name:</label>
		<?php
		echo $this->Form->input('display_name',array('label'=>false,'type'=>'text','required'=>'required','maxlength'=>'25'));
		?>
	</div>
	
	<div class="form-element">
		<label>Email Address:</label>
		<?php
		echo $this->Form->input('email',array('label'=>false,'type'=>'text','required'=>'required','pattern'=>'[^@]+@[^@]+\.[a-zA-Z]{2,6}'));
		?>
	</div>
	
	
	
	<div class="form-element">
		<label>Phone Number:</label>
		<?php
		echo $this->Form->input('phone',array('type'=>'text','label'=>false,'required'=>'required','maxlength'=>'10','pattern'=>'[789][0-9]{9}'));
		
		?>
	</div>

	<div class="form-element">
		<label>Age:</label>
		<?php
		echo $this->Form->input('age',array('type'=>'text','label'=>false,'required'=>'required','maxlength'=>'2','pattern'=>'[0-9]{2}'));
		
		?>
	</div>
	
	
	<div class="form-element">
		<label>Password:</label>
		<?php
		echo $this->Form->input('password',array('label'=>false,'required'=>'required','maxlength'=>'10'));
		?>
	</div>
	<div class="form-element submit">
		<input type="submit" value="Sign Up">
	</div>
	<?php echo $this->Form->end(); ?>
	<div class="clear"></div>
</div>
</div>

				<div class="clear"></div>
			</div>
		</div>
</div>
	
