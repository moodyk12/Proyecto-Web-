<?php

define ("KEY_TOKEN", "KPLM,1065M*A");
define ("MONEDA", "$");
session_start(); /*nuevo*/

$num_cart = 0;
if(isset($_SESSION['cesta']['productos'])){
    $num_cart = count($_SESSION['cesta']['productos']);

}
