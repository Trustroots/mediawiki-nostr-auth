# mediawiki nostr auth extension

There is just boilerplate code here for now.

This extension should enable people to log into mediawiki with their nostr identity.


## path

We'll first try to get this to work on nomadwiki.org, which arose from the digital graveyard today, so it's.

Thoughts about the user flow: https://github.com/Trustroots/mediawiki-nostr-auth/issues/5

When we have something that works we'll set it up on trashwiki.org, then finally hitchwiki.org, which is the most active wiki we run.



## boilerplate stuff

This is a blank extension template. It doesn't really do anything on its own.
It is intended to provide a boiler template for an actual MediaWiki extension.

If you are checking this out from Git and intend to use it, you may use the
following commands to make a clean directory of just this template without the
Git meta-data and other examples.

	cd extensions
	git clone https://gerrit.wikimedia.org/r/mediawiki/extensions/BoilerPlate.git
	cp -r BoilerPlate ./MyExtension
	rm -rf ./MyExtension/.git

This automates the recommended code checkers for PHP and JavaScript code in Wikimedia projects
(see https://www.mediawiki.org/wiki/Continuous_integration/Entry_points).
To take advantage of this automation.

1. install nodejs, npm, and PHP composer
2. change to the extension's directory
3. `npm install`
4. `composer install`

Once set up, running `npm test` and `composer test` will run automated code checks.
