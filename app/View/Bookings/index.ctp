<div class="span12 bookings index">
	<div class="row-fluid">
		<div class="block">
<p class="block-heading"><?php echo __('Bookings').' for Ground '.$ground['Ground']['name'].'(#'.$ground['Ground']['id'].')'; ?></p>
<div id="chart-container" class="block-body collapse in">
<div class="row-fluid rowdata">
	<?php echo $this->EBHtml->link(__('New '.'Bookings'), array('controller'=>'booked_slots','action' => 'add_slots',$ground['Ground']['id']),array('class'=>'btn btn-primary eb-icon-add') ); ?></div>
<div class="row-fluid tbl-space">
	<div class="well">
<div class="bookings index">
    <table class="table admin_list_tbl">
        <?php echo $this->EBForm->create('Search',array('url'=>array('controller'=>'bookings','action'=>'index', $ground['Ground']['id']))); ?>
        <tr>
            <td>
                <?php echo $this->EBForm->input('created',array('label'=>'Booked Date', 'class'=>'datepicker')); ?>
            </td>
            <td>
            	<?php echo $this->EBForm->input('id',array('label'=>'Booking Id')); ?>
            </td>
            <td>
                <?php echo $this->EBForm->input('from_date',array('label'=>'From Slot Date', 'class'=>'datepicker')); ?>
            </td>
            <td>
                <?php echo $this->EBForm->input('to_date',array('label'=>'To Slot Date', 'class'=>'datepicker')); ?>
            </td>
        </tr>
        <tr>
            <td style='border-top:none;'>
                <?php echo $this->EBForm->hidden('proxy', array('value'=>$ground['Ground']['id'])); ?>                
                <?php echo $this->EBForm->end(array('label'=>__('Search'), 'class'=>'btn btn-primary','div'=>false)); ?>
                <?php echo $this->Html->link(__('Reset Search'), array('action' => 'index', $ground['Ground']['id']), array('class' => 'btn btn-danger')); ?>
            </td>
        </tr>
    </table>
	<table class="table sorted_table table-hover">
	<tr>
							<th><?php echo $this->Paginator->sort('User.email','Email'); ?></th>
							<th><?php echo $this->Paginator->sort('User.phone','Phone'); ?></th>
							<th><?php echo $this->Paginator->sort('id','Booking Id'); ?></th>
							<th><?php echo $this->Paginator->sort('status'); ?></th>
							<th><?php echo $this->Paginator->sort('payment_method'); ?></th>
							<th><?php echo $this->Paginator->sort('initiator'); ?></th>
							<th><?php echo $this->Paginator->sort('created','Booked Date'); ?></th>
							<th><?php echo $this->Paginator->sort('amount'); ?></th>
							<th><?php echo $this->Paginator->sort('Min_datetime', __('Slots')); ?></th>
							<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($bookings as $booking): ?>
		<tr>
			<td><?php echo h($booking['User']['email']); ?>&nbsp;</td>
			<td><?php echo h($booking['User']['phone']); ?>&nbsp;</td>
			<td><?php echo h($booking['Booking']['id']); ?>&nbsp;</td>
			<td><?php echo h($booking['Booking']['status']); ?>&nbsp;</td>
			<td><?php echo h($booking['Booking']['payment_method']); ?>&nbsp;</td>
			<td><?php echo h($booking['Booking']['initiator']); ?>&nbsp;</td>
			<td><?php echo h($booking['Booking']['created']); ?>&nbsp;</td>
			<td><?php echo h($booking['Booking']['amount']); ?>&nbsp;</td>
			<td><?php 
			if(!empty($booking['BookedSlot'])){
				foreach($booking['BookedSlot'] as $k=>$datum){
				if(count($booking['BookedSlot']) > ($k+1))
					echo date('F j, g:i a',strtotime($datum['datetime'])).',';
				else
					echo date('F j, g:i a',strtotime($datum['datetime']));
				}
			} 
			?>&nbsp;</td>
			<td class="actions">
				<?php echo ($booking['Booking']['status'] != 'CANCELLED')?$this->Form->postLink(__('Cancel'), array('action' => 'cancel', $booking['Booking']['id']), null, __('Are you sure you want to cancel this booking ?')):""; ?><br>
				<?php echo ($booking['Booking']['status'] != 'SUCCESS' && $booking['Booking']['status'] != 'CANCELLED')?$this->Form->postLink(__('Mark as paid'), array('action' => 'mark_paid', $booking['Booking']['id']), null, __('Are you sure you want to mark this booking as paid?')):""; ?>
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