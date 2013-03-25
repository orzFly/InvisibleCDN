<?php
$invisibleCfg = array();
$invisibleCfg['storage'] = "file";
$invisibleCfg['routes'] = array();

define('BASE_PATH', dirname(__FILE__) . '/');
require_once BASE_PATH . 'invisible.inc';
require_once BASE_PATH . 'invisible/start.php';
invisible_start();