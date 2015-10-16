<?php
	// Emploi du temps
	class Sched {
		private $generic=[
			'monday'=>[],
			'tuesday'=>[],
			'wednesday'=>[],
			'thursday'=>[],
			'friday'=>[],
			'saturday'=>[]
		];
		
		public function __get($type) {
			$sched=$this->generic;
			if($type=='plain') return $sched;
			elseif($type=='hour') {
				foreach($sched as $dayname=>$day) {
					for($h=8; $h<=19; $h++) {
						$sched[$dayname][$h.'h']=[];
						$sched[$dayname][$h.'h30']=[];
					}
					$sched[$dayname]['date']=false;
				}
				return $sched;
			}
			else return null;
		}
	}
?>