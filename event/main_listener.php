<?php
/**
*
* @package phpBB Session Admin
* @copyright (c) 2015 Lucifer
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace anavaro\trackntrace\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class main_listener implements EventSubscriberInterface
{
	static public function getSubscribedEvents()
	{
		return array(
			'core.login_box_redirect'	=> 'get_login',
		);
	}

	public function __construct(\phpbb\request\request $request, \phpbb\user $user, \phpbb\db\driver\driver_interface $db,
	$fingerprint_table)
	{
		$this->request = $request;
		$this->user = $user;
		$this->db = $db;
		$this->fingerprint_table = $fingerprint_table;
	}

	function get_login($event)
	{
		$cookie = $this->request->variable('phpbb_fp2', '', true, \phpbb\request\request_interface::COOKIE);
		if (!$event['admin'] && $cookie != '')
		{
			$sql_ary = array(
				'user_id' => $this->user->data['user_id'],
				'fingerprint' => $cookie,
				'session_start' => $this->user->data['session_start'],
				'user_agent' => $this->user->data['session_browser']
			);
			
			$sql = 'INSERT INTO ' . $this->fingerprint_table . ' ' . $this->db->sql_build_array('INSERT', $sql_ary);
			$this->db->sql_query($sql);
		}
	}
}
