<?php
namespace MediaWiki\Extension\BoilerPlate\Special;

use MediaWiki\SpecialPage\SpecialPage;


class SpecialBoilerPlate extends SpecialPage
{
	
	public function __construct()
	{
		parent::__construct('BoilerPlate');
	}

	public function execute($par)
	{
		$request = $this->getRequest();
		$output = $this->getOutput();
		$this->setHeaders();

		if (!$this->getUser()->isAllowed('createaccount')) {
			throw new PermissionsError('createaccount');
		}

		// Add a basic button
		$html = file_get_contents(__DIR__ . '/' . 'login.html');

		// Add the HTML to the output
		$output->addHTML($html);
	}
}
