<div class="span12 bookedSlots index">
	<div class="row-fluid">
		<div class="block">
<p class="block-heading"><?php echo __('Booked Slots'); ?></p>
<div id="chart-container" class="block-body collapse in">
<div class="row-fluid rowdata">
	<?php echo $this->EBHtml->link(__('New '.'Booked Slots'), array('action' => 'add',),array('class'=>'btn btn-primary eb-icon-add') ); ?></div>
<div class="row-fluid tbl-space">
	<div class="well">
<div class="bookedSlots index">
	<table class="table sorted_table table-hover">
	<tr>
							<th><?php echo $this->Paginator->sort('id'); ?></th>
							<th><?php echo $this->Paginator->sort('datetime'); ?></th>
							<th><?php echo $this->Paginator->sort('locked'); ?></th>
							<th><?php echo $this->Paginator->sort('booking_id'); ?></th>
							<th><?php echo $this->Paginator->sort('ground_id'); ?></th>
							<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($bookedSlots as $bookedSlot): ?>
	<tr>
		<td><?php echo h($bookedSlot['BookedSlot']['id']); ?>&nbsp;</td>
		<td><?php echo h($bookedSlot['BookedSlot']['datetime']); ?>&nbsp;</td>
		<td><?php echo h($bookedSlot['BookedSlot']['locked']); ?>&nbsp;</td>
		<td>
			<?php echo $this->EBHtml->link($bookedSlot['Booking']['id'], array('controller' => 'bookings', 'action' => 'view', $bookedSlot['Booking']['id'])); ?>
		</td>
		<td>
			<?php echo $this->EBHtml->link($bookedSlot['Ground']['name'], array('controller' => 'grounds', 'action' => 'view', $bookedSlot['Ground']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $bookedSlot['BookedSlot']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $bookedSlot['BookedSlot']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $bookedSlot['BookedSlot']['id']), null, __('Are you sure you want to delete # %s?', $bookedSlot['BookedSlot']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
</div>
</div>
</div>
</div></div></div></div>