# mediawiki nostr auth extension

This extension should enable people to log into mediawiki with their nostr identity.

## path forward

- [ ] We'll first try to get this to work on nomadwiki.org, which arose from the digital graveyard in October 2024.
- [ ] Thoughts about the user flow: https://github.com/Trustroots/mediawiki-nostr-auth/issues/5
- [ ] When we have something that works we'll set it up on trashwiki.org
- [ ] Finally set it up on hitchwiki.org, which is the most active wiki we run.
- [ ] Promote logging into these wikis to trustroots users.

## resources

- https://github.com/nostr-protocol/nips/issues/154

## installation
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
	   'trustroots.org',
	   'couchers.org'
];
```

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
