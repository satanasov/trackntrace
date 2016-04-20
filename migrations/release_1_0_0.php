<?php
/**
*
* @package migration
* @copyright (c) 2014 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
*
*/

namespace anavaro\trackntrace\migrations;

class release_1_0_0 extends \phpbb\db\migration\migration
{
	public function update_data()
	{
		return array(
			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_TRACKNTRACE_GRP'
			)),
			array('module.add', array(
				'acp',
				'ACP_TRACKNTRACE_GRP',
				array(
					'module_basename'	=> '\anavaro\trackntrace\acp\acp_trackntrace_module',
					'module_mode'		=> array('main'),
					'module_auth'        => 'ext_anavaro/trackntrace && acl_a_user',
				)
			)),
		);
	}

	//lets create the needed table
	public function update_schema()
	{
		return array(
			'add_tables'    => array(
				$this->table_prefix . 'fingerprint'	=> array(
					'COLUMNS'	=> array(
						'user_id'	=> array('UINT', 0),
						'fingerprint'	=> array('VCHAR:40', ''),
						'session_start'	=> array('TIMESTAMP', 0),
						'user_agent'	=> array('VCHAR:150', '')
					),
					'KEYS'	=> array(
						'pr'	=> array('UNIQUE', array('session_start', 'fingerprint', 'user_id'))
					),
				),
			),
		);
	}
	public function revert_schema()
	{
		return array(
			'drop_tables'		=> array(
				//$this->table_prefix . 'fingerprint',
			),
		);
	}
}
