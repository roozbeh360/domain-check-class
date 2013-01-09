domain-check-class
==================

 easy check domain by simple whois base class.

useage:
 
 require_once("whois.class.php"); 
 $whois = new domainCheck();
 //?submitBtn=on&domain=standi&top_com=true&top_ir=true
 $whois->processWhois();