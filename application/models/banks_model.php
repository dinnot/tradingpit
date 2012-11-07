<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

    class Banks_model extends CI_Model {
        
        public function __construct() {
            parent::__construct();
        }
        
        private function num_arab2roman($nr) {
            $values = array(1=>"I", 5=>"V", 10=>"X", 50=>"L", 100=>"C", 500=>"D", 1000=>"M");
            $result = "";
            $item = 1000;
            $div = 2;
            $xor = 2^5;
            while($nr >= 0) {
                if($nr >= $item) {
                    $result .= $values[$item];
                    $nr -= $item;
                } else {
                    $item /= $div;
                    $div = $div ^ $xor;
                }
            }
            for($i = 1; $i <= 1000; $i *= 10) {
                $fst = $values[$i];
                $snd = $values[$i*5];
                $trd = $values[$i*10];
                $str = $fst.$fst.$fst.$fst;
                $result = str_replace($snd.$str, $fst.$trd, $result);
                $result = str_replace($str, $fst.$snd, $result);
            }
            
            return $result;
        }
        
        private function num_roman2arab($nr) {
            $values = array("CM"=>900, "CD"=>400, "XC"=>90, "XL"=> 40, "IX"=>9, "IV"=>4, "M"=>1000, "D"=>500, "C"=>100, "L"=>50, "X"=>10, "V"=>5, "I"=>1);
            $result = 0;
            while(strlen($nr) > 0) {
                foreach($values as $r=>$a) {
                    if(strpos($nr, $r) === 0) {
                        $result += $a;
                        $nr = substr($nr, strlen($r));
                    }
                }
            }
            return $result;
        }
        
        private function createTag($name) {
            $result = "";
            $names = explode(" ", $name);
            if(strlen($names[0]) > 4) {
                $result .= substr($names[0], 0, 4);
                $rest = substr($names[0], 5);
                $rest = $this->num_roman2arab($rest);
                $fst = ord('g');
                $snd = ord('b');
                $fst += (int)($rest / 26) - ord('a');
                $snd += ($rest % 26) - ord('a');
                $fst = chr(($fst % 26) + ord('a'));
                $snd = chr(($snd % 26) + ord('a'));
                $result .= $fst.$snd;
            } else {
                while(strlen($names[0]) < 4) {
                    $names[0] .= 'O';
                }
                $result .= $names[0]."GB";
            }
            return $result;
        }
        
        private function generateBankName() {
            $query = $this->db->get("banks");
            $nr = $query->num_rows()+1;
            $nr = $this->num_arab2roman($nr);
            return $nr." General Bank";
        }
        
        public function getFreeJob($user, $position) {
            $query = $this->db->where(array("available"=>"3", "jobpositions_id"=>$position))->get("jobs");
            if($query->num_rows() == 0) {
                return false;
            } else {
                $this->db->set(array('available'=>"0"))->where(array("id"=>$id))->update("jobs");
                $this->db->set(array("jobs_id"=>$id))->where(array("id"=>$user))->update("users");
                return $query->row()->id;
            }
        }
        
        public function createBank() {
            $name = $this->generateBankName();
            $tag = $this->createTag($name);
            $this->db->set(array("name"=>$name, "tag"=>$tag))->insert("banks");
            $bank_id = $this->db->insert_id();
            $currencies = $this->db->get("currencies");
            $data = array();
            foreach($currescies->results() as $currency) {
                $data[] = array("banks_id"=>$bank_id, "currencies_id"=>$currency->id, "amount"=>0);
            }
            $this->db->insert_batch("banks_balances", $data);
            return $bank_id;
        }
        
        public function createEmploymentContract($clauses, $clauses_values) {
            $type = $this->db->select("id")->where("name", "Employment contract")->get("contracttypes");
            $type = $type->row()->id;
            $this->db->set(array("contracttypes_id"=>$type))->insert("contracts");
            $contract_id = $this->db->insert_id();
            foreach($clauses as $clause) {
                $data_clauses[] = array("contracts_id"=>$contract_id, "clauses_id"=>$clause);
                if(isset($clauses_values[$clause])) {
                    foreach($clauses_values[$clause] as $cvalue) {
                        list($value, $ord) = explode("=", $cvalue);
                        $data_clausesvalues[] = array("contracts_id"=>$contract_id, "clauses_id"=>$clause, "value"=>$value, "ord"=>$ord);
                    }
                }
            }
            $this->db->insert_batch("contracts_has_clauses", $data_clauses);
            $this->db->insert_batch("clasevalues", $data_clausesvalues);
            return $contract_id;
        }
    }
?>
