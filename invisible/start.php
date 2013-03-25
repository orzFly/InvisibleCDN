<?php
if (!defined('BASE_PATH')) exit('No direct script access allowed');
require_once BASE_PATH . 'invisible/storage_' . $invisibleCfg['storage'] . '.php';

function invisible_start()
{
	global $invisibleCfg;
	
	$info = explode('/', $_SERVER['PATH_INFO']);
	if (count($info) > 0) array_shift($info);
	
	if (count($info) > 0 && isset($info[0]))
	{
		$module = array_shift($info);
		if (strlen($module) == 0) {
			require BASE_PATH . 'invisible/tpl_intro.php';
			die();
		} elseif (isset($invisibleCfg['routes'][$module])) {
			$fname = implode('/', $info);
			if ($fname)
			{
				$remote = rtrim($invisibleCfg['routes'][$module], '/') . '/' . $fname;
				if (storage_exist($module, $fname))
				{
					$content = storage_get($module, $fname);
				}
				else
				{
					$content = @file_get_contents($remote);
					storage_put($module, $fname, $content);
				}
				if ($content)
					invisible_render($fname, $content);
			}
		} elseif ($module == '.purge')
		{
			$module = implode('/', $info);
			if (isset($invisibleCfg['routes'][$module]))
			{
				storage_purge($module);
				echo 'ok';
				die();
			}
		}
	}
	
	invisible_error();
}

function invisible_render($fname, $content)
{
	$type = 'application/x-force-download';
	$temp = array();
	if(preg_match('/\.(jpg|jpeg|png|gif|css|js|htm|html|txt)$/i', $fname, $temp)===1){
		switch(strtolower($temp[1])){
			case 'jpg':{$type="image/jpeg";}break;
			case 'jpeg':{$type="image/jpeg";}break;
			case 'gif':{$type="image/gif";}break;
			case 'png':{$type="image/png";}break;
			case 'htm':{$type="text/html";}break;
			case 'html':{$type="text/html";}break;
			case 'css':{$type="text/css";}break;
			case 'js':{$type="text/javascript";}break;
			case 'txt':{$type="text/plain";}break;
		}
	}
	header('Content-type: ' . $type);
	echo $content;
	die();
}

function invisible_error()
{
	header("HTTP/1.0 404 Not Found");
	header("Status: 404 Not Found");

	require BASE_PATH . 'invisible/tpl_error.php';
	die();
}