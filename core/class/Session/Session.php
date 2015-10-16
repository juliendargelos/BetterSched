<?php
	// Session
	class Session {
		private $server;
		public function set($username,$password) {
			$this->server->user->username=$username;
			$this->server->user->password=$password;
			if($this->server->signin()) {
				$this->username=$username;
				$this->password=$password;
				return true;
			}
			else return false;
		}
		
		public function check() {
			if($this->username!=null && $this->password!=null) {
				$this->server->user->username=$this->username;
				$this->server->user->password=$this->password;
				if($this->server->signin()) return true;
				else return false;
			}
			else return false;
		}
		
		public function remove($data) {
			unset($_SESSION[$data]);
		}
		
		public function destroy() {
			session_destroy();
		}
		
		public function __get($data) {
			if(isset($_SESSION[$data])) return $_SESSION[$data];
			else return null;
		}
		
		public function __set($data,$value) {
			$_SESSION[$data]=$value;
		}
		
		public function __construct() {
			if(session_id()=='') @session_start();
			$this->server=new Server;
		}
	}
?>