<?php
	// Tableaux HTML
	class Table {
		public $data=null;
		private $compute;
		
		public function __get($type) {
			if($this->data!=null) {
				if($type=='day') {
					$day=[];
					foreach($this->data as $dayname=>$daydata) $day[$dayname]=$this->compute->day($dayname,$daydata);
					return $day;
				}
				elseif($type=='week') return $this->compute->week($this->data);
				else return null;
			}
			else return null;
		}
		
		public function __construct() {
			$this->compute=new Compute;
		}
	}
?>