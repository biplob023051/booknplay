<style>
#flashMessage{
	display:none;
}
</style>
<div style="min-height:510px;">
	<div class="h-wrapper">
		<div class="clear space100"></div>
		<div class="clear space100"></div>
		<div class="message-box no-icon green">
			We have requested payment from the customer via email and mobile number. You will receive a SMS notification from the customer once the payment is done.
		</div>
	</div>
</div>
<script>
window.setTimeout(function() {
location.href = '<?php echo $this->webroot;?>';
}, 30000);
</script>