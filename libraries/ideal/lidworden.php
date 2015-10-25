<?php
	/*
		Donatiescript
		Ontwikkeld voor het ontvangen van donaties m.b.v. iDEAL Lite (Rabobank) of iDEAL Basic (ING Bank).

		Auteur:     Martijn Wieringa
		Email:      info@php-solutions.nl
		Website:    http://www.php-solutions.nl
		Licentie:   http://creativecommons.org/licenses/by/3.0/nl/
	*/

	include(dirname(__FILE__) . '/library/ideallite.cls.php');

	$ideal_html = '';

	if(empty($_POST['amount'])) {

		// No amount if this is not a result-request, then show the form.
		if(empty($_GET['ideal']['status'])) {

			// Toon lidworden formulier
			$ideal_html .= '
				<div style="font-size: 20px;" >
					<form class="form-inline" action="/lid-worden/" method="post">
						<div class="input-group">
							<div class="input-append input-prepend">
								<div class="input-group">
									<div class="input-append input-prepend">
										<input id="inBedrag" class="input-small" name="amount" type="hidden" value="17,50">
										<input id="btnLidworden" class="btn btn-primary" type="submit" value="Verzenden (&euro;17,50)">
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div style="clear: both;" ></div>';
		} else {
			// Meld de status van de betaling
			$sPaymentId = (empty($_GET['ideal']['id']) ? '' : $_GET['ideal']['id']);
			$sPaymentCode = (empty($_GET['ideal']['code']) ? '' : $_GET['ideal']['code']);
			$sPaymentStatus = ((empty($_GET['ideal']['status']) || (in_array($_GET['ideal']['status'], array('success', 'cancel', 'error')) == false)) ? 'error' : $_GET['ideal']['status']);

			// Send confirmation to screen
			if(strcasecmp($sPaymentStatus, 'success') === 0) {
				//$html .= '<p>Uw betaling is met succes ontvangen.</p>';.
				header('Location: /lid-worden/ideal-succes/');
				die('<center><br/><br/>Je betaling is met succes ontvangen.<br/><a href="/lid-worden/ideal-succes/">Klik hier als je niet word doorgestuurd.</a></center>');
			} else {
				$ideal_html .= '
					<p class="bg-danger">Er is helaas een fout opgetreden bij het verwerken van je betaling.<br/><br/></p>';
			}
		}
	} else { // Toon iDEAL Lite formulier (met AUTO SUBMIT)
		$sPaymentId = date('YmdHis');
		$sPaymentCode = lidwordenRandomCode(32);
		$fPaymentAmount = floatval(17.50);

		if($fPaymentAmount >= 1) { // Minimaal 1 euro
			// Save order in folder
			$aPaymentData = array('id' => $sPaymentId, 'code' => $sPaymentCode, 'amount' => $fPaymentAmount, 'date' => date('Y-m-d'), 'time' => date('H:i:s'), 'ip' => $_SERVER['REMOTE_ADDR'], 'status' => 'open');
			//lidwordenWriteToFile($sPaymentFile, serialize($aPaymentData));

			$oIdeal = new IdealLite();

    $oIdeal->setMerchant('002096509');
    $oIdeal->setHashKey('g9G63xXiI48Lfc2W');
    $oIdeal->setAquirer('rabo');

			// Bepaal de URL waar de bezoeker naar toe wordt gestuurd nadat de ideal betaling is afgerond (of bij fouten)
			$sCurrentUrl = strtolower(substr($_SERVER['SERVER_PROTOCOL'], 0, strpos($_SERVER['SERVER_PROTOCOL'], '/')) . '://' . $_SERVER['SERVER_NAME'] . '/') . substr($_SERVER['SCRIPT_NAME'], 1);
			$sReturnUrl = substr($sCurrentUrl, 0, strrpos($sCurrentUrl, '/') + 1);

			$oIdeal->setUrlCancel($sReturnUrl . '?ideal[id]=' . $sPaymentId . '&ideal[code]=' . $sPaymentCode . '&ideal[status]=cancel');
			$oIdeal->setUrlError($sReturnUrl . '?ideal[id]=' . $sPaymentId . '&ideal[code]=' . $sPaymentCode . '&ideal[status]=error');
			$oIdeal->setUrlSuccess($sReturnUrl . '?ideal[id]=' . $sPaymentId . '&ideal[code]=' . $sPaymentCode . '&ideal[status]=success');

			// Set order details
			$oIdeal->setAmount($fPaymentAmount); // Bedrag (in EURO's)
			$oIdeal->setOrderId($sPaymentId); // Unieke order referentie (tot 16 karakters)
			$oIdeal->setOrderDescription('iDEAL betaling contributie'); // Order omschrijving (tot 32 karakters)

			// Customize submit button
			$oIdeal->setButton('Verder >>');

			$ideal_html .= '<div class="wrapper"><p><img alt="iDEAL" border="0" src="'.static_url().'vendor/ideal/images/ideal.gif"></p><p>Afrekenen via je eigen bank.</p>' . $oIdeal->createForm() . '</div>';

			if(IDEAL_TEST_MODE == false) {
				$ideal_html .= '<script type="text/javascript"> function doAutoSubmit() { document.getElementById(\'_ideal_form_\').submit(); } setTimeout(\'doAutoSubmit()\', 100); </script>';
			}
		} else {
			$ideal_html .= '<p>Vanwege de transactiekosten die iDeal rekent is het minimum bedrag 1 euro.<br><br><a href="https://piratenpartij.nl/lid-worden/">Terug</a></p>';
		}
	}

  function lidworden_shortcode_handler() {
    global $ideal_html;
    return lidworden_output($ideal_html);
  }

	function lidworden_output($html) {
		// Bouw deze HTML code naar eigen inzicht om zodat ze aansluit bij jou website
		return '<div id="ppnl-ideal">'.$html.'</div>';
	}

	// Read content from file
	function lidwordenReadFromFile($sPath) {
		return file_get_contents($sPath);
	}

	// Write content to file
	function lidwordenWriteToFile($sPath, $sContent, $bClearFile = false) {
		if(file_exists($sPath) == false) {
			// Create file
			touch($sPath);

			// When creating a new file, we update file mode 
			// to avoid access problems with other tools like FTP.
			chmod($sPath, 0777);
		}

		if($bClearFile) {
			// Override file contents
			file_put_contents($sPath, $sContent);
		} else {
			// Append content to file
			file_put_contents($sPath, $sContent, FILE_APPEND);
		}
	}

	// Create a random code with N digits.
	function lidwordenRandomCode($iLength = 64) {
		$aCharacters = array('a', 'b', 'c', 'd', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

		$sResult = '';

		for($i = 0; $i < $iLength; $i++) { // (62 ^ [$digits] mogelijke codes)
			$sResult .= $aCharacters[rand(0, sizeof($aCharacters) - 1)];
		}
		return $sResult;
	}
?>
