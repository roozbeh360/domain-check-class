<?php
/**
 * Simple class to query whois servers for domain availability.
 * 
 * @author Original: Roozbeh Baabakaan 
 * @author Latest: Lawrence Cherone <cherone.co.uk>
 * @Version: 1.1
 * 
 **/

class domainCheck{

	private $serverList;

	/**
	 * Class construct, here we set the available whois servers array
	 */
	function __construct()
	{
		$this->serverList[0]['top'] = 'com';
		$this->serverList[0]['server'] = 'whois.verisign-grs.com';
		$this->serverList[0]['response'] = 'No match for';
		$this->serverList[0]['check'] = true;

		$this->serverList[1]['top'] = 'net';
		$this->serverList[1]['server'] = 'whois.verisign-grs.com';
		$this->serverList[1]['response'] = 'No match for';
		$this->serverList[1]['check'] = true;

		$this->serverList[2]['top'] = 'org';
		$this->serverList[2]['server'] = 'whois.pir.org';
		$this->serverList[2]['response'] = 'NOT FOUND';
		$this->serverList[2]['check'] = true;

		$this->serverList[3]['top'] = 'info';
		$this->serverList[3]['server'] = 'whois.afilias.info';
		$this->serverList[3]['response'] = 'NOT FOUND';
		$this->serverList[3]['check'] = true;

		$this->serverList[4]['top'] = 'name';
		$this->serverList[4]['server'] = 'whois.nic.name';
		$this->serverList[4]['response'] = 'No match';
		$this->serverList[4]['check'] = true;

		$this->serverList[5]['top'] = 'ca';
		$this->serverList[5]['server'] = 'whois.cira.ca';
		$this->serverList[5]['response'] = 'AVAIL';
		$this->serverList[5]['check'] = true;

		$this->serverList[6]['top'] = 'ir';
		$this->serverList[6]['server'] = 'whois.nic.ir';
		$this->serverList[6]['response'] = 'No entries found';
		$this->serverList[6]['check'] = true;
	}
	
	/**
	 * The whois query processor method,
	 * 	this method will loop through the $this->serverList array and determin 
	 *  if the check should be made, after that it will pass the parramiters to
	 *  the checkDomain method and store and return the result.
	 *
	 * @param string $domain
	 * @param array $tlds
	 * @return array
	 */
	function processWhois($domain = "", $tlds = array())
	{
		// if no tlds array passed then just do all in the serverList array
		if(!empty($tlds)){
			for ($i=0; $i < sizeof($this->serverList); $i++) {
				if(in_array($this->serverList[$i]['top'], $tlds)){
					$this->serverList[$i]['check'] =  true;
				}else{
					$this->serverList[$i]['check'] =  false;
				}
			}
		}

		// check domains only if the base name is big enough
		$result[$domain] = array();
		if (strlen($domain) > 2){
			for ($i=0; $i < sizeof($this->serverList); $i++) {
				if ($this->serverList[$i]['check'] == true){
					//query whois and check domain
					if ($this->checkDomain(trim($domain).".",
										   $this->serverList[$i]['server'],
										   $this->serverList[$i]['response'])){
						// domain is available
						$result[$domain][$this->serverList[$i]['top']] = '<span style="color:green">available</span>';
					}else{
						// domain is registered
						$result[$domain][$this->serverList[$i]['top']] = '<span style="color:red">registered</span>';
					}
				}
			}
			return $result;
		}
	}

	/**
	 * Socket connection to whois server.
	 * 	Returns bool (true|false)
	 *
	 * @param string $domain
	 * @param string $server
	 * @param string $findText
	 * @return bool
	 */
	function checkDomain($domain, $server, $findText)
	{
		// open socket to whois server
		$con = @fsockopen($server, 43);
		if (!$con) return false;

		// send the requested domain name
		fputs($con, $domain."\r\n");
		
		// read and store the server response
		$response = ' :';
		while(!feof($con)) {
			$response .= fgets($con,128);
		}
		
		// close the connection
		fclose($con);
		// check the response stream whether the domain is available
		return strpos($response, $findText) ? true : false;
	}
}
?>