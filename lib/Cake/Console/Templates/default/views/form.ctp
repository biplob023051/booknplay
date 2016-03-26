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
<div class="span12 <?php echo $pluralVar; ?> form">
	<div class="row-fluid">
		<div class="block">
			<div class="categoriesform">
				<p class="block-heading"><?php printf("<?php echo __('%s %s'); ?>", Inflector::humanize($action), $singularHumanName); ?></p>
				<div id="chart-container" class="block-body collapse in">
					<div class="row-fluid rowdata">
					<?php echo "<?php echo \$this->EBHtml->link(__('Back to list'), array('action' => 'index'),array('class'=>'btn btn-primary eb-icon-back') ); ?>";?>				</div>
					<div class="row-fluid">
					<?php echo "<?php echo \$this->EBForm->create('{$modelClass}',array('class'=>'form-horizontal','type'=>'file')); ?>";?>
					<div class="control-group">
						<?php
							echo "\t<?php\n";
							foreach ($fields as $field) {
								if (strpos($action, 'add') !== false && $field == $primaryKey) {
									continue;
								} elseif (!in_array($field, array('created', 'modified', 'updated'))) {
									echo "\t\techo \$this->EBForm->input('{$field}');\n";
								}
							}
							if (!empty($associations['hasAndBelongsToMany'])) {
								foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData) {
									echo "\t\techo \$this->EBForm->input('{$assocName}');\n";
								}
							}
							echo "\t?>\n";
						?>	
					</div>
						<div class="control-group"><?php echo "<?php echo \$this->EBForm->end(array('label'=>__('Submit'), 'class'=>'btn btn-primary')); ?>";?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>