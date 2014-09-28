<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * wrapper myFox
 * 
 * @author Johan V.	www.hotfirenet.Com	twitter.com/hotfirenet
 *
 */
class myFox 
{	
	private $debug = false;
	
	private $clientId = '';
	private $clientSecret = '';
	
	private $username= '';
	private $password= '';
		
	private $authenticationUrl = 'https://api.myfox.me/oauth2/token';
	
	private $access_token = '';
	private $refresh_token = '';
	
	private $listClientSiteUrl = 'https://api.myfox.me:443/v2/client/site/items?access_token=%s';
	private $getSecurityUrl = 'https://api.myfox.me:443/v2/site/%d/security?access_token=%s';
	private $setSecurityUrl = 'https://api.myfox.me:443/v2/site/%d/security/set/%s';
	private $listScenarioUrl = 'https://api.myfox.me:443/v2/site/%d/scenario/items/?access_token=%s';
	private $scenarioUrl = 'https://api.myfox.me:443/v2/site/%d/scenario/%d/%s';
	
	
	public function __construct($clientId, $clientSecret, $username, $password)
	{	
		$this->clientId = (string) $clientId;
		$this->clientSecret = (string) $clientSecret;	
		$this->username = (string) $username;
		$this->password = (string) $password;
	}
	
	public function gate($siteId, $deviceId, $perform, $access_token = '')
	{
		$siteId = (int) $siteId;
		$perform = (int) $perform;
		$perform = (string) $perform;
		$access_token = (string) $access_token;	
		
		$url = sprintf($this->gateUrl, $siteId, $deviceId, $perform);

		$form = http_build_query(
			array(
				'access_token' => $access_token
			)
		);
	
		$result = $this->makeRequestMyfox($url,'POST', $form);		
		return $result;				
	}
	
	public function listGate($siteId, $access_token = '')
	{
		$siteId = (int) $siteId;	
		$access_token = (string) $access_token;
		
		return $this->listItem($this->listGateUrl, $siteId, $access_token);	
	}	
	
	public function heater($siteId, $deviceId, $action, $access_token = '')
	{
		$siteId = (int) $siteId;
		$deviceId = (int) $deviceId;
		$action = (string) $action;
		$access_token = (string) $access_token;	

		$url = sprintf($this->heaterUrl, $siteId, $deviceId, $action);

		$form = http_build_query(
			array(
				'access_token' => $access_token
			)
		);
	
		$result = $this->makeRequestMyfox($url,'POST', $form);		
		return $result;				
	}
	
	public function listHeater($siteId, $access_token = '')
	{
		$siteId = (int) $siteId;	
		$access_token = (string) $access_token;
		
		return $this->listItem($this->listHeaterUrl, $siteId, $access_token);	
	}		
	
	public function shutter($siteId, $deviceId, $perform, $access_token = '')
	{
		$siteId = (int) $siteId;
		$deviceId = (int) $deviceId;
		$action = (string) $action;
		$access_token = (string) $access_token;	

		$url = sprintf($this->shutterUrl, $siteId, $deviceId, $action);

		$form = http_build_query(
			array(
				'access_token' => $access_token
			)
		);
	
		$result = $this->makeRequestMyfox($url,'POST', $form);		
		return $result;				
	}
	
	public function listShutter($siteId, $access_token = '')
	{
		$siteId = (int) $siteId;	
		$access_token = (string) $access_token;
		
		return $this->listItem($this->listShutterUrl, $siteId, $access_token);	
	}		
	
	public function socket($siteId, $deviceId, $perform, $access_token = '')
	{
		$siteId = (int) $siteId;
		$deviceId = (int) $deviceId;
		$action = (string) $action;
		$access_token = (string) $access_token;		
		
		$url = sprintf($this->socketUrl, $siteId, $deviceId, $action);

		$form = http_build_query(
			array(
				'access_token' => $access_token
			)
		);
	
		$result = $this->makeRequestMyfox($url,'POST', $form);		
		return $result;				
	}
	
	public function listSocket($siteId, $access_token = '')
	{
		$siteId = (int) $siteId;	
		$access_token = (string) $access_token;
		
		return $this->listItem($this->listSocketUrl, $siteId, $access_token);	
	}	
	
	public function history($siteId, $access_token = '')
	{
		$siteId = (int) $siteId;	
		$access_token = (string) $access_token;
		
		$url = sprintf($this->getSecurityUrl, $siteId, $access_token);
		$result = $this->makeRequestMyfox($url,'GET', '');		
		return $result;			
	}	
	
	public function scenario($siteId, $scenarioId, $action, $access_token = '')
	{
		$siteId = (int) $siteId;
		$scenarioId = (int) $scenarioId;
		$action = (string) $action;
		$access_token = (string) $access_token;

		$url = sprintf($this->scenarioUrl, $siteId, $scenarioId, $action);

		$form = http_build_query(
			array(
				'access_token' => $access_token
			)
		);
	
		$result = $this->makeRequestMyfox($url,'POST', $form);		
		return $result;		
	}
	
	public function listScenario($siteId, $access_token = '')
	{
		$siteId = (int) $siteId;	
		$access_token = (string) $access_token;
	
		return $this->listItem($this->listScenarioUrl, $siteId, $access_token);		
	}

	public function setSecurity($siteId, $securityLevel, $access_token = '')
	{
		$siteId = (int) $siteId;
		$securityLevel = (string) $securityLevel;
		$access_token = (string) $access_token;

		$url = sprintf($this->setSecurityUrl, $siteId, $securityLevel);

		$form = http_build_query(
			array(
				'access_token' => $access_token
			)
		);
	
		$result = $this->makeRequestMyfox($url,'POST', $form);		
		return $result;		
	} 

	public function getSecurity($siteId, $access_token = '')
	{
		$siteId = (int) $siteId;	
		$access_token = (string) $access_token;
		
		$url = sprintf($this->getSecurityUrl, $siteId, $access_token);
		$result = $this->makeRequestMyfox($url,'GET', '');		
		return $result;			
	}
	
	public function listClientSite($access_token = '')
	{
		$access_token = (string) $access_token;
		
		$url = sprintf($this->listClientSiteUrl, $access_token);
		
		$result = $this->makeRequestMyfox($url,'GET', '');		
		return $result;		
	}
	
	public function authentication($refresh_token = '')
	{
		$refresh_token = (string) $refresh_token;
		
		if($refresh_token)
		{
			$form = http_build_query(
				array(
					'client_id' => $this->clientId,
					'client_secret' => $this->clientSecret,
					'refresh_token' => $this->refresh_token,
					'grant_type' => 'refresh_token'
				)
			);			
		}
		else
		{
			$form = http_build_query(
				array(
					'client_id' => $this->clientId,
					'client_secret' => $this->clientSecret,
					'username' => $this->username,
					'password' => $this->password,
					'grant_type' => 'password'
				)		
			);		
		}
		
		$result = $this->makeRequestMyfox($this->authenticationUrl,'POST', $form);
		
		return $result;
	}
	
	private function listItem($siteId, $access_token, $url)
	{		
		$url = sprintf($url, $siteId, $access_token);
		$result = $this->makeRequestMyfox($url,'GET', '');		
		return $result;			
	}
	
	private function watchDog($message) 
	{
		$dateTime = date('d-m-Y H:i:s',time());		
		$dateFile = sprintf('%10.10s',$dateTime);
		
		$logFile = $dateFile.'.log';
		$fp = fopen($logFile, 'a');
		flock($fp, 2);
		fseek($fp, filesize($logFile));
		$ibid = sprintf("%-10s %-60s\r\n",$dateTime,$message);
		fwrite($fp, $ibid);
		flock($fp, 3);
		fclose($fp);
	}	
	
	private function makeRequestMyfox($url, $method, $content)
	{
		$opts = array('http' =>
						array(
							'method'  => $method,
							'header'  => ($method == 'POST') ? 'Content-type: application/x-www-form-urlencoded' : '',
							'content' => $content
						)
					 );
		
		$context  = @stream_context_create($opts);
		$result = @file_get_contents($url, false, $context);
		
		if($result === false)
			$this->watchDog($url ."\n\r" . $content);		
		
		return $result;
	}
}
?>
