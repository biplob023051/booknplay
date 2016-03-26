<div class="redirectMsg">
	Redirecting to 3D secure page
</div>
<?php
	echo $this->EBForm->create('PayU',array('url'=>$requestURL[0],'id'=>'redirectForm')); 
	echo $this->EBForm->hidden('key',array('name'=>'key','value'=>$requestURL[1]['key']));
	echo $this->EBForm->hidden('txnid',array('name'=>'txnid','value'=>$requestURL[1]['txnid']));
	echo $this->EBForm->hidden('email',array('name'=>'email','value'=>$requestURL[1]['email']));
	echo $this->EBForm->hidden('amount',array('name'=>'amount','value'=>$requestURL[1]['amount']));
	echo $this->EBForm->hidden('surl',array('name'=>'surl','value'=>$requestURL[1]['surl']));
	echo $this->EBForm->hidden('furl',array('name'=>'furl','value'=>$requestURL[1]['furl']));
	echo $this->EBForm->hidden('productinfo',array('name'=>'productinfo','value'=>$requestURL[1]['productinfo']));
	echo $this->EBForm->hidden('firstname',array('name'=>'firstname','value'=>$requestURL[1]['firstname']));
	echo $this->EBForm->hidden('phone',array('name'=>'phone','value'=>$requestURL[1]['phone']));
	echo $this->EBForm->hidden('hash',array('name'=>'hash','value'=>$requestURL[1]['hash']));
	echo $this->EBForm->hidden('service_provider',array('name'=>'service_provider','value'=>$requestURL[1]['service_provider']));
?>

<style>
.redirectMsg{font-size:20px; font-weight:bold; width:260px; padding:20px 40px; background-color:#ccc; border:1px solid #ddd; margin:0px auto; margin-top:200px; color:#FFF;}
</style>

<script type="text/javascript">
	document.getElementById("redirectForm").submit();
</script>