<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Wrapper PHP myFox
 * 
 * @author Johan V.	www.hotfirenet.Com	twitter.com/hotfirenet
 *
 */

	$clientId = '';
	$clientSecret = '';
	$username = '';
	$password = '';	
	
	$siteId = '';
	
//A MODIFIER
	$security_key = 'CLEDESECURITE'; //Clé de sécurité
	$email = 'EMAIL'; //Email expéditeur
	$recipient = 'EMAIL'; //Email destinataire
	
//NE PAS TOUCHER
	// Récupération ip
	$ip = $_SERVER['REMOTE_ADDR'];
	$url = $_SERVER['REQUEST_URI'];
	
//MODIFIABLE
	//Configuration email
	$Name = 'Script Api MyFox'; //Expéditeur
	$subject = 'Alerte utilisation scipt'; //Sujet
	$header = 'From: '. $Name . ' <' . $email . ">\r\n"; //Header email 
	$mail_body = 'L\'adresse ip : '.$ip.' a tenté d\'accèder au script MyFox avec l\'url : '.$url; //mail body		
?>