<?php
    class Users_model extends CI_Model {
        
        const SALT = "gdIFDGION9-90sg0";
        const ERROR_USERNAME_EXISTS = 11;
        const ERROR_EMAIL_EXISTS = 12;
        
        private function encryptPassword($pwd) {
            return md5(md5($pwd.Users_model::SALT));
        }
        
        private function makeUkey($key) {
            return md5($key.$_SERVER['REMOTE_ADDR']);
        }
        
        function getUserBy($cond) {
            $this->db->select("*");
            $this->db->from("users");
            $this->db->where($cond);
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
               return $query->row(); 
            } else {
                return false;
            }
        }
        
        function getAuth($key, $module) {
            $ukey = $this->makeUkey($key);
            $user = $this->getUserBy(array("ukey"=>$ukey));
            if($user === false) {
                return false;
            } else {
                $access = $this->hasModuleAccess($user->usertypes_id, $module);
                if($access) {
                    return $user;
                } else {
                    return false;
                }
            }
        }
        
        function hasModuleAccess($usertype, $module) {
            $this->db->select("*");
            $this->db->from("usertypes_has_modules");
            $this->db->join("modules", "usertypes_has_modules.modules_id = modules.id");
            $this->db->where("usertypes_has_modules.usertypes_id", $usertype);
            $this->db->where("modules.name", $module);
            $query = $this->db->get();
            return $query->num_rows() > 0;
        }
        
        function getLogin($email, $password) {
            $user = $this->getUserBy(array("email"=>$email, "password"=>$this->encryptPassword($password)));
            if($user !== false) {
                $key = md5($user->username.$user->id.rand(1000000, 9999999));
                $ukey = $this->makeUkey($key);
                $this->db->update("users", array('ukey'=>$ukey), "id = {$user->id}");
                return $key;
            } else {
                return false;
            }
        }
        
        function getUserSettings() {
            $this->db->from("usersettings");
            $query = $this->db->get();
            $arr = array();
            foreach($query->result() as $row) {
                $arr[$row->name] = $row;
            }
            return $arr;
        }
        
        function getUserTypes() {
            $this->db->from("usertypes");
            $query = $this->db->get();
            $arr = array();
            foreach($query->result() as $row) {
                $arr[$row->name] = $row;
            }
            return $arr;
        }
        
        function updateSetting($user, $setting, $value) {
            $this->db->set(array("value"=>$value));
            $this->db->where(array("users_id"=>$user, "usersettings_id"=>$setting));
            $this->db->update("users_has_usersettings");
        }
        
        function createUser($email, $password, $username, $type, $country, $settings = false) {
            $data = array("email"=>$email,
                    "password"=>$this->encryptPassword($password),
                    "username"=>$username,
                    "usertypes_id"=>$type,
                    "countries_id"=>$country,
                    "confirm_email"=>md5($email.rand(100000, 999999)),
                );
            //check if email or username exists
            $this->db->select("email, username")->from("users")->where("email", $email)->or_where("username", $username);
            $query = $this->db->get();
            if($query->num_rows() > 0) {
                $row = $query->row();
                if($row->email == $email) {
                    return Users_model::ERROR_EMAIL_EXISTS;
                } else {
                    return Users_model::ERROR_USERNAME_EXISTS;
                }
            } else {
                //create the user and //?send verification email\\
                $this->db->set($data);
                $this->db->insert("users");
                $id = $this->db->insert_id();
                //add settings
                $batch = array();
                foreach($settings as $setting) {
                    $batch[] = array('usersettings_id'=>$setting['id'], 'value'=>$setting['value'], "users_id"=>$id);
                }
                $this->db->insert_batch('users_has_usersettings', $batch);
                //login
                return $this->getLogin($email, $password);
            }
        }
    }
?>
