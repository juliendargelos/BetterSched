<?php
	// Requête vers le serveur
	class Request {
		public $url=null;
		public $param=[];
		public $handle=null;
		private $output=null;
		
		public function exec() {
			if(is_string($this->url) && is_array($this->param)) {
				if($this->handle==null) {
					$curl=curl_init();
					curl_setopt($curl,CURLOPT_HEADER,true);
					curl_setopt($curl,CURLOPT_USERAGENT,USER_AGENT);
					curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
					curl_setopt($curl,CURLOPT_POST,true);
					curl_setopt($curl,CURLOPT_COOKIESESSION,true);
				}
				else {
					$curl=$this->handle->curl;
					curl_setopt($curl,CURLOPT_COOKIE,$this->handle->cookies);
				}
				curl_setopt($curl,CURLOPT_POSTFIELDS,$this->param);
				curl_setopt($curl,CURLOPT_URL,$this->url);
		
				$output=curl_exec($curl);
		
				preg_match_all('#Set-Cookie: (.+?;)#',$output,$matches);
				$cookies='';
				foreach($matches[1] as $cookie) $cookies.=$cookie;
				if($this->handle!=null) $cookies=$this->handle->cookies.$cookies;
		
				$this->handle=new Handle($curl,$cookies);
				$this->output=$output;
				
				return true;
			}
			else return false;
		}
		
		public function close() {
			$this->url=null;
			$this->param=[];
			if($this->handle->curl!=null) curl_close($this->handle->curl);
			$this->handle=null;
			$this->output=null;
		}
		
		public function __get($output) {
			return $output=='output' ? $this->output : null;
		}
	}
?>