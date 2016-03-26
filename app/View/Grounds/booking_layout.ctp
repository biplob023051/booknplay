<div class="chart" style="display:block !important;">
	<div class="courts">
		Number of courts
		<select style="" data-gid="<?php echo $ground_details['Ground']['id'];?>" data-startdate="<?php echo $start_date;?>" id="no_of_court"><?php for($i=1;$i<=$ground_details['Ground']['count'];$i++){$selected = "";if($i == $selected_court){$selected='selected';}echo "<option ".$selected." value='".$i."'>".$i."</option>";}?></select>
<i><font color="black"><b>Note: <?php echo $ground_details['Ground']['tips'];?></b></font></i>
	</div>
	<input type="hidden" name="data[Booking][selected_court]" value="<?php echo $selected_court;?>" />
	<?php
		$selected_inx = 1;
		$check_available_for_selected = array();
		for($i=1;$i<=$count_no;$i++) { 
			$check_available_for_today = array();
			$slot = explode(',',$slots[date('Y-m-d',strtotime((($i+$start_date_no)-1)." days"))]);
			if(date('Y-m-d',strtotime((($i+$start_date_no)-1)." days")) == $start_date) {
				$selected_inx = $i;
			}
			for($j=1;$j<=Configure::read ( 'slots_per_day' );$j++) {
				//Checking Availability
            	if($slot[$j-1] == 1) {
					$check_available_for_today['s'.($i+$start_date_no).sprintf ("%02u", $j)] = 'class="available single_slot"';
				} else {
					$check_available_for_today['s'.($i+$start_date_no).sprintf ("%02u", $j)] = 'class="unavailable single_slot"';
				}	
            	//Check Validation
            	if(date('Y-m-d',strtotime((($i+$start_date_no)-1)." days")) == date('Y-m-d')){
            		if((date('H')+2) > $j){
            			$check_available_for_today['s'.($i+$start_date_no).sprintf ("%02u", $j)] = 'class="unavailable single_slot"';
            		}
            	}
			}
			$check_available_for_selected[$i] = $check_available_for_today;
		}
		$check_available_for_today = $check_available_for_selected[$selected_inx];
	?>
	<div class="pricing">
		<table border="0" class="booking_detail">
			<tr>
				<td class="timily">
					<i><img src="<?php echo $this->webroot;?>img/day-icon2.png"></i>
					DAWN</td>
				<?php 
				$morning_arr = array('01', '02', '03', '04', '05', '06');
				foreach($check_available_for_today as $avl_key => $avl_value) {
					if(in_array(substr($avl_key, -2), $morning_arr)) {
						$start_hrtime= (substr($avl_key, -2)-1);
						if($start_hrtime==0) {
							$start_hrtime==12;
						}
				?>
						<td <?php echo $avl_value;?> id="<?php echo $avl_key;?>" value="<?php echo $avl_key;?>"><span><?php 
						if($start_hrtime==0) {
							echo '12';
						} else {
							echo $start_hrtime;
						}
						?>-<?php echo ($start_hrtime+01);?> AM</span></td>
				<?php }
				} ?>
				
			</tr>
			<tr>
				<td class="timily">
					<i><img src="<?php echo $this->webroot;?>img/day-icon1.png"></i>MORNING  				
				</td>
				<?php 
				$morning_arr = array('07', '08', '09', '10', '11', '12');
				foreach($check_available_for_today as $avl_key => $avl_value) {
					if(in_array(substr($avl_key, -2), $morning_arr)) {
						$start_hrtime= (substr($avl_key, -2)-1);
				?>
						<td <?php echo $avl_value;?> id="<?php echo $avl_key;?>" value="<?php echo $avl_key;?>"><span><?php echo ($start_hrtime > 12) ? ($start_hrtime - 12): $start_hrtime;?>-<?php echo (($start_hrtime+01) > 12) ? (($start_hrtime+01) - 12): ($start_hrtime+01);?>
						<?php if($start_hrtime==11) { 
								echo "Noon";
							} else if($start_hrtime>=12) {
								echo "PM";
							} else {
								echo "AM";
							} ?></span>
						</td>
				<?php }
				} ?>
				
			</tr>
			<tr>
				<td class="timily">
					<i><img src="<?php echo $this->webroot;?>img/day-icon1.png"></i>EVENING
				</td>
				<?php 
				$morning_arr = array('13', '14', '15', '16', '17', '18');
				foreach($check_available_for_today as $avl_key => $avl_value) {
					if(in_array(substr($avl_key, -2), $morning_arr)) {
						$start_hrtime= (substr($avl_key, -2)-1);
				?>
						<td <?php echo $avl_value;?> id="<?php echo $avl_key;?>" value="<?php echo $avl_key;?>"><span><?php echo ($start_hrtime > 12) ? ($start_hrtime - 12): $start_hrtime;?>-<?php echo (($start_hrtime+01) > 12) ? (($start_hrtime+01) - 12): ($start_hrtime+01);?>
						<?php if($start_hrtime==11) { 
								echo "Noon";
							} else if($start_hrtime>=12) {
								echo "PM";
							} else {
								echo "AM";
							} ?></span></td>
				<?php }
				} ?>
				
			</tr>
			<tr>
				<td class="timily">
					<i><img src="<?php echo $this->webroot;?>img/day-icon3.png"></i>NIGHT
				</td>
				<!--<?php 
				$morning_arr = array('19', '20', '21', '22', '23', '24');
				//krsort($check_available_for_today);
				//pr($check_available_for_today);
				foreach(array_slice($check_available_for_today, 22) as $avl_key => $avl_value) {
					if(in_array(substr($avl_key, -2), $morning_arr)) {
						$start_hrtime= (substr($avl_key, -2)-1);
				?>
						<td <?php echo $avl_value;?> id="<?php echo $avl_key;?>" value="<?php echo $avl_key;?>"><span><?php 
						if($start_hrtime==0) {
							echo '12';
						} else {
							echo ($start_hrtime > 12) ? ($start_hrtime - 12): $start_hrtime;
						}
						
						?>-<?php echo (($start_hrtime+01) > 12) ? (($start_hrtime+01) - 12): ($start_hrtime+01);?>
						<?php if($start_hrtime==23) { 
								echo "Zzz...";
							} else if($start_hrtime>=12) {
								echo "PM";
							} else {
								echo "AM";
							} ?></span></td>
				<?php }
				} ?>-->
				
				<?php 
				$morning_arr = array('19','20','21', '22', '23', '24');
				//krsort($check_available_for_today);
				//pr($check_available_for_today);
				foreach($check_available_for_today as $avl_key => $avl_value) {
					if(in_array(substr($avl_key, -2), $morning_arr)) {
						$start_hrtime= (substr($avl_key, -2)-1);
				?>
						<td <?php echo $avl_value;?> id="<?php echo $avl_key;?>" value="<?php echo $avl_key;?>"><span><?php 
						if($start_hrtime==0) {
							echo '12';
						} else {
							echo ($start_hrtime > 12) ? ($start_hrtime - 12): $start_hrtime;
						}
						
						?>-<?php echo (($start_hrtime+01) > 12) ? (($start_hrtime+01) - 12): ($start_hrtime+01);?>
						<?php if($start_hrtime==23) { 
								echo "Zzz...";
							} else if($start_hrtime>=12) {
								echo "PM";
							} else {
								echo "AM";
							} ?></span></td>
				<?php }
				} ?>
				
			</tr>
			
		</table>
		<div class="clear"></div>
		<table class="booking_button">
			<tr>
				<td class="available"><a href="#">Available</a></td>
				<td class="unavailable"><a href="#">Unavailable</a></td>
				<td class="select"><a href="#">Selected</a></td>
			</tr>
		</table>
	</div>			
	<div class="charges">
		<ul>
			<li><span># of Slots: </span><strong id='selected_slots'></strong></li>
			<li><span>Base Fare: </span><strong id='base_price'></strong></li>
			<li><span>Service Charges: </span><strong id='service_charge'></strong></li>
			<div class="border"></div>
			<div class="clear"></div>
			<li><span><strong>Total Fare: </strong></span><strong id='total_price'></strong></li>
		</ul>
	</div>
	<div class="clear"></div>
</div>


					