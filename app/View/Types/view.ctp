<div class="span12 types view">
	<div class="row-fluid">
		<div class="block">
			<p class="block-heading"><?php echo __('Type'); ?></p>
			<div id="chart-container" class="block-body collapse in">
				<div class="row-fluid rowdata">
	<?php echo $this->EBHtml->link(__('List'.'Type'), array('action' => 'index'),array('class'=>'btn eb-icon-list') ); ?>				<div class="types view">
					<div class="row-fluid">
						<div class="well">
							<table class="table table-hover">
								<tr>		<td><?php echo __('Id'); ?></td>
		<td>
			<?php echo h($type['Type']['id']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Type'); ?></td>
		<td>
			<?php echo h($type['Type']['type']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Group'); ?></td>
		<td>
			<?php echo $this->EBHtml->link($type['Group']['id'], array('controller' => 'groups', 'action' => 'view', $type['Group']['id'])); ?>
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