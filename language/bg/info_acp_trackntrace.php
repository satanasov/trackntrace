<?php
/**
*
* Track'n'Trace Admin [Bulgarian]
*
* @package phpBB Track'n'Trace Admin
* @version $Id$
*
**/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}
$lang = array_merge($lang, array(
	'FINGERPRINT_SEARCH'			=> 'Fingerprint search',
	'FINGERPRINT_SEARCH_EXPLAIN'	=> 'Search fingerprints database by user or fingerprint',

	'FINGERPRINT_SEARCH_FOR'		=> 'Search for',
	'ENTER_USERNAME'				=> 'Enter criteria',
	'SELECT_USERNAME'				=> 'Username',
	'SELECT_ID'						=> 'User ID',
	'SELECT_FINGERPRINT'			=> 'Fingerprint',

	'FINGERPRINT_SEARCH_USER_UNIQUE_FINGERPRINTS'	=> 'User unique fingerprints',
	'FINGERPRINT_SEARCH_USER_BASE'	=> 'Showing fingerprints for user %1$s',
	'FINGERPRINT_SEARCH_ID_BASE'	=> 'Showing fingerprints for ID <b>%1$s</b>',
	'USER_FINGERPRINTS'				=> 'Fingerprints list',

	'FINGERPRINT_SEARCH_IP_BASE'	=> 'Showing all users using fingerprint of <b>%1$s</b>',

	'FINGERPRINT_SEARCH_FP_UNIQUE_USERS'	=> 'List of all users using this Fingerprint',
	
));