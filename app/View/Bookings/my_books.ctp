<div class="inner-page">
		<div class="wrap">
			<div class="center">
				<div class="listing-page">
					<div class="detail-box">
						<div class="border-gray">
							<h3>My Bookings</h3>
						</div>
						<?php foreach ($bookings as $booking): ?>
						<?php 
							$slot_dates = array();
							$latest = "";
							$n_courts = 1;
							$count_dup_key = 0;
							$duplicate_arr = array();
							if(!empty($booking['BookedSlot'])){
								
								foreach($booking['BookedSlot'] as $k=>$datum){
									
									if($latest == ""){
										$latest = strtotime($datum['datetime']);
									}
									else{
										if($latest > strtotime($datum['datetime']))
											$latest = strtotime($datum['datetime']);
									}
									
									$slot_dates[$datum['datetime']] = date("g",strtotime($datum['datetime'])).' - '.date("g a",strtotime($datum['datetime'])+ 60*60);
									$duplicate_arr[] = $datum['datetime'];
								}
								$duplicate_arr_c = array_count_values($duplicate_arr);
								reset($duplicate_arr_c);
								$n_courts = current($duplicate_arr_c);
							}
						?>
						<div class="top-section border-gray">
							
							<div class="left">
								<div class="product">
								<a href="javascript:void(0)">
									<img src="<?php echo $this->webroot;?>img/product-img.jpg">
								</a>
							</div>
							<div class="product_detail">
								<strong><?php echo $booking['Ground']['name'];?></strong><br>
								<i>Wooden Court</i>
								<p><?php echo $booking['Ground']['address_line_1'];?><br>
									<?php echo $booking['Ground']['address_line_2'];?> <?php echo $booking['Ground']['locality'];?><br>
									<?php echo $booking['Ground']['phone'];?>
								</p>
							</div>
							</div>
							<div id="payment-page" class="right">
								<ul class="select-slots">
									<li>
										<span>Booking ID:</span>#<?php echo h($booking['Booking']['id']); ?>
									</li>
									<li>
										<span>Status: </span><?php echo $booking['Booking']['status']; ?>
									</li>
									<li>
										<span>Number of Courts: </span><input type="text" value="<?php echo $n_courts; ?>">
									</li>										
									<li><span>Date:</span>
									<?php foreach($slot_dates as $slot_date_key => $slot_date_info) {  
											echo date('l, d F Y', strtotime($slot_date_key));
											break;
									} ?>
									</li>
									<li>
										<span>Selected Slots:</span>
										<?php foreach($slot_dates as $slot_date_key => $slot_date_info) {  ?>
											<a class="select" href="javascript:void(0)"><?php echo $slot_date_info;?></a>
										<?php } ?>
									</li>
									<li>
										<span>Total Fare:</span> <strong>Rs.<?php echo $booking['Booking']['amount'];?></strong>
									</li>
								</ul>
							</div>
							<div class="clear"></div>
							<?php if($booking['Booking']['status'] != 'CANCELLED' && $latest > strtotime('2 days')) { ?>
								<div class="cancel-product">
									<?php echo $this->Form->postLink(__('Cancel Booking'), array('action' => 'cancel', $booking['Booking']['id']), null, __('Are you sure you want to cancel this booking ?')); ?>
								</div>
							<?php  } ?>
						</div>
						<?php endforeach; ?>
						<div class="clear"></div>
						<div class="paging ebpaging">
							<?php 
								echo $this->Paginator->prev('< ' . __('Previous'), array(), null, array('class' => 'prev disabled'));
								echo $this->Paginator->next(__('Next') . ' >', array(), null, array('class' => 'next disabled'));
							?>
						</div>
					</div>
					<!-- detail box end -->
				</div>
			</div>
		</div>
			<div class="clear"></div>
			<br>
	</div>
	
	
	


<style>
.next{
	float:right;
}
th a{
 color:white;
}
</style>