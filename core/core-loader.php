<?php
	require_once(__DIR__.'/const.php');
	
	function core_loader($path) {
		if(file_exists($path.basename($path).'.php')) require_once($path.basename($path).'.php');
		$class_dir=scandir($path);
		foreach($class_dir as $class) {
			if(substr($class,0,1)!='.' && is_dir($path.$class)) core_loader($path.$class.'/');
		}
	}
	
	core_loader(__DIR__.'/class/');
?>