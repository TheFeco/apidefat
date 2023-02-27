<?php
header('Access-Control-Allow-Origin: *');
session_start();
unset($_SESSION["s_usuario"]);
unset($_SESSION["s_id"]);
session_destroy();
$data=[];
print json_encode($data);
?>