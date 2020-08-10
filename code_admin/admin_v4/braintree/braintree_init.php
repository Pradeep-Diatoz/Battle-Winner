<?php
session_start();
require_once("lib/autoload.php");
if(file_exists(__DIR__ . "/../.env")) {
    $dotenv = new Dotenv\Dotenv(__DIR__ . "/../");
    $dotenv->load();
}

// testing
Braintree_Configuration::environment('sandbox');

//production
// Braintree_Configuration::environment('production');

Braintree_Configuration::merchantId('4dfb3bxpbkgs3xmh');
Braintree_Configuration::publicKey('36fvqxf27q2mbsvn');
Braintree_Configuration::privateKey('f5j78c656defd3e9501256aaab75a4lb');
?>