<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

    class Game_model extends CI_Model {
        
        public function __construct() {
            parent::__construct();
            $this->fetched = false;
            $this->settings = array();
        }
        
        private function fetchSettings() {
            $query = $this->db->get("gamesettings");
            foreach($query->result() as $row) {
                $this->settings[$row->name] = $row;
            }
            $this->fetched = true;
        }
        
        function getSetting($name) {
            if(!$this->fetched) {
                $this->fetchSettings();
            }
            if(isset($this->settings[$name])) {
                return $this->settings[$name];
            } else {
                return false;
            }
        }
        
        function getSettingValue($name) {
            $rt =  $this->getSetting($name);
            return $rt->value;
        }
        
        function getAllSettings() {
            if(!$this->fetched) {
                $this->fetchSettings();
            }
            return $this->settings;
        }
        
        function setSetting($name, $value) {
            $this->settings[$name]->value = $value;
            $this->db->set(array("value"=>$value, "name"=>$name));
            if($this->getSetting($name) !== false) {
                $this->db->where("name", $name)->update("gamesettings");
            } else {
                $this->db->insert("gamesettings");
                $this->settings[$name]->id = $this->db->insert_id();
                $this->settings[$name]->name = $name;
            }
        }
    }
?>
