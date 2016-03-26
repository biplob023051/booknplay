<div class="span12 grounds index">
	<div class="row-fluid">
		<div class="block">
<p class="block-heading"><?php echo __('Grounds'); ?></p>
<div id="chart-container" class="block-body collapse in">
<div class="row-fluid rowdata">
<div class="row-fluid tbl-space">
	<div class="well">
<div class="grounds index">
	<table class="table sorted_table table-hover">
	<tr>
							<th><?php echo $this->Paginator->sort('id'); ?></th>
							<th><?php echo $this->Paginator->sort('name'); ?></th>
							<th><?php echo $this->Paginator->sort('count'); ?></th>
							<th><?php echo $this->Paginator->sort('locality'); ?></th>
							<th><?php echo $this->Paginator->sort('city'); ?></th>
							<th><?php echo $this->Paginator->sort('state'); ?></th>
							<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($grounds as $ground): ?>
	<tr>
		<td><?php echo h($ground['Ground']['id']); ?>&nbsp;</td>
		<td><?php echo h($ground['Ground']['name']); ?>&nbsp;</td>
		<td><?php echo h($ground['Ground']['count']); ?>&nbsp;</td>
		<td><?php echo h($ground['Ground']['locality']); ?>&nbsp;</td>
		<td><?php echo h($ground['Ground']['city']); ?>&nbsp;</td>
		<td><?php echo h($ground['Ground']['state']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Schedule'), array('controller'=>'schedules','action' => 'index', $ground['Ground']['id']),array('class'=>'btn btn-primary')); ?>
			<?php echo $this->Html->link(__('Booking'), array('controller'=>'bookings','action' => 'index', $ground['Ground']['id']),array('class'=>'btn btn-primary')); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<div class="paging ebpaging">
	<?php
        //echo $this->Paginator->prev('<< first', array(), null, array('class' => 'prev disabled'));
        echo $this->Paginator->first('<<'. __('first'));
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
        //echo $this->Paginator->last(__('last'), array('tag' => false));
       echo $this->Paginator->last(__('last'). '>>');
	?>
	</div>
</div>
</div>
</div>
</div></div></div></div>