<?php
	// Propriétés et méthodes du serveur
	class Server {
		public $files;
		public $messages;
		public $request;
		public $user;

		// Authentification sur le serveur
		public function signin() {
			if(is_string($this->user->username) && is_string($this->user->username)) {
				$this->request->close();
				$this->request->url=$this->files->signin;
				$this->request->param=[
					'modeglobal'=>'',
					'modeconnect'=>'connect',
					'util'=>$this->user->username,
					'acct_pass'=>$this->user->password
				];
				$this->request->exec();
				return strpos($this->request->output,$this->messages->signin)!==false ? true : false;
			}
			else return false;
		}

		// Récupération de l'emploi du temps depuis le serveur
		public function get_sched($week,$group) {
			$this->request->param=[];

			// Requête vers la page GPU (actualisation des cookies)
			$this->request->url=$this->files->gpu;
			$this->request->exec();

			// Requête vers la page d'accueil de l'emploi du temps (actualisation des cookies + actualisation manuelle des cookies avec le groupe indiqué)
			$this->request->url=$this->files->homesched;
			$this->request->exec();
			$this->request->handle->cookies.='filiere='.$group.';';

			// Récupération de l'emploi du temps
			$this->request->param=[
				'mode'=>'edt',
				'idee'=>'',
				'aller'=>'0',
				'semaine'=>strval($week),
				'liste'=>'-1',
				'aff_edtabs'=>'-1',
				'ansemaine'=>($week<38 ? strval(BEGIN_YEAR+1) : strval(BEGIN_YEAR)),
				'idedtselect'=>'0',
				'jouredt'=>'',
				'debutedt'=>'',
				'copiercouper'=>'',
				'left'=>'0',
				'top'=>'0',
				'taillepolice'=>'10',
				'onglet_actif'=>'1',
				'filiere'=>$group
			];
			$this->request->url=$this->files->sched;
			$this->request->exec();
			$result=strpos($this->request->output,$this->messages->sched)!==false ? $this->request->output : false;
			$this->request->close();
			return $result;
		}

		public function __construct() {
			$this->files=new Files;
			$this->messages=new Messages;
			$this->request=new Request;
			$this->user=new User;
		}
	}
?>
