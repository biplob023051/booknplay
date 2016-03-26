<style>
#flashMessage{
	display:none;
}
</style>
<div style="min-height:510px;">
	<div class="h-wrapper">
		<div class="clear space100"></div>
		<div class="clear space100"></div>
		<div class="message-box no-icon red">
		 Payment Failed ! Sorry try again later!  
		</div>
	</div>
</div>
<script>
window.setTimeout(function() {
location.href = '<?php echo $this->webroot;?>';
}, 30000);
</script>