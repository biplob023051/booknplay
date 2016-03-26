<div class="container_12 booking_layout" style="display:block !important;">
		<div class="clear space10"></div>
        <p><center><span>No of courts: <select style="width:8% !important;height:36px;" data-gid="<?php echo $ground_details['Ground']['id'];?>" data-startdate="<?php echo $start_date;?>" id="no_of_court"><?php for($i=1;$i<=$ground_details['Ground']['count'];$i++){$selected = "";if($i == $selected_court){$selected='selected';}echo "<option ".$selected." value='".$i."'>".$i."</option>";}?></select></span></center></p>
        <input type="hidden" name="data[Booking][selected_court]" value="<?php echo $selected_court;?>" />
        <p class="bl_tip"><strong>Note:</strong> <?php echo $ground_details['Ground']['tips'];?></p>
        <div class="grid_8 bl_slot_selection">
            <div class="row_time noselect">
                <span class="bl_timedisplay"></span>
                <span>12 am</span>
                <span>1 am</span>
                <span>2 am</span>
                <span>3 am</span>
                <span>4 am</span>
                <span>5 am</span>
                <span>6 am</span>
                <span>7 am</span>
                <span>8 am</span>
                <span>9 am</span>
                <span>10 am</span>
                <span>11 am</span>
                <span>12 pm</span>
                <span>1 pm</span>
                <span>2 pm</span>
                <span>3 pm</span>
                <span>4 pm</span>
                <span>5 pm</span>
                <span>6 pm</span>
                <span>7 pm</span>
                <span>8 pm</span>
                <span>9 pm</span>
                <span>10 pm</span>
                <span>11 pm</span>
                <br />
            </div>
            <?php for($i=1;$i<=$count_no;$i++){ ?>
            <div class="row noselect">
                <span class="bl_timedisplay"><?php echo date('d M y',strtotime((($i+$start_date_no)-1)." days"));?>  </span>
            	<?php 
            	$slot = explode(',',$slots[date('Y-m-d',strtotime((($i+$start_date_no)-1)." days"))]);
            	for($j=1;$j<=Configure::read ( 'slots_per_day' );$j++){ 
            	
            	//Checking Availability
            	if($slot[$j-1] == 1)
            		$disabled = "";
            	else
            		$disabled = "disabled=disabled";
            		
            	//Check Validation
            	if(date('Y-m-d',strtotime((($i+$start_date_no)-1)." days")) == date('Y-m-d')){
            		if((date('H')+4) > $j){
            			$disabled = "disabled=disabled";
            		}
            	}
            	?>    
                <input type="checkbox" id="<?php echo 's'.($i+$start_date_no).sprintf ("%02u", $j);?>" <?php echo $disabled;?> name="data[slots][<?php echo 's'.($i+$start_date_no).sprintf ("%02u", $j);?>]" value="<?php echo 's'.($i+$start_date_no).sprintf ("%02u", $j);?>" class='single_slot'/><label for="<?php echo 's'.($i+$start_date_no).sprintf ("%02u", $j);?>"><span></span></label>
                <?php }?>
            <br />
            </div>
            <?php }?>

        </div>
        <div class="grid_2 bl_symbol_note">
            <div>
            <div class="clear space10"></div>
            <div class="clear space10"></div>
            <table>
            <tr><td style='width:65px;'><?php echo $ground_details['Ground']['entry1_label'];?></td><td>: Rs.<?php echo $ground_details['Ground']['entry1_value'];?></td></tr>
            <tr><td style='width:65px;'><?php echo $ground_details['Ground']['entry2_label'];?></td><td>: Rs.<?php echo $ground_details['Ground']['entry2_value'];?></td></tr>
            </table>
            </div>
            <p><span class="bl_available"></span>Available Slots</p>
            <p><span class="bl_booked"></span>Booked/Unallocated Slots</p>
            <p><span class="bl_selected"></span>Selected Slots</p>
            
            <div class="divider-wrapper ">
                <div class="divider"></div>
            </div>
            
            <p><strong>Slots : </strong><span id='selected_slots'></span></p>
            <p><strong>Base Fare : </strong>Rs.<span id='base_price'></span></p>
            <p><strong>Service Charge : </strong>Rs.<span id='service_charge'></span></p>
            <p><strong>Total Fare : </strong>Rs.<span id='total_price'></span></p>
        </div>
        <div class="clear space0"></div>
        <div class="space20"></div>
</div>
