<?php
// PHP script
// do_enter.php
//
// Post process entering host ip addr and port for samplicator
// interface to sta-samp.sh
// input: comment, ip_addr, port
// output: entries.json (file to record entries.)
// 2015-04-05 hn original
//
// input date to record.
//       ip addr for the reciever
//       port number
    $file = 'entries.json';
    $logfile = 'log.json';
    $sdate = `date +%F_%H:%M:%S`;
    // check if a form was submitted
  if( !empty( $_POST ) ){
    // convert form data to json format
      $postArray = array( 
        "date" => $sdate,
	"comment" => $_POST['comment'],
	"host" => array(
	    "addr" => $_POST['addr'],
	    "port" => $_POST['port']
	)
      ); //you might need to process any other post fields you have..

    $json = json_encode( $postArray );
    // make sure there were no problems
    if( json_last_error() != JSON_ERROR_NONE ){
      echo 'json error.';
      exit;  // do your error handling here instead of exiting
    }
  } else {
    // No entry data, get them from file.
    $json=file_get_contents($file);
    if($json == FALSE) {
	//fail to retrieve file.
	echo "cannot read " . $file;
	exit;
    }
  }
    // to display what's input
    echo 'input success:', $json;
    print "<br />";
    $decoded=json_decode($json);
    $ostring=exec("./sta-samp.sh " . $decoded->host->addr . " " . $decoded->host->port ) ;
    var_dump($ostring);
    // write to file
    //   note: _server_ path, NOT "web address (url)"!
    file_put_contents( $file, $json );
    file_put_contents( $logfile, $json, FILE_APPEND );
?>

<a href="index.html"> Go Back </a>

