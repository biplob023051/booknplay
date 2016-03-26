<?php

Configure::write("PaymentGateway.invoiceModel", "Booking");
Configure::write("PaymentGateway.userModel", "User");

Configure::write("PaymentGateway.defaultCurrency", "INR");

Configure::write("PaymentGateway.defaultQuantity", 1);

Configure::write("PaymentGateway.gateways", array(
"offline"=>"Offline",
"payu"=>"Payu"
		));

Configure::write("PayU.config", array(
   "merchant_key"=>"rJgshm",
   "salt"=>"I5O08O1X",
   "hash_method"=>"sha512",
   "test_mode"=>false,
   "endpoint"=>"https://secure.payu.in/_payment",
   "test_endpoint"=>"https://test.payu.in/_payment"
));
?>
