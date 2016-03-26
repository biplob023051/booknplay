<div class="span12 settings index">
	<div class="row-fluid">
		<div class="block">
<p class="block-heading"><?php echo __('Settings'); ?></p>
<div id="chart-container" class="block-body collapse in">
<div class="row-fluid rowdata">
	<?php echo $this->EBHtml->link(__('New '.'Settings'), array('action' => 'add',),array('class'=>'btn btn-primary eb-icon-add') ); ?></div>
<div class="row-fluid tbl-space">
	<div class="well">
<div class="settings index">
	<table class="table sorted_table table-hover">
	<tr>
							<th><?php echo $this->Paginator->sort('id'); ?></th>
							<th><?php echo $this->Paginator->sort('name'); ?></th>
							<th><?php echo $this->Paginator->sort('value'); ?></th>
							<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($settings as $setting): ?>
	<tr>
		<td><?php echo h($setting['Setting']['id']); ?>&nbsp;</td>
		<td><?php echo h($setting['Setting']['name']); ?>&nbsp;</td>
		<td><?php echo h($setting['Setting']['value']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $setting['Setting']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $setting['Setting']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $setting['Setting']['id']), null, __('Are you sure you want to delete # %s?', $setting['Setting']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
</div>
</div>
</div>
</div></div></div></div>