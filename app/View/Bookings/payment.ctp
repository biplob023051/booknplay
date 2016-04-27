<div class="inner-page">
		<div class="wrap">
			<div class="center">
				<div class="inner_content" id="payment-page">
					<form action="<?php echo $this->webroot."bookings/process_book"?>" id="contact-form" method="post">
						<?php 
							//pr($reqData);
							foreach($reqData['slots'] as $slot){
								echo "<div><input type='hidden' name='data[slots][".$slot."]' value='".$slot."' /></div>";
							}
							echo "<div><input type='hidden' name='data[ground_id]' value='".$reqData['Ground']['id']."' /></div>";
							
							echo "<div><input type='hidden' name='data[selected_court]' value='".$reqData['Booking']['selected_court']."' /></div>"; 
						?>
							<input type='hidden' name='data[total_fare]' value='<?php if(!empty($final_calc['total'])){echo $final_calc['total'];}else{echo "0";}?>' />
							
							<input type='hidden' name='data[date]' value='<?php if(!empty($reqData['Ground']['date'])){echo $reqData['Ground']['date'];}else{echo "0";}?>' />
						
							
							
						<div class="left left-block">
							<h3><?php echo $reqData['Ground']['name'];?>
								<i><?php echo $reqData['Type']['type'];?></i>
							</h3>
							<p><?php echo $reqData['Ground']['address_line_1'];?> <br>
								<?php echo $reqData['Ground']['address_line_2'];?><br>
								<?php echo $reqData['Ground']['phone'];?>
							</p>
							
								<ul class="select-slots">
									<li>
										<span>Number of Courts: </span><input type='text' readonly="true" name='data[selected_court]' value='<?php echo $reqData['Booking']['selected_court'];?>' />
									</li>	
									<li>
										<span>Selected Slots:</span>
										<span id='slots_sum' style="display:none;">
										<?php if(!empty($reqData['processed_slots'])) {
											echo implode(",",$reqData['processed_slots']);
										} ?>
										</span>
										<?php if(!empty($reqData['processed_slots'])){
											foreach($reqData['processed_slots'] as $slotd) { ?>
												<a class="select" href="javascript:void(0)"><?php echo date("g",strtotime($slotd));?> - <?php echo date("g a",strtotime($slotd)+ 60*60);?></a>
										<?php	
											}
											
										} ?>
										
									</li>
									
									<?php  if(AuthComponent::user('id') && ((AuthComponent::user('role') == 'admin') || (AuthComponent::user('role') == 'gowner'))):?>
										<li>
											<span>Total Fare:</span> <strong class='price_os'>Rs.<input type="number" name="changed_amount" id="changed_amount" min="1" placeholder="New price" value="<?php echo empty($final_calc['total']) ? 0 : $final_calc['total']; ?>"></strong>
										</li>
									<?php else : ?>
										<li>
											<span>Total Fare:</span> <strong class='price_os'>Rs.<?php if(!empty($final_calc['total'])){echo $final_calc['total'];}else{echo "0";}?></strong>
										</li>
									<?php endif ?>
									<li>
										<span>Selected Date:</span> <strong class='price_os'><?php echo $reqData['Ground']['date']; ?></strong>
									</li>
								</ul>
								
						</div>
						<div class="sign right">
						<h3>Enter your details</h3>
						<form>
							<div class="form-element">
								<label>Your Name:</label>
								<input maxlength="25" required type="text" name="data[User][display]" value="<?php echo (isset($user['User']['display_name']))?$user['User']['display_name']:"";?>" data-message="Name" class="requiredfield" placeholder="Your Name" />
							</div>
							<div class="form-element">
								<label>Email Address:</label>
								<input required type="text" pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" name="data[User][email]" id="email" value="<?php echo (isset($user['User']['email']))?$user['User']['email']:"";?>" data-message="E-main" data-email="Please enter a valid email." class="requiredfield email" placeholder="Your Email" />
							</div>
							<div class="form-element">
								<label>Phone Number:</label>
								<input maxlength="10" pattern="[789][0-9]{9}" required type="text" name="data[User][phone]" id="phone" value="<?php echo (isset($user['User']['phone']))?$user['User']['phone']:"";?>" placeholder="Your Phone" />
							</div>
							
							<div class="form-element">
								<label>Age:</label>
								<input maxlength="2" pattern="[0-9]{2}" required type="text" value="25" name="data[User][age]" id="age" value="<?php echo (isset($user['User']['age']))?$user['User']['age']:"";?>" placeholder="Age" />
							</div>
							
							<!--<div class="form-element">
								<input required type="radio" id="pp-radio-sex-1" name="data[User][sex]" value="male" checked="checked" />
								<label for="pp-radio-sex-1"><span></span>Male</label>
								<input required type="radio" id="pp-radio-sex-2" name="data[User][sex]" value="female" />
								<label for="pp-radio-sex-2"><span></span>Female</label>
							</div>-->
							
								<?php if($role != 'gowner' && $role != 'admin'){?>
									<input required type="hidden" id="pp-radio-payment-1" name="payment_method" value="PAYU" checked="checked" />
							
								<?php } else {?>
									<input required type="hidden" id="pp-radio-payment-2" name="payment_method" value="DIRECT" checked="checked" />
							
								<?php } ?>
							<div class="form-element submit">
								<?php  if(AuthComponent::user('id') && ((AuthComponent::user('role') == 'admin') || (AuthComponent::user('role') == 'gowner'))):?>
									<div class="left width-50">
										<input type="hidden" name="submitted" id="submitted" value="true" />
										<input type="submit" class="inline-button" value="Proceed to Payment">
									</div>
									<div class="left width-50">
										<input type="submit" class="inline-button" name="request_payment" value="Request Payment">
									</div>
								<?php else : ?>
									<input type="hidden" name="submitted" id="submitted" value="true" />
									<input type="submit" value="Proceed to Payment">
								<?php endif; ?>
							</div>
						</form>
						<div class="clear"></div>
					</div>

				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>
<script>
$(document).ready(function(){
var data = $('#slots_sum').text();
var arr = processArr(data.split(','));
$('#slots_sum').text(arr.join());
});
function processArr(array){
	var s=array.sort();
	var r=[];
	var count=1;
	for (var i = 0; i <s.length ; i++) {
		if (s[i] == s[i+1]) 
		{
			count++;
		}else{
    	r.push(s[i]+' ('+count+')');
		count=1;
   		}
	}
	return r;
}
</script>

<style type="text/css">
	#changed_amount {
	    width: 100px;
	    height: 30px;
	    text-align: left;
	    margin-left: 5px;
	    padding-left: 5px;
	}
	.width-50 {
		width: 48%;
		margin: 0 1%;
	}

	/*.form-element input[type="submit"]*/
	.form-element .width-50 input[type="submit"] {
	    background: #f59f48;
	    border-radius: 4px;
	    color: #ffffff;
	    border: 1px solid #db8f43;
	    min-width: 100px;
	    height: 40px;
	    line-height: 36px;
	    display: inline-block;
	    text-align: center;
	    font-size: 14px;
	    text-transform: capitalize;
	    box-shadow: 0 -2px 0 #db8f43 inset;
	}
</style>