<div class="span12 bookedSlots view">
	<div class="row-fluid">
		<div class="block">
			<p class="block-heading"><?php echo __('Booked Slot'); ?></p>
			<div id="chart-container" class="block-body collapse in">
				<div class="row-fluid rowdata">
	<?php echo $this->EBHtml->link(__('List'.'Booked Slot'), array('action' => 'index'),array('class'=>'btn eb-icon-list') ); ?>				<div class="bookedSlots view">
					<div class="row-fluid">
						<div class="well">
							<table class="table table-hover">
								<tr>		<td><?php echo __('Id'); ?></td>
		<td>
			<?php echo h($bookedSlot['BookedSlot']['id']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Datetime'); ?></td>
		<td>
			<?php echo h($bookedSlot['BookedSlot']['datetime']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Locked'); ?></td>
		<td>
			<?php echo h($bookedSlot['BookedSlot']['locked']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Created Date'); ?></td>
		<td>
			<?php echo h($bookedSlot['BookedSlot']['created']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Modified Date'); ?></td>
		<td>
			<?php echo h($bookedSlot['BookedSlot']['modified']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Booking'); ?></td>
		<td>
			<?php echo $this->EBHtml->link($bookedSlot['Booking']['id'], array('controller' => 'bookings', 'action' => 'view', $bookedSlot['Booking']['id'])); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Ground'); ?></td>
		<td>
			<?php echo $this->EBHtml->link($bookedSlot['Ground']['name'], array('controller' => 'grounds', 'action' => 'view', $bookedSlot['Ground']['id'])); ?>
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