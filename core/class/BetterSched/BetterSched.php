<?php
	// Emploi du temps ordonné
	class BetterSched {
		public $user;
		public $week;
		public $group;
		public $tdtp;
		public $year;
		public $languages;
		private $semester=[
			['semester'=>'1','year'=>1,'begin'=>38,'end'=>53,'value'=>1],
			['semester'=>'2','year'=>1,'begin'=>2,'end'=>15],
			['semester'=>'3','year'=>2,'begin'=>38,'end'=>53,'value'=>1],
			['semester'=>'4','year'=>2,'begin'=>2,'end'=>15]
		];
		private $sched;
		private $data;
		
		// Transformation des couleurs
		private function color_convert($color) {
			$color=strtolower($color);
			$color_nColor=[
				'#ffff33'=>'#cfcf29',
				'#0033ff'=>'#2c49bd',
				'#66cc00'=>'#83c145',
				'#ff66cc'=>'#d571b4',
				'#0000ff'=>'#4c4cf6',
				'#ffffff'=>'#bfbbbb',
				'#ff3300'=>'#e74c3c',
				'#0099ff'=>'#4b8ab4',
				'#ccffff'=>'#87c4c4',
				'#ffff00'=>'#d5d51e',
				'#33ccff'=>'#5db7d5',
				'#ffcc00'=>'#ffcc00',
				'#33ff00'=>'#72da58',
				'#ff6699'=>'#e35987',
				'#ffff66'=>'#cdcd53',
				'#ffff99'=>'#c7c779'
			];
			return array_key_exists($color,$color_nColor) ? $color_nColor[$color] : $color;
		}
		
		private function parse($plain_data) {
			// Tableau brut de l'emploi du temps
			$plain_sched=$this->sched->plain;
	
			// Tableau de l'emploi du temps
			$sched=$this->sched->hour;
			
			$document=new DOMDocument();
			@$document->loadHTML($plain_data);
			$sched_table=$document->getElementsByTagName('table')->item(3);
			for($i=0; $i<$sched_table->childNodes->length; $i++) {
				// Toute les 5 lignes du tableau, il ne s'agit que de l'affichage de l'heure donc inutile d'en prendre compte
				if($i%5!==0) {
					$tr=$sched_table->childNodes->item($i);
					for($j=0; $j<$tr->childNodes->length; $j++) {
						$td=$tr->childNodes->item($j);
						// Vérification qu'il s'agit bien d'un nœud élément (et pas texte par exemple)
						if($td->nodeType==XML_ELEMENT_NODE) {
							$h=$td->getElementsByTagName('table');
							if($h->length>0) {
								$data=[];
								// Récupération de la couleur
								$data['color']=$this->color_convert($td->getAttribute('bgcolor'));
								// Récupération du nom du cours, suppression des informations inutiles
								$content=$td->getElementsByTagName('font')->item(0)->nodeValue;
								$content=preg_replace('#^[\s\n]+#','',$content);
								$content=preg_replace('#[\s\n]+$#','',$content);
								$content=preg_replace('#^MMI\s#','',$content);
								$content=preg_replace('#^[Ss]emestre\s\d\s#','',$content);
								$content=preg_replace('#^cours\s#i','',$content);
								$content=preg_replace('#^groupe\s#i','',$content);
								while(preg_match('#\s\((cours|TP|\d+ PC|LP|multimédia)\)\s?\d?$#',$content) || preg_match('#\sSalle [\d-]+\s?\d?$#',$content)) {
									$content=preg_replace('#\s\((cours|TP|\d+ PC|LP|multimédia)\)\s?\d?$#i','',$content);
									$content=preg_replace('#\sSalle\s[\d-]+\s?\d?$#i','',$content);
									$content=preg_replace('#\sAmphi\s[\d]+\s?\d?$#i','',$content);
								}
								$content=preg_replace('#\s[A-Z\-\s]+\d?$#','',$content);
								// Correction des troncatures
								$content=preg_replace('#informatio$#','information',$content);
								// Majuscule à la première lettre et enregistrement
								$data['content']=ucfirst($content);
								// Conditions de suppression d'éléments en fonctions du TD/TP et du groupe (MMI Seulement)
								$td=floor($this->tdtp/10);
								$tp=$this->tdtp-$td*10;
								$tdtp_cond=[
									$this->group!='MMI' || $this->tdtp==0,
									!preg_match('#\bTD\s*\d\b#',$data['content']) && !preg_match('#\bTP\s*\d\b#',$data['content']),
									$this->tdtp!=0 && (($this->td<=2 && $this->year==1) || ($td>=3 && $this->year==2)) && preg_match('#\bTD\s*'.preg_quote($td).'\b#',$data['content']),
									$this->tdtp!=0 && (($this->tp<=3 && $this->year==1) || ($tp>=4 && $this->year==2)) && preg_match('#\bTP\s*'.preg_quote($tp).'\b#',$data['content'])
									
								];
								if($tdtp_cond[0] || $tdtp_cond[1] || $tdtp_cond[2] || $tdtp_cond[3]) {
									$h=$h->item(0)->getElementsByTagName('tr');
									for($k=1; $k<$h->length; $k++) {
										$d=$h->item($k)->getElementsByTagName('td');
										if($d->length>=2) {
											$key=$d->item(0)->nodeValue;
											$value=$d->item(1)->nodeValue;
											// Récupération de la date
											if(preg_match('#^\s*(lundi|mardi|mercredi|jeudi|vendredi|samedi)\s+(\d+)\s+(janvier|février|mars|avril|mai|juin|juillet|août|septembre|octobre|novembre|décembre)\s+(\d+)\s*$#i',$value,$match)) {
												$data['day']=$match[1];
												$data['daynumber']=$match[2];
												$data['month']=$match[3];
												$data['year']=$match[4];
											}
											// Récupération de l'heure
											elseif(preg_match('#^\s*(\d+h\d+)\s+a\s+(\d+h\d+)\s*$#i',$value,$match)) {
												$data['beginhour']=$match[1];
												$data['endhour']=$match[2];
											}
											// Récupération des noms des enseignants
											elseif(preg_match('#^\s*Enseignant\(s\)\s*$#',$key)) {
												$professor=explode('-',preg_replace('#-+$#','',$value));
												$data['professor']='';
												foreach($professor as $p) $data['professor'].=strtoupper(substr($p,0,1)).'. '.ucfirst(strtolower(substr($p,1))).', ';
												if($data['professor']=='. , ') $data['professor']=false;
												else $data['professor']=substr($data['professor'],0,-2);
											}
											// Récupération du numéro de la salle
											elseif(preg_match('#^\s*Salle\(s\)\s*$#',$key)) {
												$data['classroom']=preg_replace('#-+$#','',$value);
												if($data['classroom']=='') $data['classroom']=false;
											}
										}
									}
									// Remplissage du tableau de l'emploi du temps avec les données récupérées
									if(array_key_exists('day',$data) && array_key_exists('daynumber',$data) && array_key_exists('month',$data) && array_key_exists('year',$data) && array_key_exists('beginhour',$data) && array_key_exists('endhour',$data) && array_key_exists('professor',$data) && array_key_exists('classroom',$data)) {
										$data['id']=md5(json_encode($data));
										array_push($plain_sched[$this->languages->en[$data['day']]],$data);
										if($sched[$this->languages->en[$data['day']]]['date']===false) $sched[$this->languages->en[$data['day']]]['date']=$data['day'].' '.$data['daynumber'].' '.$data['month'].' '.$data['year'];
									}
								}
							}
						}
					}
				}
			}
	
			foreach($plain_sched as $dayname=>$day) {
				foreach($day as $c) {
					$beginhour=intval(preg_replace('#^(\d+)h\d*$#','$1',$c['beginhour']))+(intval(preg_replace('#^\d+h(\d*)$#','$1',$c['beginhour']))==30 ? 0.5 : 0);
					$endhour=intval(preg_replace('#^(\d+)h\d*$#','$1',$c['endhour']))+(intval(preg_replace('#^\d+h(\d*)$#','$1',$c['endhour']))==30 ? 0.5 : 0);
					for($h=$beginhour; $h<$endhour; $h+=0.5) {
						$hour=intval($h).'h'.(intval($h)!=$h ? '30' : '');
						array_push($sched[$dayname][$hour],$c);
					}
				}
			}
			
			$this->data=$sched;
		}
		
		public function get() {
			$week=$this->week;
			$group=$this->group;
			$year=$this->year;
			
			// Vérification des paramètres
			if($group!='MMI' && $group!='PUB' && $group!='LP') return false;
			if(($year!=1 && $year!=2) || ($year==2 && $group=='LP')) return false;
			if($week>53 || $week<1) return false;
			
			// Association du semestre correspondant aux paramètres
			$semester_found=false;
			foreach($this->semester as $s) {
				if($s['year']==$year && (($week>=$s['begin'] && $week<=$s['end']) || (array_key_exists('value',$s) && $week==$s['value']))) {
					$group.='_S'.$s['semester'];
					$semester_found=true;
					break;
				}
			}
			if(!$semester_found) return false;
			
			// Connexion au serveur
			$server=new Server;
			$server->user->username=$this->user->username;
			$server->user->password=$this->user->password;
			if($server->signin()) {
				$plain_data=$server->get_sched($week,$group);
				if($plain_data!==false) {
					$this->sched=$this->parse($plain_data);
					return true;
				}
				else return false;
			}
			else return false;
		}
		
		public function __get($data) {
			return $data=='data' ? $this->data : null;
		}
		
		public function __construct() {
			$this->user=new User;
			$this->sched=new Sched;
			$this->languages=new Languages;
		}
	}
?>