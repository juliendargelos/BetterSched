<?php
	require_once('functions.php');
	date_default_timezone_set('Europe/Paris');
	$default=[
		'group'=>'MMI',
		'year'=>1,
		'week'=>date('W'),
		'day'=>strtolower(date('l'))
	];
	$param=[];
	foreach($default as $key=>$p) $param[$key]=isset($_POST[$key]) ? (is_numeric($p) ? intval($_POST[$key]) : $_POST[$key]) : $p;
	$sched=get_sched($param['group'],$param['year'],$param['week']);
	if($sched===false) {
		$param=$default;
		$sched=get_sched($param['group'],$param['year'],$param['week']);
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
		<title>BetterSched</title>
		<link rel="stylesheet" type="text/css" href="style.css"/>
	</head>
	<body>
		<div id="date">
			<?php
				$break=false;
				foreach($sched[$param['day']] as $h) {
					foreach($h as $c) {
						if(array_key_exists('day',$c) && array_key_exists('daynumber',$c) && array_key_exists('month',$c) && array_key_exists('year',$c)) {
							echo $c['day'].' '.$c['daynumber']. ' '.$c['month']. ' '.$c['year'];
							$break=true;
							break;
						}
					}
					if($break) break;
				}
			?>
		</div>
		<table id="main">
			<tr>
				<td id="input" colspan="7">
					<form method="POST">
						<div>
							<label for="input-group">Groupe</label>
							<select id="input-group" name="group">
								<option value="MMI"<?php echo $param['group']=='MMI' ? ' selected' : ''; ?>>MMI</option>
								<option value="PUB"<?php echo $param['group']=='PUB' ? ' selected' : ''; ?>>PUB</option>
								<option value="LP"<?php echo $param['group']=='LP' ? ' selected' : ''; ?>>LP</option>
							</select>
						</div>
						<div>
							<label for="input-year">Année</label>
							<select id="input-year" name="year">
								<option value="1"<?php echo $param['year']==1 ? ' selected' : ''; ?>>1A</option>
								<option value="2"<?php echo $param['year']==2 ? ' selected' : ''; ?>>2A</option>
							</select>
						</div>
						<div>
							<label for="input-week">Semaine</label>
							<select id="input-week" name="week">
								<?php
									for($w=1; $w<=53; $w++) echo '<option value="'.$w.'"'.($param['week']==$w ? ' selected' : '').'>'.$w.'</option>';
								?>
							</select>
						</div>
						<div>
							<label for="input-day">Jour</label>
							<select id="input-day" name="day">
								<?php
									$days=fr_en_day('fr');
									foreach($days as $en_day=>$fr_day) {
										echo '<option value="'.$en_day.'"'.($param['day']==$en_day ? ' selected' : '').'>'.$fr_day.'</option>';
									}
								?>
							</select>
						</div>
						<div>
							<input type="submit" value="Valider"/>
						</div>
					</form>
				</td>
			</tr>
			<tr id="sched">
				<td>
					<?php
						echo daydata_to_htmltable($param['day'],$sched[$param['day']]);
					?>
				</td>
			</tr>
		</table>
		<script type="text/javascript">
			window.onscroll=function() {
				if(document.body.scrollTop>=176) document.getElementById('date').className='show';
				else document.getElementById('date').className='';
			}
		</script>
	</body>
</html>