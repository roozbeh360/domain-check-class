<?php 
// the domain name we want to check, this could be populated via a form or such
$domain = 'google';

// include the class
include 'whois.class.php';

// initialize the domain check class
$whois = new domainCheck();

// process only com, net, org tlds
// $result = $whois->processWhois($domain, array('com', 'net', 'org'));

// process all available tlds
$result = $whois->processWhois($domain);

/*
$result Array
(
    [google] => Array
        (
            [com] => <span style="color:red">registered</span>
            [net] => <span style="color:red">registered</span>
            [org] => <span style="color:red">registered</span>
            [info] => <span style="color:red">registered</span>
            [name] => <span style="color:red">registered</span>
            [ca] => <span style="color:red">registered</span>
            [ir] => <span style="color:green">available</span>
        )

)
*/

// display - loop through the result and echo
foreach($result as $domain => $tlds){
	echo '<h1>Whois Result for '.$domain.'</h1>';
	foreach($tlds as $tld => $status){
		echo '<span><a href="http://'.$domain.'.'.$tld.'">http://'.$domain.'.'.$tld.'</a> is '.$status.'</span><br>';
	}
}
?>