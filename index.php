<?php
/**
 * Wrapper PHP myFox
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
	
	echo $myFox->listClientSite($_SESSION['access_token']);

	echo '<br />';	
	
	echo $myFox->getSecurity($siteId, $_SESSION['access_token']);

	//Ok	
	//echo $myFox->setSecurity($siteId, 'armed', $_SESSION['access_token']);

	echo '<br />';
	
	echo $myFox->listScenario($siteId, $_SESSION['access_token']);
	
	echo '<br />';
	
	echo $myFox->scenario($siteId, $scenarioId, 'enable', $_SESSION['access_token']);
	echo '<br />';
	echo $myFox->scenario($siteId, $scenarioId, 'disable', $_SESSION['access_token']);
	echo '<br />';
	echo $myFox->scenario($siteId, $scenarioId, 'play', $_SESSION['access_token']);
	echo '<br />';
	
	
	

	
?>
<pre>
	<?php print_r($_SESSION); ?>
</pre>
