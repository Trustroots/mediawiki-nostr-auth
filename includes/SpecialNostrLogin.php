<?php
namespace MediaWiki\Extension\NostrLogin;

use MediaWiki\SpecialPage\SpecialPage;


class SpecialNostrLogin extends SpecialPage
{
	
	public function __construct()
	{
		parent::__construct('NostrLogin');
		$this->config = new Config();
	}

	public function execute($par)
	{
		file_get_contents("https://www.trustroots.org/.well-known/nostr.json?name=tillwenke");
		$request = $this->getRequest();
		$output = $this->getOutput();
		$this->setHeaders();

		$NostrLoginDomains = $this->config->getDomains();
		
		ob_start();
		include "content.php";
		$html = ob_get_clean();		

		// Add the HTML to the output
		$output->addHTML($html);
	}
}
