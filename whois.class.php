<?php
/*
 * 
 * @author     Roozbeh Baabakaan
 * @Version: 1.0
 * @Date: 2013-01-9
 *
*/

class domainCheck{

    var $serverList;
    var $tr = 0;
    
function domainCheck(){   
    $this->serverList[0]['top']      = 'com';
	$this->serverList[0]['server']   = 'whois.verisign-grs.com';
	$this->serverList[0]['response'] = 'No match for';
	$this->serverList[0]['check']    = true;
	
	$this->serverList[1]['top']      = 'net';
	$this->serverList[1]['server']   = 'whois.verisign-grs.com';
	$this->serverList[1]['response'] = 'No match for';
	$this->serverList[1]['check']    = true;

	$this->serverList[2]['top']      = 'org';
	$this->serverList[2]['server']   = 'whois.pir.org';
	$this->serverList[2]['response'] = 'NOT FOUND';
	$this->serverList[2]['check']    = true;
	
	$this->serverList[3]['top']      = 'info';
	$this->serverList[3]['server']   = 'whois.afilias.info';
	$this->serverList[3]['response'] = 'NOT FOUND';
	$this->serverList[3]['check']    = true;
	
	$this->serverList[4]['top']      = 'name';
	$this->serverList[4]['server']   = 'whois.nic.name';
	$this->serverList[4]['response'] = 'No match';
	$this->serverList[4]['check']    = true;
	
	$this->serverList[5]['top']      = 'us';
	$this->serverList[5]['server']   = 'whois.nic.us';
	$this->serverList[5]['response'] = 'Not found:';
	$this->serverList[5]['check']    = true;

	$this->serverList[6]['top']      = 'biz';
	$this->serverList[6]['server']   = 'whois.nic.biz';
	$this->serverList[6]['response'] = 'Not found';
	$this->serverList[6]['check']    = true;
	
	$this->serverList[7]['top']      = 'ca';
	$this->serverList[7]['server']   = 'whois.cira.ca';
	$this->serverList[7]['response'] = 'AVAIL';
	$this->serverList[7]['check']    = true;
	
	$this->serverList[8]['top']      = 'ir';
	$this->serverList[8]['server']   = 'whois.nic.ir';
	$this->serverList[8]['response'] = 'No entries found';
	$this->serverList[8]['check']    = true;
}


function processWhois(){
  

    if (!isset($_GET['submitBtn'])){
        
    } else {

        $domainName = (isset($_GET['domain'])) ? trim($_GET['domain']) : '';
        
       	for ($i = 0; $i < sizeof($this->serverList); $i++) {
       		$actTop = "top_".$this->serverList[$i]['top'];
       		$this->serverList[$i]['check'] = isset($_GET[$actTop]) ? true : false;
       	}

        // Check domains only if the base name is big enough
        if (strlen($domainName)>2){
            echo '<div>';
            echo '<span>search result</span>';
		
           	for ($i = 0; $i < sizeof($this->serverList); $i++) {
	       		if ($this->serverList[$i]['check']){
			     	$this->showDomainResult($domainName.".".$this->serverList[$i]['top'],
			     	                        $this->serverList[$i]['server'],
			     	                        $this->serverList[$i]['response']);
			    }
		    }
        
		    echo '</div>';
        }
      
        
    }
 

}

function showDomainResult($domain,$server,$findText){
   if ($this->tr == 0){
       $this->tr = 1;
       $class = " class='tr2'";
   } else {
       $this->tr = 0;
       $class = "";
   }
   if ($this->checkDomain(trim($domain),trim($server),trim($findText))){
      echo "<div $class><span>$domain</span><span class='ava' style='color: green;'> available </span></div>";
   }
   else echo "<div $class><span>$domain</span><span class='tak' style='color: red;'> registerd </span></div>";
}

function checkDomain($domain,$server,$findText){
    $con = fsockopen($server, 43);
    if (!$con) return false;
        
    // Send the requested domain name
    fputs($con, $domain."\r\n");
        
    // Read and store the server response
    $response = ' :';
    while(!feof($con)) {
        $response .= fgets($con,128); 
    }
        
    // Close the connection
    fclose($con);
        
    // Check the response stream whether the domain is available
    if (strpos($response, $findText)){
        return true;
    }
    else {
        return false;   
    }
}

}
?>