<?php
	// URL du script de connexion
	define('SIGNIN_URL','http://syrah.iut.u-bordeaux3.fr/gpu/sat/index.php?page_param=accueilsatellys.php&cat=0&numpage=1&niv=0&clef=/');
	
	// URL de la page GPU
	define('GPU_URL','http://syrah.iut.u-bordeaux3.fr/gpu/gpu/index.php');
	
	// URL de la page d'accueil de l'emploi du temps
	define('HOMESCHED_URL','http://syrah.iut.u-bordeaux3.fr/gpu/gpu/index.php?page_param=accueil.php&cat=0&numpage=1&niv=1&clef=/305/');
	
	// URL de l'emploi du temps
	define('SCHED_URL','http://syrah.iut.u-bordeaux3.fr/gpu/gpu/index.php?page_param=fpfilieres.php&cat=0&numpage=1&niv=2&clef=/305/306/');
	
	// Message de confirmation de connexion
	define('SIGNIN_MESSAGE','CONNEXION ETABLIE');
	
	// Message de confirmation de l'obtention de l'emploi du temps
	define('SCHED_MESSAGE','Emploi du temps Filières');
	
	// Agent utilisateur utilisé pour les requêtes
	define('USER_AGENT','User-Agent	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11) AppleWebKit/601.1.56 (KHTML, like Gecko) Version/9.0 Safari/601.1.56');
	
	// Affichage des erreurs
	define('DISPLAY_ERROR',false);
?>