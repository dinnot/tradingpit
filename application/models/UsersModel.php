<?php
    class UsersModel extends CI_Model {
        
        const SALT = "gdIFDGION9-90sg0";
        
        private function encryptPassword($pwd) {
            return md5(md5($pwd.UsersModel::SALT));
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
            $user = $this->getUserBy(array("ukey"=>$key));
            if($user === false) {
                return false;
            } else {
                $access = $this->hasModuleAccess($user->usertype_id, $module);
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
                $key = md5($user->username.$user->id.random(1000000, 9999999));
                $ukey = $this->makeUkey($key);
                $this->db->update("users", array('ukey'=>$ukey), "id = {$user->id}");
                return $key;
            } else {
                return false;
            }
        }
    }
?>
