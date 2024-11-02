<?php
require '../../vendor/autoload.php';

use MediaWiki\MediaWikiServices;

$userFactory = MediaWikiServices::getInstance()->getUserFactory();

echo "Hello World!";