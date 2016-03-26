<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="<?php echo $this->webroot;?>source/jquery.fancybox.js?v=2.1.5"></script>
	<script src="https://d2xwmjc4uy2hr5.cloudfront.net/im-embed/im-embed.min.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot;?>source/jquery.fancybox.css?v=2.1.5" media="screen" />
	<script type="text/javascript">
		$(document).ready(function() {
			$.fancybox.open({
				href : 'https://www.instamojo.com/BOOKNPLAY/gee-vee-badminton-club/?embed=form',
				type : 'iframe',
				padding : 5,
				afterClose: function () {
					location.href = "<?php echo $this->webroot;?>tournaments";
				}
			});
		});
	</script>
	<div class="section">
		<div class="wrap">
			<div class="center">
			
	</div>
	</div>
	</div>