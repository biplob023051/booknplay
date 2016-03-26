<?php

Router::connect("/payment_gateway/pg/:action/*", array("controller"=>"payment_gateway", "plugin"=>"payment_gateway"));