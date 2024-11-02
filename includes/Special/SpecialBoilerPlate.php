<?php
namespace MediaWiki\Extension\BoilerPlate\Special;

use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserOptionsManager;
use MediaWiki\Auth\TemporaryPasswordAuthenticationRequest;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Auth\AuthenticationRequest;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\User;

class SpecialBoilerPlate extends SpecialPage {
	public function __construct() {
		parent::__construct( 'BoilerPlate' );
	}

	public function execute( $par ) {
		$request = $this->getRequest();
		$output = $this->getOutput();
		$this->setHeaders();

		if (!$this->getUser()->isAllowed('createaccount')) {
            throw new PermissionsError('createaccount');
        }

		# Get request data from, e.g.
		$param = $request->getText( 'param' );

		# Do stuff
		# ...
		$wikitext = 'Hello '. $par;
		$output->addWikiTextAsInterface( $wikitext );
		// Add a basic form with a button
		$html = '<form method="post" action="' . htmlspecialchars($this->getPageTitle()->getLocalURL()) . '">';
		$html .= '<input type="hidden" name="action" value="submit_form">';
		$html .= '<label for="username">Username:</label>';
		$html .= '<input type="text" id="username" name="username" required>';
		$html .= '<label for="password">Password:</label>';
		$html .= '<input type="password" id="password" name="password" required>';
		$html .= '<input type="submit" value="Create new account" class="cdx-button cdx-button--action-progressive">';
		$html .= '</form>';
		$html .= '<button onclick="printNostr()">Print window.nostr</button>';
		$html .= '<script src="https://unpkg.com/nostr-tools/lib/nostr.bundle.js"></script>';
		$html .= '<script>

			function createAuthEvent(){
				const authEvent = {
					kind: 12345, // event kind 
					created_at: Math.floor(Date.now() / 1000), // event timestamp
					tags: [],    // event tags
					content: ""  // event content
				}
				return authEvent;
			}

			function isValidSignature(event){
				const isValid = window.NostrTools.verifyEvent(event);
				return isValid;
			}
	
			async function printNostr() {
				// console.log(window.NostrTools.generateSecretKey());
				// https://github.com/nostr-protocol/nips/issues/154
				// https://nostrlogin.org/
				const pubkey = await window.nostr.getPublicKey();
				console.log("pubKey: " + pubkey);

				const authEvent = createAuthEvent();
				const signEvent = await window.nostr.signEvent(authEvent);
				const signature = signEvent.sig
				console.log("signature: " + signature);

				if (!isValidSignature(signEvent)) {
					throw new AuthenticationError();
				} else {
				 	console.log("Signature Valid.");
				}
				
				// Send the public key to the server using an AJAX request
				const xhr = new XMLHttpRequest();
				xhr.open("POST", "' . htmlspecialchars($this->getPageTitle()->getLocalURL()) . '", true);
				xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				xhr.onreadystatechange = function () {
					if (xhr.readyState === 4 && xhr.status === 200) {
						console.log("Public key sent to server");
					}
				};
				xhr.send("action=store_public_key&public_key=" + encodeURIComponent(pubkey));
			}
		</script>';

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
		$output = $this->getOutput();
		if ($request->getVal('action') === 'submit_form') {
			$username = $request->getVal('username');
			$password = $request->getVal('password');
			
			$userFactory = MediaWikiServices::getInstance()->getUserFactory();
			$user = $userFactory->newFromName($username);

			$output->addWikiTextAsContent("'''Creating account...'''" . $user->getName() ."". $password ."");
			
			// from includes/installer/Installer.php
			if ($user->getId() == 0) {
				$user->addToDatabase();
				$status = $user->changeAuthenticationData( [
					'username' => $user->getName(),
					'password' => $password,
					'retype' => $password,
				] );
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

	// Handle AJAX request to store public key
	private function handleAjaxRequest() {
		$request = $this->getRequest();
		if ($request->wasPosted() && $request->getVal('action') === 'store_public_key') {
			$publicKey = $request->getVal('public_key');
			// Store the public key in a PHP variable
			// Log the public key to the console
			error_log("Public key set: " . $publicKey); 
			$GLOBALS['publicKey'] = $publicKey;
			// Display the public key using echo
			echo $publicKey;
			echo "PubKey received.";
			exit;
		}
	}
}
