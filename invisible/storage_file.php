<?php
if (!defined('BASE_PATH')) exit('No direct script access allowed');

function storage_put($module, $fname) {
	if (!is_dir('storage')) mkdir('storage');
	if (!is_dir('storage/' . $module)) mkdir('storage/' . $module);
	file_put_contents('storage/' . $module . '/' . md5($fname), $body);
}

function storage_get($module, $fname) {
	return file_get_contents('storage/' . $module . '/' . md5($fname));
}

function storage_exist($module, $fname) {
	return is_file('storage/' . $module . '/' . md5($fname));
}

function storage_purge($module) {
	function rrmdir($dir) {
		$files = array_diff(scandir($dir), array('.','..'));
		foreach ($files as $file) {
			(is_dir("$dir/$file")) ? rrmdir("$dir/$file") : unlink("$dir/$file");
		}
		return rmdir($dir);
	}
	
	if (is_dir('storage/' . $module)) rrmdir('storage/' . $module);
}