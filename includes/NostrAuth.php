<?php

namespace MediaWiki\Extension\NostrLogin;

use MediaWiki\Auth\AuthManager;
use MediaWiki\Extension\PluggableAuth\PluggableAuth as PA_Base;
use MediaWiki\Extension\PluggableAuth\PluggableAuthLogin;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MWException;
use swentel\nostr\Event\Event;

/**
 * Class PluggableAuth
 *
 * This class provides a pluggable authentication mechanism for MediaWiki, relying on webserver authentication.
 *
 *
 * @package MediaWiki\Auth
 */
class NostrAuth extends PA_Base
{

	/**
	 * @var AuthManager
	 */
	private AuthManager $authManager;

	/**
	 * @var UserFactory
	 */
	private UserFactory $userFactory;


	/**
	 * Name of username extra login field
	 */
	const USERNAME = 'username';

	/**
	 * Name of nostr extra login field
	 */
	const NOSTR_PASSWORD = 'nostr_password';


	/**
	 * @param UserFactory $userFactory
	 * @param AuthManager $authManager
	 */
	public function __construct(AuthManager $authManager)
	{
		$this->authManager = $authManager;
	}

	/**
	 * @inheritDoc
	 * @throws MWException
	 */
	public function authenticate(?int &$id, ?string &$username, ?string &$realname, ?string &$email, ?string &$errorMessage): bool
	{
		// does the trick for now
		$id = null;

		$extraLoginFields = $this->authManager->getAuthenticationSessionData(
			PluggableAuthLogin::EXTRALOGINFIELDS_SESSION_KEY
		);

		$username = $extraLoginFields[static::USERNAME] ?? '';
		$nostr = $extraLoginFields[static::NOSTR_PASSWORD] ?? '';

		$url = 'https://www.trustroots.org/.well-known/nostr.json?name=' . $username;
		$json = file_get_contents($url);
		$obj = json_decode($json, true);
		$npub = $obj["names"][ucfirst($username)];
		
		// in accordance with auth.js
		$note = new Event();
		$note->setKind(12345);
		$note->setContent("");
		$note->setSignature($nostr);
		$note->setPublicKey($npub);
		$note->setTags([]);
		$note->setCreatedAt(0);

		try {
            $computedId = hash(
                'sha256',
                json_encode(
                    [
                        0,
                        $note->getPublicKey(),
                        $note->getCreatedAt(),
                        $note->getKind(),
                        $note->getTags(),
                        $note->getContent(),
                    ],
                    \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE,
                ),
            );
        } catch (\JsonException) {
			$errorMessage = "Problems with Nostr event ID calculation.";
            return false;
        }

		$note->setId($computedId);

		if (!$note->verify()) {
			$errorMessage = "Nostr signature verification failed";
			//return false;
		}

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function deauthenticate(UserIdentity &$user): void
	{
		// Nothing to do, really
		$user = null;
	}

	/**
	 * @inheritDoc
	 */
	public function saveExtraAttributes(int $id): void
	{
		// Nothing to do, really
	}


	public static function getExtraLoginFields(): array
	{
		return [
			static::USERNAME => [
				'type' => 'string',
				'label' => "Username",
				'help' => "Username",
			],
			static::NOSTR_PASSWORD => [
				'type' => 'string',
				'label' => "Nostr Password",
				'help' => "Get your Nostr password from Special:NostrLogin",
			]
		];
	}
}