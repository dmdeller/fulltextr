<?php
/*
Fulltextr 1.1
Copyright 2007 D.M.Deller
See README.txt for more information.
*/
require_once("config.php");
require_once("common.php");

if (!in_array($_SERVER['REMOTE_ADDR'],explode(",",$config_allowed_ips)))
  die ("Not authorised. (".$_SERVER['REMOTE_ADDR'].")");
?>
<html>
<head>
	<title>Fulltextr</title>
	<style><!--
	table { background-color: grey; }
	td,th { background-color: white; padding: 3px; }
	acronym { border-bottom: thin dotted black; }
	img { border: none; }
	--></style>
</head>
<body>
	<h1>Fulltextr 1.1 prerelease</h1>
	This installation is only accessible to the following IPs: <?php echo $config_allowed_ips; ?><br>
	Your IP is: <?php echo $_SERVER['REMOTE_ADDR']; ?>
	<br><br>
	Remember, the first time you load a feed, it can take some time (up to a minute for some sites).
	
	<h2>Your modules</h2>
	<table>
		<tr>
			<th>Name</th>
			<th><acronym title="Fulltextr">Ftr</acronym> feed</th>
			<th>Site link</th>
			<th>Tags</th>
			<th>Comments</th>
		</tr>
	<?php
	$i = 0;
	foreach (scandir("modules") as $file)
	{
		if (substr($file,-4,4) == ".txt")
		{
			$mod = new Module("modules/$file");
  		
			echo "<tr>\n";
			echo "<td>$mod->name</td>\n";
			echo "<td align='center'><a href='feed.php?m=".substr($file,0,strlen($file)-4)."'><img src='feed-icon-16x16.gif' border='0'></a></td>\n";
			
			if (isset($mod->site_url))
				echo "<td align='center'><a href='$mod->site_url'><img src='tango-home-16x16.gif'></a></td>";
			else
				echo "<td></td>";
			
			if (isset($mod->tags))
				echo "<td>$mod->tags</td>";
			else
				echo "<td></td>";
			
			if (isset($mod->comments))
				echo "<td>$mod->comments</td>";
			else
				echo "<td></td>";
			
			echo "</tr>\n";
			//var_dump(get_object_vars($mod));
			$i++;
		}
	}
	if ($i == 0)
		echo "<tr><td colspan='5'>(none)</td></tr>";
	?>
	</table>
	<br>
	<h2>Included modules</h2>
	<!--Installed modules release date: 2007-07-06<br>
	Last checked for updates: 2007-07-06<br>
	Automatic updates: disabled (<a href="#">check now</a>)<br>
	<br>-->
	<table>
		<tr>
			<th>Name</th>
			<th><acronym title="Fulltextr">Ftr</acronym> feed</th>
			<th>Site link</th>
			<th>Tags</th>
			<th>Comments</th>
		</tr>
	<?php
	$i = 0;
	foreach (scandir("modules-dist") as $file)
	{
		if (substr($file,-4,4) == ".txt")
		{
			$mod = new Module("modules-dist/$file");
  		
			echo "<tr>\n";
			echo "<td>$mod->name</td>\n";
			
			if (file_exists("modules/$file"))
				echo "<td align='center'><img src='feed-icon-16x16-grey.gif' border='0' title='Overridden by one of your modules'></td>\n";
			else
				echo "<td align='center'><a href='feed.php?m=".substr($file,0,strlen($file)-4)."'><img src='feed-icon-16x16.gif' border='0'></a></td>\n";
			
			if (isset($mod->site_url))
				echo "<td align='center'><a href='$mod->site_url'><img src='tango-home-16x16.gif'></a></td>";
			else
				echo "<td></td>";
			
			if (isset($mod->tags))
				echo "<td>$mod->tags</td>";
			else
				echo "<td></td>";
			
			if (isset($mod->comments))
				echo "<td>$mod->comments</td>";
			else
				echo "<td></td>";
			
			echo "</tr>\n";
			//var_dump(get_object_vars($mod));
			$i++;
		}
	}
	if ($i == 0)
		echo "<tr><td>(none)</td></tr>";
	?>
	</table>
	<br>
	Hint: copy the link for one of the above feeds and paste it into your RSS reader, or use a browser like Firefox or Safari that will help you subscribe to a feed when it encounters one.
	<br><br>
	Note: Firefox does not display feed items with a lot of text, or with embeds.
	<br><br>
	<a href="http://horizon-nigh.org/projects/fulltextr.php">Fulltextr project page</a><br><em>Fulltextr is free software that runs on any web server, and the Fulltextr authors are not affiliated with the operators of any web servers running the software.</em>
</body>
</html>