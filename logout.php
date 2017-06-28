<?php
session_start();
require_once 'config.php';
$_SESSION = array();
session_destroy;
	ob_start();
    header('Location: index.php?x=logout');
    ob_end_flush();
    die();
?>