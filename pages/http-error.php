<?php
	if(!defined('INCLUDE')) {
		header('location: /');
		exit;
	}
	if(!defined('HTTP_ERROR')) define('HTTP_ERROR',404);
	echo 'Erreur '.HTTP_ERROR;
?>
