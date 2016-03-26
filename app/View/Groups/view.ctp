<div class="span12 groups view">
	<div class="row-fluid">
		<div class="block">
			<p class="block-heading"><?php echo __('Group'); ?></p>
			<div id="chart-container" class="block-body collapse in">
				<div class="row-fluid rowdata">
	<?php echo $this->EBHtml->link(__('List'.'Group'), array('action' => 'index'),array('class'=>'btn eb-icon-list') ); ?>				<div class="groups view">
					<div class="row-fluid">
						<div class="well">
							<table class="table table-hover">
								<tr>		<td><?php echo __('Id'); ?></td>
		<td>
			<?php echo h($group['Group']['id']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Type Group'); ?></td>
		<td>
			<?php echo h($group['Group']['type_group']); ?>
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