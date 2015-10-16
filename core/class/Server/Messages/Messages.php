<?php
	// Messages d'informations du serveur
	class Messages {
		private $signin='CONNEXION ETABLIE';
		private $sched='Emploi du temps Filières';
		
		public function __get($message) {
			return isset($this->$message) ? $this->$message : null;
		}
	}
?>