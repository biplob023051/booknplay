<div class="span12 settings view">
	<div class="row-fluid">
		<div class="block">
			<p class="block-heading"><?php echo __('Setting'); ?></p>
			<div id="chart-container" class="block-body collapse in">
				<div class="row-fluid rowdata">
	<?php echo $this->EBHtml->link(__('List'.'Setting'), array('action' => 'index'),array('class'=>'btn eb-icon-list') ); ?>				<div class="settings view">
					<div class="row-fluid">
						<div class="well">
							<table class="table table-hover">
								<tr>		<td><?php echo __('Id'); ?></td>
		<td>
			<?php echo h($setting['Setting']['id']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Name'); ?></td>
		<td>
			<?php echo h($setting['Setting']['name']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Value'); ?></td>
		<td>
			<?php echo h($setting['Setting']['value']); ?>
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