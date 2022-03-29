<?php
header('Access-Control-Allow-Origin: *');
session_start();
unset($_SESSION["s_usuario"]);
unset($_SESSION["s_id]);
session_destroy();
unset($_COOKIE['PHPSESSID']);
$data=[];
print json_encode($data);
header("Location:../index.php");
?>