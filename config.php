<?php

/*
	Configuration file for Fulltextr
*/

// Allowed IPs: enter a list of IP addresses, separated by commas, to allow access to Fulltextr.
// Tip: 127.0.0.1 always refers to the computer that you're currently using.
// Default is: 127.0.0.1
// If you leave it at the default value, then you will only be able to access Fulltextr from the computer
// that Fulltextr is installed on (recommended).
//
// WARNING: allowing other people on the internet to access your Fulltextr feeds may constitute
// unauthorised distribution of copyrighted materials!
$config_allowed_ips = "127.0.0.1,::1,192.168.1.98";

// As an alternative to the above, you may also be able to set up HTTP Authentication for the entire Fulltextr
// directory, with Apache's .htaccess (Google it for more info).

// Cache time: the duration (in seconds) to keep files in cache before reloading them from the web.
// Default is 3600 seconds, or 1 hour (recommended).
// This cache is used to fetch the RSS feeds.
$config_cache_time = 3600;

// Cache2 time: the duration (in seconds) to keep files in cache2 before reloading them from the web.
// Default is 21600 seconds, or 6 hours.
// Cache2 is used to fetch the actual web pages (not feeds) in order to get the full article text.
// Since these rarely change, but fetching them uses a lot of bandwidth, you should keep this at a high value.
$config_cache2_time = 21600;

// Note that no action at all will be taken unless your RSS reader requests a feed;
// so even if both caches are set to one hour, if your RSS reader only refreshes every 3 hours, then
// Fulltextr will only spring into action every 3 hours.

// If you have multiple RSS readers updating from the same Fulltextr feed, Fulltextr will still fetch
// the feed contents from the internet no more than once every cache cycle as defined above.

// Fulltextr automatically cleans up its cache by deleting stale items, so you don't have to worry about it.

?>