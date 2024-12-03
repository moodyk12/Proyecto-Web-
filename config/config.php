<?php
define ("CLIENT_ID", "AQfjGFfug0m7O2-mI67H8ldtgQpnasw3q0CJOwi0dAt3a0YvsLzIcreKqJhPYGLl6b347SJZ8nZwewqp");
define ("CURRENCY", "USD&locale=es_NI");
define("URL_SITE","http://localhost/Bunny%20Vibes/Proyecto-Web-");
define ("KEY_TOKEN", "KPLM,1065M*A");
define ("MONEDA", "$");
session_start(); /*nuevo*/

//LUEGO MIRO
define("MAIL_HOST","smtp.gmail.com");
define("MAIL_USER","moodykarla497@gmail.com");
define("MAIL_PASS","vvwm kudr jfjc stzl");
define("MAIL_PORT","587");

$num_cart = 0;
if(isset($_SESSION['cesta']['productos'])){
    $num_cart = count($_SESSION['cesta']['productos']);

}
