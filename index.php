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
}
