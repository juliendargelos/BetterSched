<?php
	// Fichiers de script du serveur
	class Files {
		private $signin='http://syrah.iut.u-bordeaux3.fr/gpu/sat/index.php?page_param=accueilsatellys.php&cat=0&numpage=1&niv=0&clef=/';
		private $gpu='http://syrah.iut.u-bordeaux3.fr/gpu/gpu/index.php';
		private $homesched='http://syrah.iut.u-bordeaux3.fr/gpu/gpu/index.php?page_param=accueil.php&cat=0&numpage=1&niv=1&clef=/305/';
		private $sched='http://syrah.iut.u-bordeaux3.fr/gpu/gpu/index.php?page_param=fpfilieres.php&cat=0&numpage=1&niv=2&clef=/305/306/';
		
		public function __get($file) {
			return isset($this->$file) ? $this->$file : null;
		}
	}
?>