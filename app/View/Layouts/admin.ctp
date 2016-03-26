<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php echo $this->fetch('title');?></title>
<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
    <script type="text/javascript">
        var BASE_URL = "<?php echo $this->webroot; ?>";
        var base_price = "<?php echo Configure::read('advance_rate'); ?>";
        var service_charge = "<?php echo Configure::read('service_charge'); ?>";
    </script>
	<!-- Css -->
	<?php echo $this->EBHtml->css('/adminpanel/stylesheets/bootstrap-datepicker.min.css');?>
    <?php echo $this->EBHtml->css('/adminpanel/stylesheets/bootstrap.css');?>
    <?php echo $this->EBHtml->css('/adminpanel/stylesheets/jquery-ui.css');?>
    <?php echo $this->EBHtml->css('/adminpanel/stylesheets/validationEngine.jquery.css');?>
    <?php echo $this->EBHtml->css('/adminpanel/lib/font-awesome/css/font-awesome.css');?>
    <?php echo $this->EBHtml->css('/adminpanel/stylesheets/chosen.css');?>
    <?php echo $this->EBHtml->css('/adminpanel/stylesheets/bootstrap-editable.css');?>
    <?php echo $this->EBHtml->css('/adminpanel/stylesheets/tipsy.css');?>
    <?php echo $this->EBHtml->css('/adminpanel/stylesheets/jquery.fancybox.css?v=2.1.5');?>
    <?php echo $this->EBHtml->css('/adminpanel/stylesheets/jquery.fancybox-buttons.css?v=1.0.5');?>
    <?php echo $this->EBHtml->css('/adminpanel/stylesheets/jquery.fancybox-thumbs.css?v=1.0.7');?>
    <?php echo $this->EBHtml->css('/adminpanel/stylesheets/jquery.mCustomScrollbar.css');?>
    <?php echo $this->EBHtml->css('/adminpanel/stylesheets/jquery.dataTables.css');?>
    <?php echo $this->EBHtml->css('/adminpanel/stylesheets/theme.css');?>
    <?php echo $this->EBHtml->css('/adminpanel/stylesheets/bootstrap-datetimepicker.min.css');?>
    <?php echo $this->EBHtml->css('/css/booking_layout.css');?>
    <?php echo $this->EBHtml->css('/css/bl_bootstrap.css');?>
    <!--  Javascript -->
    <?php //echo $this->EBHtml->script('/adminpanel/lib/jquery-1.8.1.min.js');?>
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
  	<?php echo $this->EBHtml->script('/adminpanel/javascripts/jquery-ui.min.js');?>
  	<script src="http://code.jquery.com/jquery-migrate-1.0.0.js"></script>
  	<?php echo $this->EBHtml->script('/adminpanel/javascripts/jquery.validationEngine.js');?>
  	<?php echo $this->EBHtml->script('/adminpanel/javascripts/lang/jquery.validationEngine-en.js');?>
  	<?php echo $this->EBHtml->script('/adminpanel/javascripts/bootstrap.js');?>
  	<?php echo $this->EBHtml->script('/adminpanel/javascripts/bootstrap-editable.js');?>
  	<?php echo $this->EBHtml->script('/adminpanel/javascripts/chosen.jquery.js');?>
  	<?php echo $this->EBHtml->script('/adminpanel/javascripts/ckeditor/ckeditor.js');?>
  	<?php echo $this->EBHtml->script('/adminpanel/javascripts/tinymce/tinymce.min.js');?>
  	<?php echo $this->EBHtml->script('/adminpanel/javascripts/jquery.tipsy.js');?>
    <?php echo $this->EBHtml->script('/adminpanel/javascripts/jquery.formatDateTime.min.js');?>
  	<?php echo $this->EBHtml->script('/adminpanel/javascripts/chosen.jquery.js');?>
  	<?php echo $this->EBHtml->script('/adminpanel/javascripts/chosen.ajaxaddition.jquery.js');?>
  	<?php echo $this->EBHtml->script('/adminpanel/javascripts/jquery.fancybox.pack.js?v=2.1.5');?>
  	<?php //echo $this->EBHtml->script('/adminpanel/javascripts/jquery.fancybox-buttons.js?v=1.0.5');?>
  	<?php //echo $this->EBHtml->script('/adminpanel/javascripts/jquery.fancybox-media.js?v=1.0.6');?>
  	<?php //echo $this->EBHtml->script('/adminpanel/javascripts/jquery.fancybox-thumbs.js?v=1.0.7');?>
  	<?php echo $this->EBHtml->script('/adminpanel/javascripts/jquery.mCustomScrollbar.min.js');?>
  	<?php echo $this->EBHtml->script('/adminpanel/javascripts/jquery.dataTables.js')?>
  	<?php echo $this->EBHtml->script('/adminpanel/javascripts/app.js');?>
  	<?php echo $this->EBHtml->script('/js/admin_bl.js');?>
    <?php echo $this->EBHtml->script('/adminpanel/javascripts/bootstrap-datetimepicker.min.js');?>
  	<?php echo $this->fetch("css"); ?>
  	<?php echo $this->fetch("script"); ?>
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<!--[if lt IE 7 ]> <body class="ie ie6"> <![endif]-->
<!--[if IE 7 ]> <body class="ie ie7"> <![endif]-->
<!--[if IE 8 ]> <body class="ie ie8"> <![endif]-->
<!--[if IE 9 ]> <body class="ie ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<body>
	<!--<![endif]-->
	<?php echo $this->element('adminpanel_header');?>
	<div class="container-fluid">
		<?php echo $this->element('adminpanel_menu');?>
		<div class="row-fluid">
			<?php echo $this->EBSession->flash();?>
		</div>
		<div class="row-fluid">
			<!-- Include sidemenu here if need -->
			<!-- All Page content goes here  -->
			<?php echo $this->fetch('content');?>
		</div>
		<hr>
		<p class="admin_footer">
			&copy; 2015 BookNPlay
		</p>
	</div>
</body>
</html>