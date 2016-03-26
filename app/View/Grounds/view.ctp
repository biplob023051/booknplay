<div class="span12 grounds view">
	<div class="row-fluid">
		<div class="block">
			<p class="block-heading"><?php echo __('Ground'); ?></p>
			<div id="chart-container" class="block-body collapse in">
				<div class="row-fluid rowdata">
	<?php echo $this->EBHtml->link(__('List'.'Ground'), array('action' => 'index'),array('class'=>'btn eb-icon-list') ); ?>				<div class="grounds view">
					<div class="row-fluid">
						<div class="well">
							<table class="table table-hover">
								<tr>		<td><?php echo __('Id'); ?></td>
		<td>
			<?php echo h($ground['Ground']['id']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Name'); ?></td>
		<td>
			<?php echo h($ground['Ground']['name']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Address Line 1'); ?></td>
		<td>
			<?php echo h($ground['Ground']['address_line_1']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Address Line 2'); ?></td>
		<td>
			<?php echo h($ground['Ground']['address_line_2']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Count'); ?></td>
		<td>
			<?php echo h($ground['Ground']['count']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Locality'); ?></td>
		<td>
			<?php echo h($ground['Ground']['locality']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('City'); ?></td>
		<td>
			<?php echo h($ground['Ground']['city']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('State'); ?></td>
		<td>
			<?php echo h($ground['Ground']['state']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Pin'); ?></td>
		<td>
			<?php echo h($ground['Ground']['pin']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Phone'); ?></td>
		<td>
			<?php echo h($ground['Ground']['phone']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Created Date'); ?></td>
		<td>
			<?php echo h($ground['Ground']['created']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Modified Date'); ?></td>
		<td>
			<?php echo h($ground['Ground']['modified']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Active'); ?></td>
		<td>
			<?php echo h($ground['Ground']['active']); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('User'); ?></td>
		<td>
			<?php echo $this->EBHtml->link($ground['User']['id'], array('controller' => 'users', 'action' => 'view', $ground['User']['id'])); ?>
			&nbsp;
		</td>
</tr><tr>		<td><?php echo __('Rating'); ?></td>
		<td>
			<?php echo h($ground['Ground']['rating']); ?>
			&nbsp;
		</td>
		<td>
			<?php echo h($ground['Ground']['offer']); ?>
			&nbsp;
		</td>
		<td>
			<?php echo h($ground['Ground']['google_maps']); ?>
			&nbsp;
		</td>
</tr>							</table>

<h3>Available Slots</h3>
<table class="table table-hover">
<?php 
$count = Configure::read('display_days');
for($i=0;$i<$count;$i++){
?>
	<tr>		
		<td><?php echo date('d-m-Y',strtotime('+'.$i.' days')); ?></td>
		<td>
			<?php echo h($ground['Ground']['id']); ?>
			&nbsp;
		</td>
	</tr>
<?php } ?>
</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>