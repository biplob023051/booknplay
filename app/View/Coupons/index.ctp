<div class="span12 coupons index">
	<div class="row-fluid">
		<div class="block">
<p class="block-heading"><?php echo __('Coupons'); ?></p>
<div id="chart-container" class="block-body collapse in">
<div class="row-fluid rowdata">
	<?php echo $this->EBHtml->link(__('New '.'Coupons'), array('action' => 'add',),array('class'=>'btn btn-primary eb-icon-add') ); ?></div>
<div class="row-fluid tbl-space">
	<div class="well">
<?php if(!empty($coupons)){?>
<div class="coupons index">
	<table class="table sorted_table table-hover">
	<tr>
		<th><?php echo $this->Paginator->sort('id'); ?></th>
		<th><?php echo $this->Paginator->sort('code'); ?></th>
		<th><?php echo $this->Paginator->sort('amount'); ?></th>
		<th><?php echo $this->Paginator->sort('applicable_for'); ?></th>
		<th><?php echo $this->Paginator->sort('isactive'); ?></th>
		<th><?php echo __('Not Applicable Grounds'); ?></th>
		<?php if($role == 'admin'){?>
			<th class="actions"><?php echo __('Actions'); ?></th>
		<?php }	?>
	</tr>
	<?php foreach ($coupons as $coupon): ?>
	<tr>
		<td><?php echo h($coupon['Coupon']['id']); ?>&nbsp;</td>
		<td><?php echo h($coupon['Coupon']['code']); ?>&nbsp;</td>
		<td><?php echo h($coupon['Coupon']['amount']); ?>&nbsp;</td>
		<td><?php echo h($userOptions[$coupon['Coupon']['applicable_for']]); ?>&nbsp;</td>
		<td><?php echo h($statusOptions[$coupon['Coupon']['isactive']]); ?>&nbsp;</td>
		<td><?php echo h($coupon['Coupon']['not_applicable_grounds']); ?>&nbsp;</td>
		<?php if($role == 'admin'){?>
		<td class="actions">
			<?php //echo $this->Html->link(__('View'), array('action' => 'view', $coupon['Coupon']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $coupon['Coupon']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $coupon['Coupon']['id']), null, __('Are you sure you want to delete # %s?', $coupon['Coupon']['id'])); ?>
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