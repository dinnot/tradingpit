<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Messages_model extends CI_Model {
		
		public function __construct() {
			
    	parent::__construct();
		}
		
		public function make_new_conversation (&$message, $users) {
			if (isset($message['title']) && $message['title'] != '') 
				$title = $message['title'];
			else
				$title = substr($message['message'], 0, 20);
				
			$this->insert ("conversations", array ('last_activity'=>time(), 'title'=>$title));
			$conversation_id = $this->db->last_id ();

			foreach ($users as $user)
				$this->insert ("users_has_conversations", array ('user_id'=>$user));
			
			return $conversation_id;
		}
		
		public function add_message ($message) {
			$message['date'] = time ();
			$this->db->insert ('messages', $message);			
		}
		
		public function get_messages ($where) {			
			$user_id = $this->user->id;
			
			$what = array ();
			$what[] = "message"; $what[] = "username";
			
			$this->db->from ("messages");
			$this->db->where ($where);
			if (isset($where['seen']) && $where['seen'] == 0)
				$this->db->where("user_id !=", $user_id);				
			$this->db->join ("users", "users.id = user_id");
			$messages = $this->db->get ()->result_array ();

			$where['seen'] = 0;			
			$this->db->where ($where)->where("user_id !=", $user_id)->update('messages', array ('seen'=>1));
			return $messages;
		}
				
		public function get_conversations ($user_id) {
			$this->db->from ("users_has_conversations");
			$this->db->where ('user_id', $user_id);
			$this->db->join ("conversations", "conversations.id = conversations_id", "left");
			$conversations = $this->db->get()->result_array ();
			
			return $conversations;
		}
		
		public function send_system_message ($user_id) {
						
		}
	}
	
?>

