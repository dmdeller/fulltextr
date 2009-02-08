<?php
/*
Fulltextr 1.1
Copyright 2007 D.M.Deller
See README.txt for more information.
*/


// transitioning to some OO for stuff. currently only used in index.php, but I plan to move feed.php over to this as well to cut down on copy-pasting.

class Module
{

	public $name,$feed_url,$match_body,$match_body_num,$remove,$extra_css,$prepend,$append,$charset,$omit_cdata_dec,
		$tags,$comments,$site_url;

	function __construct($filename)
	{
		if (file_exists($filename))
		{
			$mod = file_get_contents("$filename");
			
			// there must be a simpler way to do this... need to investigate sometime
			if (preg_match('/^name=(.*)/i',$mod,$matches))
				$this->name = $matches[1];
			else
				throw new Exception("$filename missing required parameter \"name\": $mod");
			
			if (preg_match("/[^#]feed_url=(.*)\r?\n?/i",$mod,$matches))
				$this->feed_url = $matches[1];
				
			if (preg_match("/[^#]match_body=(.*)\r?\n?/i",$mod,$matches))
				$this->match_body = $matches[1];
				
			if (preg_match("/[^#]match_body_num=(.*)\r?\n?/i",$mod,$matches))
				$this->match_body_num = $matches[1];
				
			if (preg_match("/[^#]remove=(.*)\r?\n?/i",$mod,$matches))
				$this->remove = $matches[1];
				
			if (preg_match("/[^#]extra_css=(.*)\r?\n?/i",$mod,$matches))
				$this->extra_css = $matches[1];
				
			if (preg_match("/[^#]prepend=(.*)\r?\n?/i",$mod,$matches))
				$this->prepend = $matches[1];
				
			if (preg_match("/[^#]append=(.*)\r?\n?/i",$mod,$matches))
				$this->append = $matches[1];
				
			if (preg_match("/[^#]charset=(.*)\r?\n?/i",$mod,$matches))
				$this->charset = $matches[1];
				
			if (preg_match("/[^#]omit_cdata_dec=(.*)\r?\n?/i",$mod,$matches))
				$this->omit_cdata_dec = $matches[1];
			
			if (preg_match("/[^#]tags=(.*)\r?\n?/i",$mod,$matches))
				$this->tags = $matches[1];
			
			if (preg_match("/[^#]comments=(.*)\r?\n?/i",$mod,$matches))
				$this->comments = $matches[1];
				
			if (preg_match("/[^#]site_url=(.*)\r?\n?/i",$mod,$matches))
				$this->site_url = $matches[1];
			
			
		}
		else
		{
			throw new Exception("File not found: $filename");
		}
	}
}

?>