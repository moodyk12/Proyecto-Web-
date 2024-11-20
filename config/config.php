<?php
define ("CLIENT_ID", "AQfjGFfug0m7O2-mI67H8ldtgQpnasw3q0CJOwi0dAt3a0YvsLzIcreKqJhPYGLl6b347SJZ8nZwewqp");
define ("CURRENCY", "USD&locale=es_NI");
define ("KEY_TOKEN", "KPLM,1065M*A");
define ("MONEDA", "$");
session_start(); /*nuevo*/

$num_cart = 0;
if(isset($_SESSION['cesta']['productos'])){
    $num_cart = count($_SESSION['cesta']['productos']);

}
