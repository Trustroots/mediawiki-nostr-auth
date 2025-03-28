
# mediawiki nostr auth extension

This extension enables people to log into mediawiki with their nostr identity.

It is using [NIP-05](https://github.com/nostr-protocol/nips/blob/master/05.md) to only allow users that have specific domains.

It currently relies on [NIP-07](https://github.com/nostr-protocol/nips/blob/master/07.md) so it will only work with browser extensions that can hold your nsec private key.

The extension is related to the [nostroots](https://github.com/trustroots/nostroots) project, the goal is to move [trustroots](https://www.trustroots.org/) onto [nostr](https://nostr.net/).

We want to support [NIP-46](https://github.com/nostr-protocol/nips/blob/master/46.md) in the future.


## Usage
On your wiki go to `Special:NostrLogin`

![image](https://github.com/user-attachments/assets/3000d27c-73dd-40e8-a6fc-d2043af764a4)

Go to `Special:UserLogin`

![image](https://github.com/user-attachments/assets/cd4a0062-3c61-4d21-839c-419601fbca59)

`Login with Nostr`.

## Installation
Go to the root of your MediaWiki setup.

Run:
```
cd extensions
git clone https://github.com/Trustroots/mediawiki-nostr-auth.git
```

Add the following to `LocalSettings.php`
```
wfLoadExtension( 'mediawiki-nostr-auth' );
$NostrLoginDomains = [
	   'trustroots.org'
];
# to set email after authenticating with nostr
$wgGroupPermissions['*']['editmyprivateinfo'] = true;
$wgGroupPermissions['*']['viewmyprivateinfo'] = true;
```

Install the [PluggableAuth extension](https://www.mediawiki.org/wiki/Extension:PluggableAuth) and the following to `LocalSettings.php`
```
wfLoadExtension( 'PluggableAuth' );
$wgPluggableAuth_EnableLocalLogin = true;
```

Install https://github.com/nostrver-se/nostr-php via `composer` on your Mediawiki.

## Path Forward

- [x] We'll first try to get this to work on nomadwiki.org, which arose from the digital graveyard in October 2024.
- [ ] Thoughts about the user flow: https://github.com/Trustroots/mediawiki-nostr-auth/issues/5
- [ ] When we have something that works we'll set it up on trashwiki.org
- [ ] Finally set it up on hitchwiki.org, which is the most active wiki we run.
- [ ] Promote logging into these wikis to trustroots users.

## Resources

- https://github.com/nostr-protocol/nips/issues/154

## Inspiration

### Builing Extensions
- https://github.com/hexmode/mediawiki-iframe/tree/master
- https://github.com/wikimedia/mediawiki-extensions-BoilerPlate/tree/master

### Building pluggable auth extensions
- https://www.mediawiki.org/wiki/Extension:PluggableAuth
- https://www.mediawiki.org/wiki/Extension:AuthRemoteUser
- https://www.mediawiki.org/wiki/Extension:LDAPAuthentication2

### building login
- https://github.com/wikimedia/mediawiki-extensions-GoogleLogin
- https://github.com/Sebastix/CCNS

## assumptions
- browser extension (e.g Alby) used to sign Nostr events
- no user with the same trustroots user name exists so far in the wiki

## developing

This automates the recommended code checkers for PHP and JavaScript code in Wikimedia projects
(see https://www.mediawiki.org/wiki/Continuous_integration/Entry_points).
To take advantage of this automation.

1. install nodejs, npm, and PHP composer
2. change to the extension's directory
3. `npm install`
4. `composer install`

Once set up, running `npm test` and `composer test` will run automated code checks.

## Contributing
Feel free to contribute via a pull request, create or assign yourself to an existing issue before working on it.

## Funding

In August 2024 OpenSats granted Trustroots to work on nostroots https://opensats.org/blog/nostr-grants-august-2024#nostroots as the Trustroots ecosystem includes several MediaWikis we invested energy into this MediaWiki extension to tighten the bonds between nostroots and the Wikis.


