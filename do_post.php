<?php
// PHP script
// Post process entering host ip addr and port for samplicator
// interface to sta-samp.sh
// input: comment, ip_addr, port
// output: entries.json (file to record entries.)
// 2015-04-05 hn original
//
// input date to record.
//       ip addr for the reciever
//       port number
    $headertext = '
<html>
<head>
<title>Welcome to stream-reflex!</title>
<style>
    body {
        width: 35em;
        margin: 0 auto;
        font-family: Tahoma, Verdana, Arial, sans-serif;
    }
</style>
</head>
<body>
<br />
<h1>Welcome to stream-reflex!</h1>
<p>This page is to manage Stream-Reflex input parameter- 
to test.</p>
<form name="postform" action="do_enter.php" method="post" enctype="multipart/form-data">
<table class="postarea" id="postarea">
    <tbody>
<tr>
';
  $bodytext = '
  <td class="postblock">Comment:</td><td><input type="text" name="comment"></td>
  <td class="assetblock">receive address1:</td><td><input type="text" name="addr"></td>
  <td class="assetblock">receive port1:</td><td><input type="text" name="port"></td>
  <td></td>
';

  $tailtext = '
</tr>
<tr>
  <td class="postblock"></td><td> <input type="submit" value="Submit Entry"> </td>    
</tr>
</tbody>
</table>
</form>
</body>
</html>
';

$file="entries.json";

if(!isset($_GET['action'])) {
    // id index does not exist
    echo 'not input specified.';
} else if ($_GET['action'] === "enter") {
  $ostring=exec("./dsp-samp.sh ", $retArr, $retVal) ;
  if($retVal == 1) {
	echo "stream-reflex is running!. cannot run two instances.<br />";
	exit;
  }
  $lockfile = 'entry.lock';
  $islocked = 0;
  if (file_exists($lockfile)) {
    echo "WARNING: Entry is locaked. You can only Start reflex!";
    $json=(file_get_contents($file));
    if($json == FALSE) {
	// fail to retrieve a file.
	echo "cannot read " . $file . ". Destination data must be entered first.";
	exit;
    }
    $decoded = json_decode($json);
    echo $headertext .  
"Reflex Destination: <br />" . $decoded->comment . " " .
$decoded->host->addr . ":" . $decoded->host->port . 
"<br />" . $tailtext;
  } else {
    echo $headertext . $bodytext . $tailtext;
  }
} else if ($_GET['action'] === "display") {
    $ostring=exec("./dsp-samp.sh ", $retArr, $retVal) ;
    if($retVal == 1) {
	echo "stream-reflex is running!.<br />";
        $json=(file_get_contents($file));
        if($json == FALSE) {
	  // fail to retrieve a file.
	  echo "cannot read " . $file . ". Destination data must be entered first.";
	  exit;
        }
        $decoded = json_decode($json);
	echo 
"Reflex Destination: <br />" . $decoded->date . " " . $decoded->comment . " " .
$decoded->host->addr . ":" . $decoded->host->port . 
"<br />";
        // var_dump($json);
    } else {
	echo "stream-reflex is not running!.<br />" . $ostring . "<br />";
    }
} else if ($_GET['action'] === "stop") {
    $ostring=exec("./sto-samp.sh ", $retArr, $retVal) ;
    if($retVal == 1) {
	echo "stream-reflex stopped.<br />";
    } else {
	echo "nothing to stop.<br />";
    }

} else {
    echo 'wrong entry!';
}  
?>
<a href="index.html"> Go Back </a>

