
<?php
require __DIR__ . '/vendor/autoload.php' ;

use  Automattic\WooCommerce\Client;

$woocommerce = new  Client (
   'http://localhost:8080/ecommerce/' ,
   'ck_e116116b637f445f1d001e151c8df6f626897364' ,
   'cs_154cea8e9fc1568dd1b0d4fd5d86cb8ff98dae5a' ,
  [
    'versión' => 'wc/v3' ,
  ]
);
print_r($woocommerce->get('products'));