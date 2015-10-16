<?php
	// Construction des tableaux
	class Compute {
		private $languages;
		
		public function day($day,$daydata) {
			$max_colspan=1;
			foreach($daydata as $kh=>$vh) if(count($vh)>$max_colspan) $max_colspan=count($vh);
			$max_colspan++;
			
			unset($daydata['date']);
	
			$html_table='<table><tr><td colspan="'.$max_colspan.'">'.$this->languages->fr[$day].'</td></tr>';
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
						$html_table.='<td class="block" rowspan="'.$length.'" colspan="'.($max_colspan-count($vh)).'">';
						$html_table.='<div style="background-color:'.$c['color'].';">';
						$html_table.='<span>'.$c['content'].'</span><br>';
						if($c['professor']!==false) $html_table.='<span style="color:'.$c['color'].';">'.$c['professor'].'</span>';
						if($c['classroom']!==false) $html_table.='<span style="color:'.$c['color'].';">'.$c['classroom'].'</span>';
						$html_table.='</div></td>';
					}
				}
				$html_table.='</tr>';
			}
			$html_table.='</table>';
	
			return $html_table;
		}
		
		public function week($data) {
			$html_table='<table><tr>';
			foreach($data as $day=>$daydata) {
				$html_table.='<td>'.$this->day($day,$daydata).'</td>';
			}
			$html_table.='</tr></table>';
	
			return $html_table;
		}
		
		public function __construct() {
			$this->languages=new Languages;
		}
	}
?>