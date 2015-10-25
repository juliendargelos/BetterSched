<?php
	// Mémoire de la requête vers le serveur
	class Handle {
		public $curl=null;
		public $cookies=null;

		public function __construct($curl,$cookies) {
			$this->curl=$curl;
			$this->cookies=$cookies;
		}
	}
?>
