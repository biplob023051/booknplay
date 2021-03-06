<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.Console.Templates.default.views
 * @since         CakePHP(tm) v 1.2.0.5234
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<div class="span12 <?php echo $pluralVar; ?> view">
	<div class="row-fluid">
		<div class="block">
			<p class="block-heading"><?php echo "<?php echo __('{$singularHumanName}'); ?>";?></p>
			<div id="chart-container" class="block-body collapse in">
				<div class="row-fluid rowdata">
	<?php echo "<?php echo \$this->EBHtml->link(__('List'.'{$singularHumanName}'), array('action' => 'index'),array('class'=>'btn eb-icon-list') ); ?>";?>
				<div class="<?php echo $pluralVar; ?> view">
					<div class="row-fluid">
						<div class="well">
							<table class="table table-hover">
								<?php
								foreach ($fields as $field) {
									$isKey = false;
									if (!empty($associations['belongsTo'])) {
										foreach ($associations['belongsTo'] as $alias => $details) {
											if ($field === $details['foreignKey']) {
												$isKey = true;
												echo "<tr>";
												echo "\t\t<td><?php echo __('" . Inflector::humanize(Inflector::underscore($alias)) . "'); ?></td>\n";
												echo "\t\t<td>\n\t\t\t<?php echo \$this->EBHtml->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n\t\t\t&nbsp;\n\t\t</td>\n";
												echo "</tr>";
												break;
											}
										}
									}
									if ($isKey !== true) {
										echo "<tr>";
										echo "\t\t<td><?php echo __('" . Inflector::humanize($field) . "'); ?></td>\n";
										echo "\t\t<td>\n\t\t\t<?php echo h(\${$singularVar}['{$modelClass}']['{$field}']); ?>\n\t\t\t&nbsp;\n\t\t</td>\n";
										echo "</tr>";
									}
								}
								?>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>