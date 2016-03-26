<div class="span12 users index">
	<div class="row-fluid">
		<div class="block">
<p class="block-heading"><?php echo __('Users'); ?></p>
<div id="chart-container" class="block-body collapse in">
<div class="row-fluid rowdata">
	<?php echo $this->EBHtml->link(__('New '.'Users'), array('action' => 'add',),array('class'=>'btn btn-primary eb-icon-add') ); ?>
	<?php echo $this->EBHtml->link(__('Ground Owner'), array('action' => 'index','gowner'),array('class'=>'btn btn-primary eb-icon-add') ); ?>
	<?php echo $this->EBHtml->link(__('Users'), array('action' => 'index','user'),array('class'=>'btn btn-primary eb-icon-add') ); ?>
	<?php echo $this->EBHtml->link(__('Guest'), array('action' => 'index','guest'),array('class'=>'btn btn-primary eb-icon-add') ); ?>
</div>
<div class="row-fluid tbl-space">
	<div class="well">
<div class="users index">
	<table class="table sorted_table table-hover">
	<tr>
							<th><?php echo $this->Paginator->sort('id'); ?></th>
							<th><?php echo $this->Paginator->sort('display_name'); ?></th>
							<th><?php echo $this->Paginator->sort('username'); ?></th>
							<th><?php echo $this->Paginator->sort('role'); ?></th>
							<th><?php echo $this->Paginator->sort('email'); ?></th>
							<th><?php echo $this->Paginator->sort('phone'); ?></th>
							<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($users as $user): ?>
	<tr>
		<td><?php echo h($user['User']['id']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['display_name']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['username']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['role']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['email']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['phone']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $user['User']['id']), null, __('Are you sure you want to delete # %s?', $user['User']['id'])); ?>
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