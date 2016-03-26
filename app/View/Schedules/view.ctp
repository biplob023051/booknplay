<div class="span12 schedules view">
	<div class="row-fluid">
		<div class="block">
			<p class="block-heading"><?php echo __('Schedule'); ?></p>
			<div id="chart-container" class="block-body collapse in">
				<div class="row-fluid rowdata">
	<?php echo $this->EBHtml->link(__('List'.'Schedule'), array('action' => 'index'),array('class'=>'btn eb-icon-list') ); ?>				<div class="schedules view">
					<div class="row-fluid">
						<div class="well">
							<table class="table table-hover">
								<tr>		<td><?php echo __('Id'); ?></td>
		<td>
			<?php echo h($schedule['Schedule']['id']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Date'); ?></td>
		<td>
			<?php echo h($schedule['Schedule']['date']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Slots'); ?></td>
		<td>
			<?php echo h($schedule['Schedule']['slots']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Created Date'); ?></td>
		<td>
			<?php echo h($schedule['Schedule']['created']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Modified Date'); ?></td>
		<td>
			<?php echo h($schedule['Schedule']['modified']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Ground'); ?></td>
		<td>
			<?php echo $this->EBHtml->link($schedule['Ground']['name'], array('controller' => 'grounds', 'action' => 'view', $schedule['Ground']['id'])); ?>
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