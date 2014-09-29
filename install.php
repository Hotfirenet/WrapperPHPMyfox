<?php
/**
 * API PHP myFox
 * 
 * @author Johan V.	www.hotfirenet.Com	twitter.com/hotfirenet
 *
 */
define('BASEPATH', 'OK');

if(function_exists('apache_get_modules') )
{
	if(!in_array('mod_rewrite',apache_get_modules()))
		die('Le module rewrite n\'est pas activ&eacute;');
}

$checkFile = array('install.php', '.htaccess.install', 'config.php');

foreach($checkFile as $file)
{
	if(!is_writable($file))
	{
		if($file == 'myFox.php')
			die('Le fichier '.$file.' n\'a pas les droits d\'&eacute;criture ! je vous conseille de faire un chmod 640.');
		else
			die('Le fichier '.$file.' n\'a pas les droits d\'&eacute;criture ! je vous conseille de faire un chmod 644.');
	}	
}

if(isset($_POST)) 
{
	extract($_POST);	
	
	if(isset($username) && isset($password) && isset($clientId) && isset($clientSecret))
	{
		if(!$siteId)
		{
			if(file_exists('wrapper.myfox.php')) include 'wrapper.myfox.php';
			$myFox = new myFox($clientId, $clientSecret, $username, $password);
			$auth = $myFox->authentication();
			$parseToken = json_decode($auth);

			if($parseToken->access_token)
				$listClientSite = json_decode($myFox->listClientSite($parseToken->access_token));	
			else 
				die('Merci de verifier vos identifiants !');
		}
		else 
			$stop = 'OK';
	}
}


	
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Install myAPIFox 2</title>
  </head>
  <body>
	<form action="#" method="post">
		<h1>Parametre de connexion</h1>
		<input name="username" type="text" autofocus="" required="" placeholder="Username" value="<?php if(isset($username)) echo $username; ?>"></input><br />
		<input name="password" type="password" required="" placeholder="Password" value="<?php if(isset($password)) echo $password; ?>"></input><br />
		<input name="clientId" type="text" required="" placeholder="Username" value="<?php if(isset($clientId)) echo $clientId; ?>"></input><br />
		<input name="clientSecret" type="password" required="" placeholder="clientSecret" value="<?php if(isset($clientSecret)) echo $clientSecret; ?>"></input><br />	
		vos centrales d'alarme:
		<ul>
		<?php
			if($listClientSite->status == 'OK')
			{
				foreach($listClientSite->payload as $payload)
				{
					foreach($payload as $items)
					{
						echo '<li><input type="radio" name="siteId" value="' . $items->siteId .'">' . $items->label . '</li>';
					}
				}			
			}			
		?>
		</ul>
		<h1>Configuration de la s&eacute;curit&eacute;</h1>
		<p>non obligatoire</p>
		<input name="security_key" placeholder="security_key" value="<?php if(isset($security_key)) echo $security_key; ?>"></input><br />
		<input name="email" placeholder="expediteur" value="<?php if(isset($email)) echo $email; ?>"></input><br />	
		<input name="recipient" placeholder="destinataire" value="<?php if(isset($recipient)) echo $recipient; ?>"></input><br />			
		<button type="submit">Ok</button>		
	</form>
  </body>
</html>
<?php
	if(!isset($stop))
		exit;
		
	$configFile = 'config.php';
	
	$content = @file_get_contents($configFile);
	
	if(isset($username) && !empty($username))	
		$content = str_replace('$username = \'\'', '$username = \''.$username.'\'', $content);
	
	if(isset($password) && !empty($password))
		$content = str_replace('$password = \'\'', '$password = \''.$password.'\'', $content);
	
	if(isset($clientId) && !empty($clientId))	
		$content = str_replace('$clientId = \'\'', '$clientId = \''.$clientId.'\'', $content);
	
	if(isset($clientSecret) && !empty($clientSecret))
		$content = str_replace('$clientSecret = \'\'', '$clientSecret = \''.$clientSecret.'\'', $content);	
	
	if(isset($siteId) && !empty($siteId))	
		$content = str_replace('$siteId = ', '$siteId = '.$siteId, $content);	
	
	if(isset($security_key) && !empty($security_key))
		$content = str_replace('$security_key = \'CLEDESECURITE\'', '$security_key = \''.$security_key.'\'', $content);

	if(isset($email) && !empty($email))
		$content = str_replace('$email = \'EMAIL\'', '$email = \''.$email.'\'', $content);
	
	if(isset($recipient) && !empty($recipient))
		$content = str_replace('$recipient = \'EMAIL\'', '$recipient = \''.$recipient.'\'', $content);
	
	if(!file_put_contents($configFile, $content))
		die('Impossible de modifier le fichier: '.$configFile);	
		
	$htaccessInstall = '.htaccess.install';
	
	$rewriteBase =  str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
	
	$content = @file_get_contents($htaccessInstall);
	if($content === false)
		die('Impossible de lire le fichier: '.$htaccessInstall);
		
	$content = str_replace('/myAPIFox/', $rewriteBase, $content);
	
	if(!file_put_contents($htaccessInstall, $content))
		die('Impossible de modifier le fichier: '.$htaccessInstall);
	
	if(!rename($htaccessInstall, '.htaccess'))
		die('Impossible de renommer le fichier: '.$htaccessInstall);			
		
		
	if(!unlink('install.php'))
		die('Impossible de modifier le fichier: install.php');	

	header('Location: index.php');
?>
