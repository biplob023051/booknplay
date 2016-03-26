<div class="span12 grounds index">
	<div class="row-fluid">
		<div class="block">
<p class="block-heading"><?php echo __('Grounds'); ?></p>
<div id="chart-container" class="block-body collapse in">
<div class="row-fluid rowdata">
	<?php echo $this->EBHtml->link(__('New '.'Grounds'), array('action' => 'add',),array('class'=>'btn btn-primary eb-icon-add') ); ?></div>
<div class="row-fluid tbl-space">
	<div class="well">
<?php if(!empty($grounds)){?>
<div class="grounds index">
	<table class="table sorted_table table-hover">
	<tr>
							<th><?php echo $this->Paginator->sort('name'); ?></th>
							<th><?php echo $this->Paginator->sort('id'); ?></th>
							<th><?php echo $this->Paginator->sort('address_line_1'); ?></th>
							<th><?php echo $this->Paginator->sort('address_line_2'); ?></th>
							<th><?php echo $this->Paginator->sort('count'); ?></th>
							<th><?php echo $this->Paginator->sort('locality'); ?></th>
							<th><?php echo $this->Paginator->sort('city'); ?></th>
							<th><?php echo $this->Paginator->sort('state'); ?></th>
							<th><?php echo $this->Paginator->sort('pin'); ?></th>
							<th><?php echo $this->Paginator->sort('phone'); ?></th>
							<th><?php echo $this->Paginator->sort('active'); ?></th>
							<th><?php echo $this->Paginator->sort('user_id'); ?></th>
							<th><?php echo $this->Paginator->sort('rating'); ?></th>
							<?php if($role == 'admin'){?>
								<th class="actions"><?php echo __('Actions'); ?></th>
							<?php }	?>
	</tr>
	<?php foreach ($grounds as $ground): ?>
	<tr>
		<td><?php echo h($ground['Ground']['name']); ?>&nbsp;</td>
		<td><?php echo h($ground['Ground']['id']); ?>&nbsp;</td>
		<td><?php echo h($ground['Ground']['address_line_1']); ?>&nbsp;</td>
		<td><?php echo h($ground['Ground']['address_line_2']); ?>&nbsp;</td>
		<td><?php echo h($ground['Ground']['count']); ?>&nbsp;</td>
		<td><?php echo h($ground['Ground']['locality']); ?>&nbsp;</td>
		<td><?php echo h($ground['Ground']['city']); ?>&nbsp;</td>
		<td><?php echo h($ground['Ground']['state']); ?>&nbsp;</td>
		<td><?php echo h($ground['Ground']['pin']); ?>&nbsp;</td>
		<td><?php echo h($ground['Ground']['phone']); ?>&nbsp;</td>
		<td><?php echo h($ground['Ground']['active']); ?>&nbsp;</td>
		<td>
			<?php echo h($ground['User']['display_name']); ?>
		</td>
		<td><?php echo h($ground['Ground']['rating']); ?>&nbsp;</td>
		<?php if($role == 'admin'){?>
		<td class="actions">
			<?php //echo $this->Html->link(__('View'), array('action' => 'view', $ground['Ground']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $ground['Ground']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $ground['Ground']['id']), null, __('Are you sure you want to delete # %s?', $ground['Ground']['id'])); ?>
		</td>
		<?php }?>
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
<?php }?>
</div>
</div>
</div></div></div></div>