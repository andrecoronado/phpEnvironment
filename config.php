<?php

// Get the path for choose the Environment
$env=explode("/",$_SERVER["PHP_SELF"]);
$GLOBALS['environmentPage']=$env[1]; 

//include class
require_once $_SERVER['DOCUMENT_ROOT'].'php/include/class.environment.php';

//start class
$environment = new Environment($GLOBALS['environmentPage']);

//define colors for background. So, this way you are know that which Environment you are.
$GLOBALS['colorFundo']=$environment->color_fundo;
if($environment->color_menu=="default"){$GLOBALS['colorMenu']="";}
else{$GLOBALS['colorMenu']='style="background-color:'.$ambiente->color_menu.';"';}

$conection=$environment->conectDB());




