<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Messages_model extends CI_Model {
		
		public function __construct() {
			
    	parent::__construct();
		}
		
		public function make_new_conversation (&$message, $users) {
			if (isset($message['subject']) && $message['subject'] != '') {
				$title = $message['subject'];
				unset ($message['subject']);
			}
			else
				$title = substr($message['message'], 0, 20);
				
			$this->db->insert ("conversations", array ('last_activity'=>time(), 'title'=>$title));
			$conversation_id = $this->db->insert_id ();

			foreach ($users as $user)
				$this->db->insert ("users_has_conversations", array ('user_id'=>$user, 'conversations_id'=>$conversation_id));
			
			$message['user_id'] = $users[1];
			$message['conversations_id'] = $conversation_id;
			$this->add_message ($message);
			return $conversation_id;
		}
		
		public function add_message ($message) {
			$message['date'] = time ();
			$this->db->insert ('messages', $message);	
			$current_time = time ();
			$this->db->where ('id', $message['conversations_id']);
			$this->db->update ('conversations', array ('last_activity' => $message['date']));		
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
				
		public function get_conversations ($user_id, $last_activity) {
			$this->db->from ("users_has_conversations");
			$this->db->where ('user_id', $user_id);
			if ($last_activity != 0) 
				$this->db->where ('last_activity >=', $last_activity);
			
			$this->db->order_by ('last_activity', 'asc');
			$this->db->join ("conversations", "conversations.id = conversations_id", "left");
			$conversations = $this->db->get()->result_array ();
			
			return $conversations;
		}
		
		public function get_user_id ($username) {
			$query = $this->db->from ("users")->where ('username', $username)->get ();
			if ($query->num_rows () == 0)
				return 0;
			return $query->row()->id;
		}
		
		public function send_system_message ($user_id) {
						
		}
	}
	
?>

