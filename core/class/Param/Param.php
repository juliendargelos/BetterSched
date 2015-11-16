<?php
	// Paramètres
	class Param {
		public $data;
		private $expire=315360000; // 10 ans

		// Récupération automatique des paramètres POST et des cookies lors de l'instanciation
		public function __construct() {
			$default=[
				'group'=>['value'=>'MMI','numeric'=>false,'cookie'=>true],
				'td'=>['value'=>1,'numeric'=>true,'cookie'=>true],
				'tp'=>['value'=>1,'numeric'=>true,'cookie'=>true],
				'year'=>['value'=>1,'numeric'=>true,'cookie'=>true],
				'week'=>['value'=>intval(date('W')),'numeric'=>true,'cookie'=>false],
				'day'=>['value'=>strtolower(date('l')),'numeric'=>false,'cookie'=>false]
			];
			$this->data=$default;
			foreach($default as $key=>$p) {
				$this->data[$key]=$p['cookie'] && isset($_COOKIE[$key]) ? ($p['numeric'] ? intval($_COOKIE[$key]) : $_COOKIE[$key]) : $p['value'];
			}
			foreach($default as $key=>$p) {
				if(isset($_POST[$key])) $this->data[$key]=$p['numeric'] ? intval($_POST[$key]) : $_POST[$key];
			}
			foreach($this->data as $key=>$p) {
				if($default[$key]['cookie']) setcookie($key,$p,time()+$this->expire);
			}
		}
	}
?>
