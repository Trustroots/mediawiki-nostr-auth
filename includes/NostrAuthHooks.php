<?php

namespace MediaWiki\Extension\NostrLogin;

/**
 * Class AuthRemoteUserHooks
 *
 * This class contains hooks for handling user registration with the AuthRemoteUser plugin.
 */
class NostrAuthHooks {

	/**
	 * Adds the AuthRemoteUser plugin configuration to PluggableAuth's
	 * global $wgPluggableAuth_Config array.
	 *
	 * @param array $info Information about the plugin.
	 *                    - name: The name of the plugin.
	 *
	 * @return void
	 */
	public static function onRegistration( array $info ) {

		if ( !isset( $GLOBALS['wgPluggableAuth_Config'] ) ) {
			$GLOBALS['wgPluggableAuth_Config'] = [];
		}
		$GLOBALS['wgPluggableAuth_Config'][$info['name']] = [
			'plugin' => 'NostrLogin',
			'buttonLabelMessage' => 'nostrlogin-login-button',
		];
	}
}