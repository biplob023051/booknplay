<script type="text/javascript">
	$(document).ready(function(){

		$('#call_us').click(function(){
			$('.cities_call').slideToggle();
		});

			

		$('.close_offer').click(function() {
			$('.offer-image').hide();
		});
		
		$('#sub_dropdown').click(function(){
				$('ul.dropdown-box').slideToggle();
			});


	});


</script>


<div id="header">
		<div class="top_header">
			<div class="wrap">
				<span class="left">Call us on: <b id="call_us">Chennai +91 95-00-948983</b>
					<ul class="cities_call arrow_box" style="display:none;">
						<li>Chennai +91 95-00-948983</li>
						<li>Bangalore +91 95-00-948983</li>
					</ul>
				</span>
				
											
											
				<div class="right login_detail">
					<?php if($role == 'guest' || empty($role)){ ?>
						<ul style="list-style-type: none;"> 
							<li style="padding: 10px 3px 10px 0;display: inline;"><a href="<?php echo $this->Html->url('/');?>users/login">Sign In</a></li>
							<li style="padding: 10px 0 10px 3px;display: inline;"><a href="<?php echo $this->Html->url('/');?>users/signup">Sign Up</a></li>
						</ul>
					<?php } else { ?>
						
						<div id="sub_dropdown" class="right">Hi <b id="user-name"><?php echo $display_name;?></b>
							<ul class="dropdown-box arrow_box" style="display:none;">
					
						<?php if($role == 'user'){ ?>
							<li><a href="<?php echo $this->Html->url('/');?>bookings/my_books">Bookings</a></li>
							<li><a href="<?php echo $this->Html->url('/');?>users/change_password">Change Password</a></li>
							<li><a href="<?php echo $this->Html->url('/');?>users/logout">Logout</a></li>
						<?php } else { ?>
								<li><a href="<?php echo $this->Html->url('/');?>grounds">Dashboard</a></li>
								
								<li><a href="<?php echo $this->Html->url('/');?>users/logout">Logout</a></li>
						<?php } ?>
							</ul>
						</div>
						<?php } ?>
						<div class="offer-image" style="display:none">
							<span><b>OFFER</b>
							<br>
							₹ 50 Cashback<br>
							Offer applicable only to New users!!</span>
							<span class="close_offer"></span>
						</div>
				</div>
			</div>

		</div>

		<div class="clear"></div>
		<div class="main_header">
			<div class="wrap">
				<div class="left">
					<a href="<?php echo $this->Html->url('/');?>"><img src="<?php echo $this->webroot;?>img/booknplay.svg" alt="Book N Play" /></a>
				</div>
				<div class="right">
					<span class="league">
						<?php 
						$active_class_t = $active_class_lt = '';
						if($this->params['controller'] == 'tournaments') {
							$active_class_t = "active";
						} else {
							$active_class_lt = "active";
						}
						?>
						<a href="<?php echo $this->Html->url('/tournaments');?>" class="<?php echo $active_class_t;?>" style="margin-right:65px;">Tournaments</a>
						<a href="<?php echo $this->Html->url('/');?>" class="<?php echo $active_class_lt;?>">Let's Play</a>
						<!--<a href="#">Meetups</a>-->
					</span>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>
	 <?php if($this->action == 'display'){?>
		<script>
		$(window).load(function() {
				$('.offer-image').show();
		});
		</script>
	 <?php } ?>