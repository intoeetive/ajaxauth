<?php

if ( ! defined('AJAXAUTH_ADDON_NAME'))
{
	define('AJAXAUTH_ADDON_NAME',         'AJAX Auth');
	define('AJAXAUTH_ADDON_VERSION',      '1.2.0');
}

$config['name']=AJAXAUTH_ADDON_NAME;
$config['version']=AJAXAUTH_ADDON_VERSION;

$config['nsm_addon_updater']['versions_xml']='http://www.intoeetive.com/index.php/update.rss/32';
