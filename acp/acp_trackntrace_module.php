<?php
/**
*
* @package Anavaro.com PM Admin
* @copyright (c) 2013 Lucifer
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace anavaro\trackntrace\acp;

/**
* @package acp
*/
class acp_trackntrace_module
{
	var $search;
	var $u_action;

	function main($id, $mode)
	{
		global $db, $template, $request, $user;
		global $table_prefix, $phpbb_root_path, $phpbb_container;
		$db_tools = $phpbb_container->get('dbal.tools');
		$this->tpl_name = 'acp_trackntrace';

		// Let's define image
		$image = array(
			'search'	=> '<img src="' . $phpbb_root_path . 'ext/anavaro/trackntrace/adm/images/spyglass.png">',
		);
		$case = $request->variable('case', '');
		switch ($case)
		{
			case 'username':
				$username = utf8_normalize_nfc($request->variable('username', '', true));
				if ($username == '')
				{
					// To DO LANg
					trigger_error('USERNAME_MISSING', E_USER_WARNING);
				}
				$sql = 'SELECT user_id FROM ' . USERS_TABLE . '
				WHERE username_clean = \'' . $db->sql_escape(utf8_clean_string($username)) . '\'';
				$result = $db->sql_query($sql);
				$user_id = (int) $db->sql_fetchfield('user_id');
				$db->sql_freeresult($result);
				if (!$user_id)
				{
					trigger_error($user->lang['NO_USER'] . adm_back_link($this->u_action), E_USER_WARNING);
				}
				$template->assign_vars(array(
					'FINGERPRINT_SEARCH_USER'	=> $user->lang('FINGERPRINT_SEARCH_USER_BASE', $username),
				));
				$this->page_title = $user->lang('SESSION_SEARCH_USER_BASE', $username);
			case 'userid':
				if (!isset($user_id))
				{
					$user_id = $request->variable('username', '', true);
					if ($user_id == '')
					{
						trigger_error('USERID_MISSING', E_USER_WARNING);
					}
				}
				if (!isset($username))
				{
					$template->assign_vars(array(
						'FINGERPRINT_SEARCH_USER'	=> $user->lang('FINGERPRINT_SEARCH_ID_BASE', $user_id),
					));
					$this->page_title = $user->lang('SESSION_SEARCH_ID_BASE', $user_id);
				}
				// Let's get list of users fingerprints
				$sql = 'SELECT DISTINCT(fingerprint), session_start FROM ' . $table_prefix . 'fingerprint WHERE user_id = ' . $user_id . ' ORDER BY session_start DESC';
				$result = $db->sql_query($sql);
				while ($row = $db->sql_fetchrow($result))
				{
					$template->assign_block_vars('user_fingers', array(
						'USER_FINGERPRINT'	=> '<a href="' . $this->u_action . '&case=fingerprint&username=' . $row['fingerprint'] .'">' . $row['fingerprint'] . '</a>',
					));
				}
				$sql = 'SELECT * FROM ' . $table_prefix . 'fingerprint WHERE user_id = ' . $user_id . ' ORDER BY session_start DESC';
				$result = $db->sql_query_limit($sql, 1000, 0);
				while ($row = $db->sql_fetchrow($result))
				{
					$template->assign_block_vars('fingerprints', array(
						'FINGERPRINT'	=> '<a href="' . $this->u_action . '&case=fingerprint&username=' . $row['fingerprint'] .'">' . $row['fingerprint'] . '</a>',
						'SESSION_START'	=> $user->format_date($row['session_start'], 'd.m.Y, H:i'),
						'USER_AGENT'	=> $row['user_agent'],
					));
				}
				$db->sql_freeresult($result);
				$template->assign_var('S_STAGE', 'user');
			break;
			case 'fingerprint':
				$user_fp = $request->variable('username', '', true);
				if ($user_fp == '')
				{
					trigger_error('USER_FINGERPRINT_MISSING', E_USER_WARNING);
				}
				$template->assign_vars(array(
					'FP_SEARCH'	=> $user->lang('FINGERPRINT_SEARCH_IP_BASE', $user_fp),
					'S_STAGE'	=> 'fingerprint',
				));
				$sql = 'SELECT DISTINCT(user_id) as user_id, session_start FROM ' . $table_prefix . 'fingerprint WHERE fingerprint = \'' . $user_fp . '\' ORDER BY session_start DESC';
				$result = $db->sql_query($sql);
				$user_ids = array();
				while ($row = $db->sql_fetchrow($result))
				{
					$user_ids[] = $row['user_id'];
				}
				$db->sql_freeresult($result);
				if (empty($user_ids))
				{
					trigger_error('NO_USER', E_USER_WARNING);
				}
				// Let's get some live users from all the IP's (we could have deleted users and if we use some other extension we could have users_deleted table
				$users_array = array();
				$sql = 'SELECT user_id, username, user_colour
				FROM ' . USERS_TABLE . '
				WHERE ' . $db->sql_in_set('user_id', $user_ids) . '
				ORDER BY user_id ASC';
				$result = $db->sql_query($sql);
				while ($row = $db->sql_fetchrow($result)) {
					$users_array[$row['user_id']] = array(
						'id'	=> $row['user_id'],
						'username'	=> $row['username'],
						'colour'	=> $row['user_colour'],
					);
				}
				$db->sql_freeresult($result);
				if ($db_tools->sql_table_exists($table_prefix . 'users_deleted'))
				{
					// If the db table exists we are going to use it to get deleted users
					$sql = 'SELECT user_id, username
					FROM ' . $table_prefix . 'users_deleted
					WHERE ' . $db->sql_in_set('user_id', $user_ids) . '
					ORDER BY user_id ASC';
					$result = $db->sql_query($sql);
					while ($row = $db->sql_fetchrow($result)) {
						$users_array[$row['user_id']] = array(
							'id'	=> $row['user_id'],
							'username'	=> $row['username'],
							'colour'	=> '000000',
						);
					}
					$db->sql_freeresult($result);
				}
				foreach($users_array as $var)
				{
					$template->assign_block_vars('fp_users', array(
						'USERNAME'	=> '<a class="username-coloured" style="color: #'.(isset($var['colour']) ? $var['colour'] : '') . ';" href="' .append_sid($phpbb_root_path. 'memberlist.php?mode=viewprofile&u=' . $var['id']) . '" target="_blank">' . $var['username'] .'</a> <a href="' . $this->u_action . '&case=userid&username=' . $var['id'] .'">' . $image['search'] . '</a>',
					));
				}
				// We will now build all sessions for this user ... but no admin needs more then 1000 sessions so we limit them to a 1000
				// Better use cold storage for sessions older then 6 months or a year
				$sql = 'SELECT * FROM ' . $table_prefix . 'fingerprint WHERE fingerprint = \'' . $user_fp . '\' ORDER BY session_start DESC';
				$result = $db->sql_query_limit($sql, 1000, 0);
				while ($row = $db->sql_fetchrow($result))
				{
					$template->assign_block_vars('fingers', array(
						'USER_NAME'	=> '<a class="username-coloured" style="color: #'.(isset($users_array[$row['user_id']]['colour']) ? $users_array[$row['user_id']]['colour'] : '') . ';" href="' .append_sid($phpbb_root_path. 'memberlist.php?mode=viewprofile&u=' . $row['user_id']) . '" target="_blank">' . $users_array[$row['user_id']]['username'] .'</a>  <a href="' . $this->u_action . '&case=userid&username=' . $row['user_id'] .'">' . $image['search'] . '</a>',
						'SESSION_START'	=> $user->format_date($row['session_start'], 'd.m.Y, H:i'),
						'FINGERPRINT'	=> $row['fingerprint'],
						'SESSION_BROWSER'	=> $row['user_agent'],
					));
				}
				$db->sql_freeresult($result);
			break;
			default:
				$template->assign_vars(array(
					'S_STAGE'	=> 'search',
				));
		}
	}
}
