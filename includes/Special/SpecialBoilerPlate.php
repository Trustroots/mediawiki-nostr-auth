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
		$wikitext = 'Hello world!';
		$output->addWikiTextAsInterface( $wikitext );
	}
}