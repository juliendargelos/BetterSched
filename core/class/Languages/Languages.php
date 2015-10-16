<?php
	// Langues
	class Languages {
		private $fr=[
			'monday'=>'Lundi',
			'tuesday'=>'Mardi',
			'wednesday'=>'Mercredi',
			'thursday'=>'Jeudi',
			'friday'=>'Vendredi',
			'saturday'=>'Samedi'
		];
		private $en=[
			'Lundi'=>'monday',
			'Mardi'=>'tuesday',
			'Mercredi'=>'wednesday',
			'Jeudi'=>'thursday',
			'Vendredi'=>'friday',
			'Samedi'=>'saturday'
		];
		
		public function __get($language) {
			if($language=='fr') return $this->fr;
			elseif($language=='en') return $this->en;
			else return null;
		}
	}
?>