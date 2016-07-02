<?php
// Curl test url:
//
//  curl -v -X POST -d 'email=test@example.com&voornaam=firstname&achternaam=lastname' https://server.com/wp-content/themes/ppnl/subscribe_newsletter.php
//

require_once('subscribe_newsletter.settings.php');

$lAccess = !empty($gValidIPs) && is_array($gValidIPs) && in_array($_SERVER['REMOTE_ADDR'],$gValidIPs);

if (!$lAccess) {
	header($_SERVER["SERVER_PROTOCOL"]." 403 Not Authorized");
	exit;
}

function getDatabaseConnection() {
	$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	if (mysqli_connect_errno()) {
    	printf("Connect failed: %s\n", mysqli_connect_error());
    	exit();
	}

	return $connection;
}

function checkSubscriberExists($pEmail) {
	$lDB = getDatabaseConnection();
	if ($stmt = $lDB->prepare('SELECT email FROM wp_newsletter WHERE email = ?')) {
		$stmt->bind_param('s', $pEmail);
		if (!$stmt->execute()) {
    			echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}
		$stmt->store_result();
		return $stmt->num_rows == 1;
	}
}

function addSubscriber($pEmail, $pFirstname, $pLastname) {
	if (checkSubscriberExists($pEmail)) return; // User already exists

	$pEmail = trim($pEmail);
	$pFirstname = trim($pFirstname);
	$pLastname = trim($pLastname);
	$lSex = "n";
	$lStatus = "C";
	$lReferrer = "lidworden";
	$lHttpReferrer = "https://lidworden.piratenpartij.nl";

	$lToken = substr(uniqid(),0,10);
	$lIP = $_SERVER['REMOTE_ADDR'];
	$lDB = getDatabaseConnection();

	if ($stmt = $lDB->prepare('INSERT INTO wp_newsletter (email, name, surname, sex, status, token,  referrer, http_referer, ip) VALUES (?,?,?,?,?,?,?,?,?)')) {
		$stmt->bind_param('sssssssss', $pEmail,$pFirstname,$pLastname,$lSex,$lStatus,$lToken,$lReferrer,$lHttpReferrer,$lIP);
		if (!$stmt->execute()) {
 	   		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}
	} else {
		echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	echo "OK";
}

if ($lAccess && $_POST['email'] != '') {
	addSubscriber($_POST['email'],$_POST['voornaam'],$_POST['achternaam']);
}
?>
