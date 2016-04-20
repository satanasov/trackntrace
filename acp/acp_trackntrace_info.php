<?php
/**
*
* @package acp
* @version $Id: acp_my_page.php,v 1.10 2006/12/31 16:56:14 acydburn Exp $
* @copyright (c) 2006 phpBB Group 
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

namespace anavaro\trackntrace\acp;

/**
* @package module_install
*/
class acp_trackntrace_info
{
	function module()
	{
		return array(
			'filename'	=> 'anavaro\trackntrace\acp\acp_trackntrace_module',
			'title'		=> 'ACP_TRACKNTRACE', // define in the lang/xx/acp/common.php language file
			'version'	=> '1.0.0',
			'modes'		=> array(
				'main'		=> array(
					'title'		=> 'ACP_TRACKNTRACE',
					'auth' 		=> 'ext_anavaro/trackntrace && acl_a_user',
					'cat'		=> array('ACP_TRACKNTRACE_GRP')
				),
			),
		);
	}
}
