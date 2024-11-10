<?php

namespace MediaWiki\Extension\NostrLogin;

use Exception;
use GlobalVarConfig;
use JsonContent;
use MediaWiki\MediaWikiServices;
use Title;

// from https://github.com/hexmode/mediawiki-iframe/blob/master/src/Config.php
class Config extends GlobalVarConfig {

	/**
	 * The page that contains our user-editable configuration.
	 */
	private const KEY_DOMAINS = 'Domains';

	private array $domains;

	public function __construct() {
		parent::__construct( 'NostrLogin' );
		$this->domains = $this->initDomains();
	}

	public static function newInstance() {
		return new self();
	}

	/**
	 * Get the initial list of domains.
	 *
	 */
	private function initDomains(): array {
		$ret = $this->get( self::KEY_DOMAINS );
		if ( !is_array( $ret ) ) {
			throw new Exception( wfMessage( 'nostrlogin-config-error-array-expected', gettype( $ret ) )->plain() );
		}
		$ret = array_filter( $ret, 'is_string' );
        return array_map( 'strtolower', $ret );
	}

	/**
	 * Get array of domains where the domains are they keys, not the values, of the array and, as a result,
	 * isset($domains["example.com"]) can be used to see if the domain is in the list returned.
	 */
	public function getDomains(): array {
		return $this->domains;
	}
}