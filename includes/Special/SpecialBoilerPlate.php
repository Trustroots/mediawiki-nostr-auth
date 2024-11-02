<?php
namespace MediaWiki\Extension\BoilerPlate\Special;

use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\MediaWikiServices;


class SpecialBoilerPlate extends SpecialPage
{
	private static $userFactory;
	

	public function __construct()
	{
		parent::__construct('BoilerPlate');
		self::$userFactory = MediaWikiServices::getInstance()->getUserFactory();
	}

	public function execute($par)
	{
		$request = $this->getRequest();
		$output = $this->getOutput();
		$this->setHeaders();

		if (!$this->getUser()->isAllowed('createaccount')) {
			throw new PermissionsError('createaccount');
		}

		# Get request data from, e.g.
		$param = $request->getText('param');

		# Do stuff
		# ...
		$wikitext = 'Hello ' . $par;
		$output->addWikiTextAsInterface($wikitext);
		// Add a basic form with a button
		$html = '<form method="post" action="' . htmlspecialchars($this->getPageTitle()->getLocalURL()) . '">';
		$html .= file_get_contents('/var/lib/mediawiki/extensions/mediawiki-nostr-auth/includes/Special/login.html');
		;

		// Add the HTML to the output
		$output->addHTML($html);

		// Optionally, check if the form is submitted
		$this->handleFormSubmission();
	}

	// Handle form submission
	private function handleFormSubmission()
	{
		// Check if the form has been submitted
		$request = $this->getRequest();
		$output = $this->getOutput();
		if ($request->getVal('action') === 'submit_form') {
			$username = $request->getVal('username');
			$password = $request->getVal('password');


			$user = self::$userFactory->newFromName($username);

			$output->addWikiTextAsContent("'''Creating account...'''" . $user->getName() . "" . $password . "");

			// from includes/installer/Installer.php
			if ($user->getId() == 0) {
				$user->addToDatabase();
				$status = $user->changeAuthenticationData([
					'username' => $user->getName(),
					'password' => $password,
					'retype' => $password,
				]);
				$user->saveSettings();

				if ($status->isGood()) {
					$output->addWikiTextAsContent("'''Account created successfully!'''");
				} else {
					$output->addWikiTextAsContent("'''Error creating account: " . $status->getWikiText() . "'''");
				}
			} else {
				$output = $this->getOutput();
				$output->addWikiTextAsContent("'''Username already exists. Please choose another one.'''");
			}
		}
	}

	public function processRequest()
	{
		// Set header to JSON for the response
		header("Content-Type: application/json");

		// Get the JSON input from the request body
		$input = file_get_contents("php://input");

		// Decode JSON input to an associative array
		$data = json_decode($input, true);

		if ($data) {
			// read the data
			$pubkey = $data['npub'];
			$signature = $data['signature'];


			$username = $pubkey;
			// TODO: generate a random password
			$password = "password";

			$user = self::$userFactory->newFromName($username);

			// from includes/installer/Installer.php
			if ($user->getId() == 0) {
				$user->addToDatabase();
				$status = $user->changeAuthenticationData([
					'username' => $user->getName(),
					'password' => $password,
					'retype' => $password,
				]);
				$user->saveSettings();

				if ($status->isGood()) {
					$status = "Account created successfully!";
				} else {
					$status = "Error creating account: " . $status->getWikiText();
				}
			} else {
				$status = "Username already exists. Please choose another one.";
			}


			// Respond with a JSON message
			echo json_encode([
				"status" => "success",
				"message" => "Data received",
				"receivedData" => [
					"npub" => $pubkey,
					"signature" => $signature
				]
			]);
		} else {
			// Error response if no data is received
			echo json_encode([
				"status" => "error",
				"message" => "No data received"
			]);
		}
	}
}
