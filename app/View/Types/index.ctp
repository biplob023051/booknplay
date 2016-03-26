<div class="span12 types index">
	<div class="row-fluid">
		<div class="block">
<p class="block-heading"><?php echo __('Types'); ?></p>
<div id="chart-container" class="block-body collapse in">
<div class="row-fluid rowdata">
	<?php echo $this->EBHtml->link(__('New '.'Types'), array('action' => 'add',),array('class'=>'btn btn-primary eb-icon-add') ); ?></div>
<div class="row-fluid tbl-space">
	<div class="well">
<div class="types index">
	<table class="table sorted_table table-hover">
	<tr>
							<th><?php echo $this->Paginator->sort('id'); ?></th>
							<th><?php echo $this->Paginator->sort('type'); ?></th>
							<th><?php echo $this->Paginator->sort('group_id'); ?></th>
							<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($types as $type): ?>
	<tr>
		<td><?php echo h($type['Type']['id']); ?>&nbsp;</td>
		<td><?php echo h($type['Type']['type']); ?>&nbsp;</td>
		<td>
			<?php echo $this->EBHtml->link($type['Group']['id'], array('controller' => 'groups', 'action' => 'view', $type['Group']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $type['Type']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $type['Type']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $type['Type']['id']), null, __('Are you sure you want to delete # %s?', $type['Type']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
</div>
</div>
</div>
</div></div></div></div>