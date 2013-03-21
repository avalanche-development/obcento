<?php

/* this is just showing a few possible usages */

include_once dirname(__FILE__) . '/lib/ObcentoTwitter.class.php';

$consumer_key = '';
$consumer_secret = '';
$access_token = '';
$access_token_secret = '';

$obcento = ObcentoTwitter::instance(
	$consumer_key,
	$consumer_secret,
	$access_token,
	$access_token_secret);

/* get the last 50 of the authenticating user's tweets */
$obcento->getUserTimeline(NULL, 50)->fetchJSON();

/* get some tweets from your 'home' timeline */
$obcento->getHomeTimeline()->fetchArray();

/* get the last few mentions of the authenticating user */
$obcento->getMentionsTimeline(5); // yes, there is a __toString()!