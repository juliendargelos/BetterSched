<?php
	// Paramètres
	class Param {
		public $data;
		
		public function __construct() {
			$default=[
				'group'=>'MMI',
				'year'=>1,
				'week'=>date('W'),
				'day'=>strtolower(date('l'))
			];
			$this->data=$default;
			foreach($default as $key=>$p) $this->data[$key]=isset($_POST[$key]) ? (is_numeric($p) ? intval($_POST[$key]) : $_POST[$key]) : $p;
		}
	}
?>