<?php
	
	require_once('const.php');
	
	// Affichage d'un message d'erreur
	function error($message) {
		if(DISPLAY_ERROR) echo htmlentities($message);
		return false;
	}
	
	function fr_en_day($lang) {
		if($lang=='en') {
			return [
				'Lundi'=>'monday',
				'Mardi'=>'tuesday',
				'Mercredi'=>'wednesday',
				'Jeudi'=>'thursday',
				'Vendredi'=>'friday',
				'Samedi'=>'saturday'
			];
		}
		else return [
			'monday'=>'Lundi',
			'tuesday'=>'Mardi',
			'wednesday'=>'Mercredi',
			'thursday'=>'Jeudi',
			'friday'=>'Vendredi',
			'saturday'=>'Samedi'
		];
	}
	
	// Envoi d'une requête HTTP avec cURL
	function curl_request($url,$param,$handle=null) {
		if($handle==null) {
			$curl=curl_init();
			curl_setopt($curl,CURLOPT_HEADER,true);
			curl_setopt($curl,CURLOPT_USERAGENT,USER_AGENT);
			curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
			curl_setopt($curl,CURLOPT_POST,true);
			curl_setopt($curl,CURLOPT_COOKIESESSION,true);
		}
		else {
			$curl=$handle['curl'];
			curl_setopt($curl,CURLOPT_COOKIE,$handle['cookies']);
		}
		curl_setopt($curl,CURLOPT_POSTFIELDS,$param);
		curl_setopt($curl,CURLOPT_URL,$url);
		
		$output=curl_exec($curl);
		
		preg_match_all('#Set-Cookie: (.+?;)#',$output,$matches);
		$cookies='';
		foreach($matches[1] as $cookie) $cookies.=$cookie;
		if($handle!=null) $cookies=$handle['cookies'].$cookies;
		
		return ['handle'=>['curl'=>$curl,'cookies'=>$cookies],'output'=>$output];
	}
	
	// Récupération de l'emploi du temps pour un groupe et une semaine donnée
	function get_sched($group,$year,$week) {
		
		// Détection d'erreurs dans les paramètres indiqués
		if($group!='MMI' && $group!='PUB' && $group!='LP') return error('Le groupe indiqué n\'existe pas.');
		if(($year!=1 && $year!=2) || ($year==2 && $group=='LP')) return error('L\'année d\'enseignement indiquée n\'existe pas.');
		if($week>53 || $week<1) return error('La semaine indiquée n\'existe pas.');
		
		// Correspondance entre les paramètres fournis et les numéros des semestres
		$semesters=[
			['semester'=>'1','year'=>1,'begin'=>38,'end'=>53,'value'=>1],
			['semester'=>'2','year'=>1,'begin'=>2,'end'=>15],
			['semester'=>'3','year'=>2,'begin'=>38,'end'=>53,'value'=>1],
			['semester'=>'4','year'=>2,'begin'=>2,'end'=>15]
		];
		
		// Association du semestre correspondant aux paramètres
		foreach($semesters as $s) {
			if($s['year']==$year && (($week>=$s['begin'] && $week<=$s['end']) || (array_key_exists('value',$s) && $week==$s['value']))) {
				$group.='_S'.$s['semester'];
				break;
			}
		}
		
		// Paramètres POST de connexion
		$signin_param=[
			'modeglobal'=>'',
			'modeconnect'=>'connect',
			'util'=>SIGNIN_USERNAME,
			'acct_pass'=>SIGNIN_PASSWORD
		];
		
		// Paramètres POST de récupération de l'emploi du temps
		$sched_param=[
			'mode'=>'edt',
			'idee'=>'',
			'aller'=>'0',
			'semaine'=>strval($week),
			'liste'=>'-1',
			'aff_edtabs'=>'-1',
			'ansemaine'=>($week<38 ? '2016' : '2015'),
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
		
		// Connexion
		$result=curl_request(SIGNIN_URL,$signin_param);
		if(strpos($result['output'],SIGNIN_MESSAGE)===false) return error('Erreur lors de la connexion.');
		
		// Requête vers la page GPU (actualisation des cookies)
		$result=curl_request(GPU_URL,[],$result['handle']);
		
		// Requête vers la page d'accueil de l'emploi du temps (actualisation des cookies + actualisation manuelle des cookies avec le groupe indiqué)
		$result=curl_request(HOMESCHED_URL,[],$result['handle']);
		$result['handle']['cookies'].='filiere='.$group.';';
		
		// Récupération de l'emploi du temps
		$result=curl_request(SCHED_URL,$sched_param,$result['handle']);
		if(strpos($result['output'],SCHED_MESSAGE)===false) return error('Erreur lors de la récupération de l\'emploi du temps.');
		
		// Fermeture de la ressource cURL
		curl_close($result['handle']['curl']);
		
		// Correspondance français/anglais des jours
		$fr_en_day=fr_en_day('en');
		
		// Tableau brut de l'emploi du temps
		$plain_sched=[
			'monday'=>[],
			'tuesday'=>[],
			'wednesday'=>[],
			'thursday'=>[],
			'friday'=>[],
			'saturday'=>[]
		];
		
		// Tableau de l'emploi du temps
		$sched=$plain_sched;
		
		// Remplissage du tableau de l'emploi du temps avec les heures
		foreach($sched as $dayname=>$day) {
			for($h=8; $h<=19; $h++) {
				$sched[$dayname][$h.'h']=[];
				$sched[$dayname][$h.'h30']=[];
			}
		}
		
		// Parsing du document html
		$document=new DOMDocument();
		@$document->loadHTML($result['output']);
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
							$content=preg_replace('#\s[A-Z\s]+\d?$#','',$content);
							// Correction des troncatures
							$content=preg_replace('#informatio$#','information',$content);
							// Majuscule à la première lettre et enregistrement
							$data['content']=ucfirst($content);
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
								array_push($plain_sched[$fr_en_day[$data['day']]],$data);
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
		
		// Retour de l'emploi du temps
		return $sched;
	}
	
	// Transformation des données d'un jour en tableau HTML
	function daydata_to_htmltable($dayname,$daydata) {
	
		// Correspondance anglais/français des jours
		$fr_en_day=fr_en_day('fr');
		
		$max_colspan=1;
		foreach($daydata as $kh=>$vh) if(count($vh)>$max_colspan) $max_colspan=count($vh);
		$max_colspan++;
		
		$html_table='<table><tr><td colspan="'.$max_colspan.'"></tr>';
		foreach($daydata as $kh=>$vh) {
			$html_table.='<tr><td><span>'.(substr($kh,-2)=='30' ? '30' : $kh).'</span></td>';
			if(count($vh)==0) $html_table.=(count($vh)==0 ? '<td colspan="'.$max_colspan.'"></td>' : '');
			foreach($vh as $c) {
				$cbegin=false;
				foreach($daydata as $lkh=>$lvh) {
					if($lkh==$kh) break;
					foreach($lvh as $lc) {
						if($lc['id']==$c['id']) {
							$cbegin=true;
							break;
						}
					}
					if($cbegin) break;
				}
				if(!$cbegin) {
					$length=0;
					foreach($daydata as $lkh=>$lvh) {
						foreach($lvh as $lc) if($lc['id']==$c['id']) $length++;
					}
					$html_table.='<td class="block" rowspan="'.$length.'" colspan="'.($max_colspan-count($vh)).'"><div>';
					$html_table.='<span>'.$c['content'].'</span><br>';
					if($c['professor']!==false) $html_table.='<span>'.$c['professor'].'</span>';
					if($c['classroom']!==false) $html_table.='<span>'.$c['classroom'].'</span>';
					$html_table.='</div></td>';
				}
			}
			$html_table.='</tr>';
		}
		$html_table.='</table>';
		
		return $html_table;
	}
?>