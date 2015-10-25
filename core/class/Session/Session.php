<?php
	// Session
	class Session {
		private $server;
		private $crypt;
		private $expire=315360000;

		// Création d'une session
		public function set($username,$password) {
			$this->server->user->username=$username;
			$this->server->user->password=$password;
			if($this->server->signin()) {
				$this->username=$username;
				$this->password=$password;
				setcookie('session',$this->crypt->encode($password,$username),time()+$this->expire);
				return true;
			}
			else return false;
		}

		// Vérification de l'existence et l'authenticité d'une session
		public function check() {
			if($this->username!=null && $this->password!=null) {
				$this->server->user->username=$this->username;
				$this->server->user->password=$this->password;
				if($this->server->signin()) return true;
				else return false;
			}
			elseif(isset($_COOKIE['session'])) {
				$username=$this->crypt->extract_key($_COOKIE['session']);
				$password=$this->crypt->decode($_COOKIE['session']);
				$this->server->user->username=$username;
				$this->server->user->password=$password;
				if($this->server->signin()) {
					$this->username=$username;
					$this->password=$password;
					return true;
				}
			}
			else return false;
		}

		// Suppression d'une variable de session
		public function remove($data) {
			unset($_SESSION[$data]);
		}

		// Destruction d'une session
		public function destroy() {
			session_destroy();
		}

		// Récupération de la valeur d'une variable de session
		public function __get($data) {
			if(isset($_SESSION[$data])) return $_SESSION[$data];
			else return null;
		}

		// Allocation d'une valeur à une variable de session
		public function __set($data,$value) {
			$_SESSION[$data]=$value;
		}

		public function __construct() {
			if(session_id()=='') @session_start();
			$this->server=new Server;
			$this->crypt=new Crypt;
		}
	}
?>
