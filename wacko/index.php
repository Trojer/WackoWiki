<?php

// initialize engine api
require('classes/init.php');
$init = new init();

// define settings
$init->settings();	// populate from config.inc.php
$init->settings();	// initialize DBAL and populate from config table
$init->dbal();
$init->settings('theme_url',	$init->config['base_url'].'themes/'.$init->config['theme'].'/');
$init->settings('user_table',	$init->config['table_prefix'].'user');
$init->settings('cookie_hash',	hash('sha1', $init->config['base_url'].$init->config['system_seed']));
// run in ssl mode?
#echo "<br />base_url 1: ".$init->config['base_url'];
if ($init->config['ssl'] == true && (( ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") && !empty($init->config['ssl_proxy'])) || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') ) ))
{
	$init->settings('base_url',	str_replace("http://", "https://".($init->config['ssl_proxy'] ? $init->config['ssl_proxy'].'/' : ''), $init->config['base_url']));
}
$init->settings('cookie_path',	preg_replace('|https?://[^/]+|i', '', $init->config['base_url'].''));
#echo "<br />base_url 2: ".$init->config['base_url'];

if ($init->is_locked() === true)
{
	header('HTTP/1.1 503 Service Temporarily Unavailable');
	echo "The site is temporarily unavailable due to system maintenance. Please try again later.";
	exit;
}

// misc
$init->request();
$init->session();

// engine start
$cache	= $init->cache();
$init->cache('check');
$engine	= $init->engine();
$init->cache('log');

// execute and cache
$init->engine('run');
$init->cache('store');
$init->debug();

// closing tags
if (strpos($init->method, '.xml') === false)
	echo "\n</body>\n</html>";

// out
header('Cache-Control: public');
header('Pragma: cache');
ob_end_flush();

?>