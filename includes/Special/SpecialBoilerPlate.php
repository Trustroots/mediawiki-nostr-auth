<?php
namespace MediaWiki\Extension\BoilerPlate\Special;

use MediaWiki\SpecialPage\SpecialPage;
class SpecialBoilerPlate extends SpecialPage {
	public function __construct() {
		parent::__construct( 'BoilerPlate' );
	}

	public function execute( $par ) {
		$request = $this->getRequest();
		$output = $this->getOutput();
		$this->setHeaders();

		# Get request data from, e.g.
		$param = $request->getText( 'param' );

		# Do stuff
		# ...
		$wikitext = 'Hello '. $par;
		$output->addWikiTextAsInterface( $wikitext );
		// Add a basic form with a button
		$html = '<form method="post" action="' . htmlspecialchars($this->getPageTitle()->getLocalURL()) . '">';
		$html .= '<input type="hidden" name="action" value="submit_form">';
		$html .= '<input type="submit" value="Nostr Extension Login" class="mw-ui-button mw-ui-progressive">';
		$html .= '</form>';
		$html .= '<button onclick="printNostr()">Print window.nostr</button>';
		$html .= '<script>
			function printNostr() {
				const publicKey = window.nostr.getPublicKey();
				console.log(publicKey);
				// Send the public key to the server using an AJAX request
				const xhr = new XMLHttpRequest();
				xhr.open("POST", "' . htmlspecialchars($this->getPageTitle()->getLocalURL()) . '", true);
				xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				xhr.onreadystatechange = function () {
					if (xhr.readyState === 4 && xhr.status === 200) {
						const response = JSON.parse(xhr.responseText);
						document.getElementById("publicKeyDisplay").innerText = response.public_key;
					}
				};
				xhr.send("action=store_public_key&public_key=" + encodeURIComponent(publicKey));
			}
		</script>';
		$html .= '<div id="publicKeyDisplay"></div>';

		// Add the HTML to the output
		$output->addHTML($html);

		// Optionally, check if the form is submitted
		$this->handleFormSubmission();

		// Handle AJAX request to store public key
		$this->handleAjaxRequest();
	}

	// Handle form submission
	private function handleFormSubmission() {
		// Check if the form has been submitted
		$request = $this->getRequest();
		if ($request->getVal('action') === 'submit_form') {
			// Perform some action upon form submission
			$output = $this->getOutput();
			$output->addWikiTextAsContent("'''Form Submitted!'''");
		}
	}

	// Handle AJAX request to store public key
	private function handleAjaxRequest() {
		$request = $this->getRequest();
		if ($request->wasPosted() && $request->getVal('action') === 'store_public_key') {
			$publicKey = $request->getVal('public_key');
			// Store the public key in a PHP variable
			$GLOBALS['publicKey'] = $publicKey;
			// Return the public key as a JSON response
			header('Content-Type: application/json');
			echo json_encode(['public_key' => $publicKey]);
			exit;
		}
	}
}
