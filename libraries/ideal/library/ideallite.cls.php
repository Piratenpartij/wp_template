<?php

	// Set default timezone for DATE/TIME functions
	if(function_exists('date_default_timezone_set'))
	{
		date_default_timezone_set('Europe/Amsterdam');
	}

	if(function_exists('stripos') == false)
	{
		function stripos($haystack, $search, $offset = 0)
		{
			return strpos(strtolower($haystack), strtolower($search), $offset);
		}
	}

	// Load iDEAL settings
	if(file_exists(dirname(__FILE__) . '/ideallite.cfg.php'))
	{
		require_once(dirname(__FILE__) . '/ideallite.cfg.php');
	}

	// Load iDEAL classes
	if(version_compare(PHP_VERSION, '5', '<'))
	{
		require_once(dirname(__FILE__) . '/ideallite.cls.4.php');
	}
	else
	{
		require_once(dirname(__FILE__) . '/ideallite.cls.5.php');
	}

?>