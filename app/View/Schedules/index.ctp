<div class="span12 schedules index">
	<div class="row-fluid">
		<div class="block">
<p class="block-heading"><?php echo __('Schedules').' for Ground '.$ground['Ground']['name'].'(#'.$ground['Ground']['id'].')'; ?></p>
<div id="chart-container" class="block-body collapse in">
<div class="row-fluid rowdata">
	<?php echo $this->EBHtml->link(__('New '.'Schedules'), array('action' => 'add',$ground['Ground']['id']),array('class'=>'btn btn-primary eb-icon-add') ); ?></div>
<div class="row-fluid tbl-space">
	<div class="well">
<div class="schedules index">
	<table class="table sorted_table table-hover">
	<tr>
							<th><?php echo $this->Paginator->sort('date'); ?></th>
							<th><?php echo $this->Paginator->sort('slots'); ?></th>
							<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($schedules as $schedule): ?>
	<tr>
		<td><?php echo date('D, j M y',strtotime($schedule['Schedule']['date'])); ?>&nbsp;</td>
		<td><?php echo "<div class='change_ui_slots'>".$schedule['Schedule']['slots']."</div>"; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $schedule['Schedule']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $schedule['Schedule']['id']), null, __('Are you sure you want to delete # %s?', $schedule['Schedule']['id'])); ?>
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
<script>
	var slots = <?php echo Configure::read ( 'slots_per_day' );?>;
</script>
<?php echo $this->EBHtml->css('/adminpanel/stylesheets/schedule_slot.css');?>
<?php echo $this->EBHtml->script('/adminpanel/javascripts/schedule_slot.js');?>
<style>
.single_slot{
	width:26px !important;
}
</style>