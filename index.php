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

?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="/favicon.ico">

  <title>Documentation - Simple-API</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
</head>

<body>

  <div  class="container">
    <div class="row">
      <div class="col-12">
        <h1><a href="https://github.com/cjtrowbridge/Simple-API" target="_blank">Simple-API</a></h1>
        <div class="card">
          <div class="card-body">
            <p>Here are some endpoints which are available. Some of these may be disabled depending on configuration...</p>
            <ul>
              <li>
                <h4>What Is My IP?</h4>
                <p><a href="/?endpoint=whatismyip" target="_blank">Click here</a> to find out your ip.</p>
              </li>
              <li>
                <h4>Who Is?</h4>
                <p><a href="javasrcipt:void(0);" onclick="WhoIs();">Click Here</a> to run a whois lookup from the server.</p>
              </li>
              <li>
                <h4>Ping</h4>
                <p><a href="javasrcipt:void(0);" onclick="Ping();">Click Here</a> to run a ping from the server.</p>
              </li>
            </ul>
          </div><!--/card-body-->
        </div><!--/card-->
        <div id="output"></div>
      </div><!--/col-12-->
    </div><!--/row-->
  </div><!--/container-->
  
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
  
  <script>
    function WhoIs(){
      var fqdn = prompt("Enter an FQDN to run a Whois lookup from the server.","google.com");
      if (fqdn == null || fqdn == ""){
        //User canceled the prompt. Do nothing.
      }else{
        var uri="./?endpoint=whois&fqdn="+fqdn;
        AddResultCard(uri);
      }
    }
    function Ping(){
      var fqdn = prompt("Enter an FQDN to run a ping from the server.", "google.com");
      if (fqdn == null || fqdn == ""){
        //User canceled the prompt. Do nothing.
      }else{
        var uri = "./?endpoint=whois&fqdn="+fqdn;
        AddResultCard(uri);
      }
    }
    function AddResultCard(uri){
      var ID = MakeID();
      $('#output').append(`
        <div class="card mt-10">
          <div class="card-body">
            <h4><a href="`+uri+`">`+uri+`</a></h4>
            <pre id="results_`+ID+`">Fetching...</pre>
          </div><!--/card-body-->
        </div><!--/card-->
      `);
      $.get(uri,function(data){
        $("#result_"+ID).html(data);
      });
    }
    function MakeID(){
      var text = "";
      var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
      for (var i = 0; i < 5; i++)
       text += possible.charAt(Math.floor(Math.random() * possible.length));
      return text;
    }
  </script>
</body>
</html>
