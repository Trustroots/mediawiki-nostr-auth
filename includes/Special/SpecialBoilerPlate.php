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
		$html .= '<script>function printNostr() { console.log(window.nostr.getPublicKey());}</script>';

		// Add the HTML to the output
		$output->addHTML($html);

		// Optionally, check if the form is submitted
		$this->handleFormSubmission();
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
}