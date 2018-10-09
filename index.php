<?php

/*

  CJTrowbridge.com
  2018-10-09

  Simple-API

  The purpose of this project is to illustrate a simple API deployment which provides
  some basic, common server-side functionality while being easy to self-host.

*/

switch($_REQUEST['endpoint']){
  case 'whatismyip':
    /*
      /?endpoint=whatismyip
      Returns your public ip. Add the flag "&json" to get it in JSON format.
    */
    $IP = $_SERVER['REMOTE_ADDR'];
    if(isset($_GET['json'])){
      header('Content-Type: application/json');
      echo json_encode(array('whatismyip'=>$IP));
    }else{
      echo $IP;
    }
    exit;
  case 'whois':
    /*
      /?endpoint=whois&fqdn=google.com
      Checks to see if the fqdn is valid, per https://stackoverflow.com/questions/3026957/how-to-validate-a-domain-name-using-regex-php#answer-16491074
      Then runs a whois and returns the results.
    */
    if(ValidateFQDN($_GET['fqdn'])){
      $FQDN = escapeshellarg($_GET['fqdn']);
      $Command="whois '$FQDN' ";
      echo shell_exec($Command);
      exit;
    }else{
var_dump(ValidateFQDN($_GET['fqdn']));
      die('Make sure you have passed a valid FQDN into the FQDN attribute. For example: /?endpoint=whois&fqdn=google.com');
    }
  case 'ping':
    /*
      /?endpoint=whois&fqdn=google.com
      Checks to see if the fqdn is valid, per https://stackoverflow.com/questions/3026957/how-to-validate-a-domain-name-using-regex-php#answer-16491074
      Then pings the fqdn and returns the results.
    */
    if(ValidateFQDN($_GET['fqdn'])){
      $FQDN = escapeshellarg($_GET['fqdn']);
      $Command= "ping -c 1 '$FQDN'";
      echo shell_exec($Command);
      exit;
    }else{
      die('Make sure you have passed a valid FQDN into the FQDN attribute. For example: /?endpoint=whois&fqdn=google.com');
    }
}

function ValidateFQDN($FQDN){
  if(filter_var('http://'.$FQDN, FILTER_VALIDATE_URL)){
    return true;
  }else{
    if(filter_var($FQDN, FILTER_VALIDATE_URL)){
      return true;
    }else{
      return false;
    }
  }
}
