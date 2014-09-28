<?php
/**
 * API PHP myFox
 * 
 * @author Johan V.	www.hotfirenet.Com	twitter.com/hotfirenet
 *
 */
define('BASEPATH', 'OK');

if(!file_exists('wrapper.myfox.php'))
	die('il manque le fichier wrapper.myfox.php');
	
	include 'wrapper.myfox.php';

if(!file_exists('config.php'))
	die('il manque le fichier config.php');
	
	include 'config.php';	
	
	session_start();
	
	$now = time();
		
	$myFox = new myFox($clientId, $clientSecret, $username, $password);
	
	if(($now + 10) > $_SESSION['expires_in'])
	{		
		if ($now > $_SESSION['expires_in'])
			$resultToken = $myFox->authentication();
		else
			$resultToken = $myFox->authentication($refresh_token);
	
		$parseToken = json_decode($resultToken);
		
		$_SESSION['access_token'] =  $parseToken->access_token;
		$_SESSION['expires_in'] = ($now + $parseToken->expires_in);
		$_SESSION['refresh_token'] = $parseToken->refresh_token;
	}
	
	//On test si l'id de la station est renseigné
	if(empty($siteId))
	{
		$listClientSite = json_decode($myFox->listClientSite($_SESSION['access_token']));
		foreach($listClientSite->payload as $payload)
		{
			foreach($payload as $items)
			{
				echo 'l\'identifiant de la centrale <b>' . $items->label . '</b> a pour identifiant: <b>' . $items->siteId .'</b>, merci de renseigner le fichier <b>config.php</b> !<br />';
			}
		}
		exit();
	}
	
	/**
	* URI
	*/
	$command = array();

	$requestURI = explode('/', $_SERVER['REQUEST_URI']);
	$scriptName = explode('/',$_SERVER['SCRIPT_NAME']);

	for($i= 0;$i < sizeof($scriptName);$i++)
	{
		if ($requestURI[$i] == $scriptName[$i])
		{
			unset($requestURI[$i]);
		}
	}
	 
	$command = array_values($requestURI);

	if($security_key != 'CLEDESECURITE')
	{
		$connectid = $command[0];
		
		/**
		* Début d'ajout de Cyril LOPEZ
		*/ 
		
		# Mise en place d'une sécurité
		if ($connectid != $security_key) 
		{
			@mail($recipient, $subject, $mail_body, $header);
			die ('Erreur ! Acc&eacute;s interdit !');
		}
		
		/**
		* Fin d'ajout de Cyril LOPEZ
		*/
		
		$command = array_slice($command, 1);
	}	
	
	$i = 0;
	foreach($command as $valueURI)
	{
		if($i == 0)
			$commande = $valueURI;
		else	
		${"parametre$i"} = $valueURI;
		
		$i++;
	}	
	
	switch($commande)
	{
		case 'etat':
			echo $myFox->getSecurity($siteId, $_SESSION['access_token']);
			break;
			
		case 'protection':
			echo $myFox->setSecurity($siteId, $parametre1, $_SESSION['access_token']);
			break;
			
		case 'scenario':
			echo $myFox->scenario($siteId, $parametre1, $parametre2, $_SESSION['access_token']);
			break;			
			
		default:
		
			break;
	
	}
