<?php
/*
Fulltextr 1.1
Copyright 2007 D.M.Deller
See README.txt for more information.
*/

	require_once("config.php");
	header("Content-type: application/xml\n");
	error_reporting('E_NONE'); // errors are gonna screw up the XML...
	
	if (!in_array($_SERVER['REMOTE_ADDR'],explode(",",$config_allowed_ips)))
		die ("<error>Not authorised.</error>");
	
	//require_once("modules/".$_GET["m"]);
	
	// read module data
	if (file_exists("modules/".$_GET['m'].".txt"))
		$mod = file_get_contents("modules/".$_GET['m'].".txt");
	else if (file_exists("modules-dist/".$_GET['m'].".txt"))
		$mod = file_get_contents("modules-dist/".$_GET['m'].".txt");
	else
		die("<error>Module not found.</error>\n");
	
	// TODO: transition this to Module object from common.php
	if (preg_match("/[^#]name=(.*)\r?\n?/i",$mod,$matches))
		$mod_name = $matches[1];
	if (preg_match("/[^#]feed_url=(.*)\r?\n?/i",$mod,$matches))
		$mod_feed_url = $matches[1];
	if (preg_match("/[^#]match_body=(.*)\r?\n?/i",$mod,$matches))
		$mod_match_body = $matches[1];
	if (preg_match("/[^#]match_body_num=(.*)\r?\n?/i",$mod,$matches))
		$mod_match_body_num = $matches[1];
	if (preg_match("/[^#]remove=(.*)\r?\n?/i",$mod,$matches))
		$mod_remove = $matches[1];
	if (preg_match("/[^#]extra_css=(.*)\r?\n?/i",$mod,$matches))
		$mod_extra_css = $matches[1];
	if (preg_match("/[^#]prepend=(.*)\r?\n?/i",$mod,$matches))
		$mod_prepend = $matches[1];
	if (preg_match("/[^#]append=(.*)\r?\n?/i",$mod,$matches))
		$mod_append = $matches[1];
	if (preg_match("/[^#]charset=(.*)\r?\n?/i",$mod,$matches))
		$mod_charset = $matches[1];
  if (preg_match("/[^#]omit_cdata_dec=(.*)\r?\n?/i",$mod,$matches))
  	$mod_omit_cdata_dec = $matches[1];
  if (preg_match("/[^#]match_item_url=(.*)\r?\n?/i",$mod,$matches))
  	$mod_match_item_url = $matches[1];
  if (preg_match("/[^#]replace_item_url=(.*)\r?\n?/i",$mod,$matches))
  	$mod_replace_item_url = $matches[1];
  if (preg_match("/[^#]prefer_guid=(.*)\r?\n?/i",$mod,$matches))
  	$mod_prefer_guid = $matches[1];
	
	// set up MagpieRSS
	define('MAGPIE_CACHE_AGE',$config_cache_time);
	require_once("magpierss/rss_fetch.inc");
	//$url = $_GET['url'];
	$rss = fetch_rss( $mod_feed_url );
	
	/*
	echo "<pre>";
	var_dump($rss);
	echo "</pre><hr>";
	die();
	*/
	
	if (isset($mod_charset))
		echo '<?xml version="1.0" encoding="'.$mod_charset.'"?>'."\n";
	else
		echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
	
	// i know this is ugly... but MagpieRSS doesn't provide a way to get any information about namespace
	// definitions from the feed, so we'd better play it safe and include them all.
	// most feed readers do not even check these (they shouldn't need to), but any namespace used has 
	// to be defined here first in order for the document to be legal XML.
	echo '<rss version="2.0"
		xmlns:access="http://www.bloglines.com/about/specs/fac-1.0"
		xmlns:admin="http://webns.net/mvcb/"
		xmlns:ag="http://purl.org/rss/1.0/modules/aggregation/"
		xmlns:atom="http://purl.org/atom/ns"
		xmlns:annotate="http://purl.org/rss/1.0/modules/annotate/"
		xmlns:audio="http://media.tangent.org/rss/1.0/"
		xmlns:blogChannel="http://backend.userland.com/blogChannelModule"
		xmlns:cc="http://web.resource.org/cc/"
		xmlns:cf="http://www.microsoft.com/schemas/rss/core/2005"
		xmlns:company="http://purl.org/rss/1.0/modules/company"
		xmlns:content="http://purl.org/rss/1.0/modules/content/"
		xmlns:cp="http://my.theinfo.org/changed/1.0/rss/"
		xmlns:creativeCommons="http://backend.userland.com/creativeCommonsRssModule"
		xmlns:dc="http://purl.org/dc/elements/1.1/"
		xmlns:dcterms="http://purl.org/dc/terms/"
		xmlns:email="http://purl.org/rss/1.0/modules/email/"
		xmlns:ev="http://purl.org/rss/1.0/modules/event/"
		xmlns:feedburner="http://rssnamespace.org/feedburner/ext/1.0"
		xmlns:foaf="http://xmlns.com/foaf/0.1/"
		xmlns:g="http://base.google.com/ns/1.0"
		xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#"
		xmlns:geourl="http://geourl.org/rss/module/"
		xmlns:icbm="http://postneo.com/icbm"
		xmlns:image="http://purl.org/rss/1.0/modules/image/"
		xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd"
		xmlns:l="http://purl.org/rss/1.0/modules/link/"
		xmlns:media="http://search.yahoo.com/mrss"
		xmlns:opensearch="http://a9.com/-/spec/opensearch/1.1"
		xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
		xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
		xmlns:ref="http://purl.org/rss/1.0/modules/reference/"
		xmlns:reqv="http://purl.org/rss/1.0/modules/richequiv/"
		xmlns:rss091="http://purl.org/rss/1.0/modules/rss091#"
		xmlns:search="http://purl.org/rss/1.0/modules/search/"
		xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
		xmlns:ss="http://purl.org/rss/1.0/modules/servicestatus/"
		xmlns:str="http://hacks.benhammersley.com/rss/streaming/"
		xmlns:sub="http://purl.org/rss/1.0/modules/subscription/"
		xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
		xmlns:syn="http://purl.org/rss/1.0/modules/syndication/"
		xmlns:taxo="http://purl.org/rss/1.0/modules/taxonomy/"
		xmlns:thr="http://purl.org/rss/1.0/modules/threading/"
		xmlns:trackback="http://madskills.com/public/xml/rss/module/trackback"
		xmlns:wfw="http://wellformedweb.org/CommentAPI"
		xmlns:wiki="http://purl.org/rss/1.0/modules/wiki/"
		xmlns:xhtml="http://www.w3.org/1999/xhtml">'."\n";
  echo "	<channel>\n";
	
	// start with the same channel info that was originally present, except <generator/>
	foreach($rss->channel as $key=>$value)
	{
		// handle namespaces (possibly prone to breaking...)
		if (is_array($value))
		{
			foreach($value as $key2=>$value2)
			{
				if (($key2 != 'description') && (!strstr($key2,"content")))
					echo "	<$key:$key2>".htmlentities($value2)."</$key:$key2>\n";
			}
		}
		// normal, un-namespaced tags
		else
		{
			echo "	<$key>".htmlentities($value)."</$key>\n";
		}
	}

	foreach ($rss->items as $item)
	{
		if (($mod_prefer_guid == '1') && (isset($item['guid'])))
			$href = $item['guid'];
		else
			$href = $item['link'];
		
		if ((isset($mod_match_item_url)) && (isset($mod_replace_item_url)))
			$href = preg_replace($mod_match_item_url,$mod_replace_item_url,$href);
		
		// replace possibly invalid filesystem characters in the URL with underscores
		$fhref = preg_replace("/[\/:*?\"<>|~]/","_",$href);
		
		// if the file isn't cached, or the cache has expired, load it live
		if ((!file_exists("cache2/$fhref")) || ((time()-filemtime("cache2/$fhref")) > $config_cache2_time))
		{
			// make a backup, just in case this fetch attempt fails
			if (file_exists("cache2/$fhref"))
				rename("cache2/$fhref","cache2/$fhref.bak");
			
			$fp = fopen("cache2/$fhref","w");
			fwrite($fp,file_get_contents($href)); // hmm... let's hope they don't block a PHP user-agent
			fclose($fp);
		}
		
		// for some reason, regex match only works if there are no newlines
		preg_match($mod_match_body,str_replace("\r"," ",str_replace("\n"," ",file_get_contents("cache2/$fhref"))),$matches);
		
		// note: $matches[0] contains the full regex match. $matches[1] is the first parenthetical bit,
		// $matches[2] is the second parenthetical bit, etc.
		
		$title = $item['title'];
		
		// fallback: in case there was trouble finding a match this time, but we have a backup that might work...
		// try the match on the backup instead
		// (added in 1.1)
		if ((!isset($matches[$mod_match_body_num])) && (file_exists("cache2/$fhref.bak")))
		{
			preg_match($mod_match_body,str_replace("\r"," ",str_replace("\n"," ",file_get_contents("cache2/$fhref.bak"))),$matches);
			
			// if the match succeeded on the backup but failed on the current... replace the current with the backup
			if (isset($matches[$mod_match_body_num]))
			{
				unlink("cache2/$fhref");
				rename("cache2/$fhref.bak","cache2/$fhref");
				touch("cache2/$fhref"); // so it doesn't expire from the cache
			}
		}
		
		// we're done with the backup, clean it up now
		if (file_exists("cache2/$fhref.bak"))
			unlink("cache2/$fhref.bak");
		
		// now! we either have a match, or we don't, so figure out what to do for the full text description
		$ftrerror = false;
		if ((!isset($matches[$mod_match_body_num])) && (isset($item['description'])))
		{
			$html = "<i><b>Fulltextr:</b> Could not find the full text for this item. Falling back to the feed-supplied description.</i><br><br>".html_entity_decode($item['description']);
			$ftrerror = true;
		}
		else if (!isset($matches[$mod_match_body_num]))
		{
			$html = "<i><b>Fulltextr:</b> Could not find the full text of this item. This item does not have a description to fall back to.</i><br><br>".html_entity_decode($item['description']);
			$ftrerror = true;
		}
		else if (isset($mod_remove))
		{
			if (isset($mod_extra_css))
				$html = "<style>$mod_extra_css</style>".preg_replace($mod_remove,"",$matches[$mod_match_body_num]);
			else
				$html = preg_replace($mod_remove,"",$matches[$mod_match_body_num]);
		}
		else
		{
			if (isset($mod_extra_css))
				$html = "<style>$mod_extra_css</style>".$matches[$mod_match_body_num];
			else
				$html = $matches[$mod_match_body_num];
		}
		
		if (!$ftrerror && isset($mod_prepend))
			$html = $mod_prepend.$html;
		if (isset($mod_extra_css))
			$html = "<style>$mod_extra_css</style>".$html;
		if (!$ftrerror && isset($mod_append))
			$html = $html.$mod_append;
		
		echo "		<item>\n";
		
		$dcdate = false;
		// put all of the existing XML tags back into the feed... except article text
		foreach($item as $key=>$value)
		{
			if (($key != 'description') && ($key != 'summary') && ($key != 'date_timestamp')
				&& (!strstr($key,"content")))
			{
				// handle namespaces (possibly prone to breaking...)
				if (is_array($value))
				{
					foreach($value as $key2=>$value2)
					{
						if (($key2 != 'description') && (!strstr($key2,"content")))
							echo "			<$key:$key2>".htmlentities($value2)."</$key:$key2>\n";
						if ("$key:$key2" == "dc:date")
							$dcdate = true;
					}
				}
				// normal, un-namespaced tags
				else
				{
					// MagpieRSS can't tell us about XML attributes, so we have to play it safe...
					if ($key == 'guid')
						echo "			<guid isPermaLink=\"false\">".htmlentities($value)."</$key>\n";
					else if (($key == 'link') && (isset($mod_match_item_url)) && (isset($mod_replace_item_url)))
					{
						echo "			<link>".htmlentities(preg_replace($mod_match_item_url,$mod_replace_item_url,$href))."</link>\n";
					}
					else
						echo "			<$key>".htmlentities($value)."</$key>\n";
				}
			}
		}
		
		// some feed readers (NETNEWSWIRE) seem to want dc:date if the dc: namespace was defined... which it was...
		if ((!$dcdate) && (isset($item['date_timestamp'])))
			echo "			<dc:date>".date('c',$item['date_timestamp'])."</dc:date>\n";
		
		// and finally, all that hard work pays off!
		if (($mod_omit_cdata_dec) && (!$ftrerror))
		  echo "			<description>$html</description>\n";
		else
		  echo "			<description><![CDATA[$html]]></description>\n";
		echo "		</item>\n";
	}
	
	echo "	</channel>\n";
	echo "</rss>\n";
	
	// now let's clean up the caches - if there are any files more than 30 minutes (1800 seconds) older
	// than the cache expiration time, delete them
	foreach (scandir('cache') as $file)
	{
		if (($file != '.') && ($file != '..') && ((time()-filemtime("cache/$file")) > ($config_cache_time+1800)))
		{
			unlink("cache/$file");
		}
	}
	// this is more important for cache2 - it ensures that the full text of items that no longer appear in 
	// the feed will eventually be deleted, or else you could quickly use up a lot of space...
	foreach (scandir('cache2') as $file)
	{
		if (($file != '.') && ($file != '..') && ((time()-filemtime("cache2/$file")) > ($config_cache2_time+1800)))
		{
			unlink("cache2/$file");
		}
	}


?>