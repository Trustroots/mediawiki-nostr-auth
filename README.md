
# mediawiki nostr auth extension

This extension should enable people to log into mediawiki with their nostr identity.

## Usage
On your wiki go to `Special:NostrLogin`
![image](https://github.com/user-attachments/assets/3000d27c-73dd-40e8-a6fc-d2043af764a4)

Go to `Special:UserLogin`
![image](https://github.com/user-attachments/assets/cd4a0062-3c61-4d21-839c-419601fbca59)

`Login with Nostr`.

## path forward

- [ ] We'll first try to get this to work on nomadwiki.org, which arose from the digital graveyard in October 2024.
- [ ] Thoughts about the user flow: https://github.com/Trustroots/mediawiki-nostr-auth/issues/5
- [ ] When we have something that works we'll set it up on trashwiki.org
- [ ] Finally set it up on hitchwiki.org, which is the most active wiki we run.
- [ ] Promote logging into these wikis to trustroots users.

## resources

- https://github.com/nostr-protocol/nips/issues/154

## inspiration
- https://github.com/wikimedia/mediawiki-extensions-GoogleLogin
- https://github.com/hexmode/mediawiki-iframe/tree/master
- https://github.com/wikimedia/mediawiki-extensions-BoilerPlate/tree/master

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

## Funding

## ...

Once set up, running `npm test` and `composer test` will run automated code checks.
