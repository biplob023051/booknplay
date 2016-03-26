<div class="span12 bookings view">
	<div class="row-fluid">
		<div class="block">
			<p class="block-heading"><?php echo __('Booking'); ?></p>
			<div id="chart-container" class="block-body collapse in">
				<div class="row-fluid rowdata">
	<?php echo $this->EBHtml->link(__('List'.'Booking'), array('action' => 'index'),array('class'=>'btn eb-icon-list') ); ?>				<div class="bookings view">
					<div class="row-fluid">
						<div class="well">
							<table class="table table-hover">
								<tr>		<td><?php echo __('Id'); ?></td>
		<td>
			<?php echo h($booking['Booking']['id']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Status'); ?></td>
		<td>
			<?php echo h($booking['Booking']['status']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Payment Method'); ?></td>
		<td>
			<?php echo h($booking['Booking']['payment_method']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Initiator'); ?></td>
		<td>
			<?php echo h($booking['Booking']['initiator']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Amount'); ?></td>
		<td>
			<?php echo h($booking['Booking']['amount']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Created Date'); ?></td>
		<td>
			<?php echo h($booking['Booking']['created']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Modified Date'); ?></td>
		<td>
			<?php echo h($booking['Booking']['modified']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Ground'); ?></td>
		<td>
			<?php echo $this->EBHtml->link($booking['Ground']['name'], array('controller' => 'grounds', 'action' => 'view', $booking['Ground']['id'])); ?>
			&nbsp;
		</td>
</tr>							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>