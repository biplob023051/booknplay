<div class="span12 users view">
	<div class="row-fluid">
		<div class="block">
			<p class="block-heading"><?php echo __('User'); ?></p>
			<div id="chart-container" class="block-body collapse in">
				<div class="row-fluid rowdata">
	<?php echo $this->EBHtml->link(__('List'.'User'), array('action' => 'index'),array('class'=>'btn eb-icon-list') ); ?>				<div class="users view">
					<div class="row-fluid">
						<div class="well">
							<table class="table table-hover">
								<tr>		<td><?php echo __('Id'); ?></td>
		<td>
			<?php echo h($user['User']['id']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Display Name'); ?></td>
		<td>
			<?php echo h($user['User']['display_name']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Username'); ?></td>
		<td>
			<?php echo h($user['User']['username']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Password'); ?></td>
		<td>
			<?php echo h($user['User']['password']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Email'); ?></td>
		<td>
			<?php echo h($user['User']['email']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Phone'); ?></td>
		<td>
			<?php echo h($user['User']['phone']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Active'); ?></td>
		<td>
			<?php echo h($user['User']['active']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Created Date'); ?></td>
		<td>
			<?php echo h($user['User']['created_date']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Modified Date'); ?></td>
		<td>
			<?php echo h($user['User']['modified_date']); ?>
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