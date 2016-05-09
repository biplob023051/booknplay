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
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="keywords" content="Book And Play" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <meta name="google-signin-client_id" content="806270021281-0qa3los30bp12oep07sf61b7ks5qn3in.apps.googleusercontent.com"></meta>
    <?php if($this->action == 'search'){?>
    	<meta name="viewport" content="width=device-width, initial-scale=1">
    <?php }else{?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php }?>
	<?php echo $this->Html->charset(); ?>
	<script type="text/javascript">
        var BASE_URL = "<?php echo $this->webroot; ?>";
        var base_price = "<?php echo Configure::read('advance_rate'); ?>";
        var service_charge = "<?php echo Configure::read('service_charge'); ?>";
    </script>
    
<script>

  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-62012666-1', 'auto');
  ga('send', 'pageview');

</script>
	<title>
        BookNPlay
	</title>
   <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700' rel='stylesheet' type='text/css'>
   
  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
  <link rel="apple-touch-icon" href="/apple-touch-icon.png" />
  <link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png" />
  <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png" />
  <link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png" />
  <link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png" />
  <link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png" />
  <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png" />
  <link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png" />
  <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png" />
  
   <?php
		//echo $this->EBHtml->css("reset");
		//echo $this->EBHtml->css("960");
		echo $this->EBHtml->css("superfish");
		echo $this->EBHtml->css("prettyPhoto");
		echo $this->EBHtml->css("tipsy");
		echo $this->EBHtml->css("icomoon");
		echo $this->EBHtml->css("style");
		//echo $this->EBHtml->css("responsive");
		echo $this->EBHtml->css("font-awesome.min");
		echo $this->EBHtml->css("booking_layout");
		echo $this->EBHtml->css("bootstrap-datepicker.min");
		echo $this->EBHtml->css("lightbox");
		echo $this->EBHtml->script("jquery.min");
		echo $this->EBHtml->script("bootstrap-datepicker.min");
		echo $this->EBHtml->script("custom");
		echo $this->EBHtml->script("lightbox");
	?>
</head>
<body>
	<div id="body-background">
		<div id="body-wrapper">
			<?php echo $this->Element('main_header');?>
			<div id="main" class="pt0">
				
				<?php echo $this->Session->flash(); ?>
				<?php echo $this->fetch('content'); ?>						
	
			</div><!-- #main -->

			<?php echo $this->Element('main_footer');?>
			<!-- Back-to-top
			================================================== -->
			<div id="backtotop">
				<span class="icon icon-arrow-up10"></span>
			</div>


		</div><!-- .body-wrapper -->
	</div><!-- .body-background -->
<?php 
echo $this->EBHtml->script("superfish");
echo $this->EBHtml->script("supersubs");
echo $this->EBHtml->script("jquery.prettyPhoto");
echo $this->EBHtml->script("jquery.nivo.slider.pack");
echo $this->EBHtml->script("jquery.masonry.min");
echo $this->EBHtml->script("jquery.easing.1.3");
echo $this->EBHtml->script("jquery.carouFredSel-6.2.1-packed");
echo $this->EBHtml->script("jquery.parallax-1.1.3");
echo $this->EBHtml->script("jquery.html5-placeholder-shim");
echo $this->EBHtml->script("jquery.countTo");
echo $this->EBHtml->script("jquery.tipsy");
echo $this->EBHtml->script("jquery.custom");
echo $this->EBHtml->script("waypoints.min");
echo $this->EBHtml->script("bl");
?>
<script>
$('#flashMessage').addClass('yellow');
$('#flashMessage').addClass('no-icon');
$('#flashMessage').addClass('message-box');
$('#flashMessage').click(function(){
	$(this).remove();
});
</script>
<style>
#flashMessage{
	text-align:center !important;
	cursor:pointer;
}
.message-box.no-icon{
	padding:12px !important;
}
</style>
</body>
</html>