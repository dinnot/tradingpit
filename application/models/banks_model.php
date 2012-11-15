<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

    class Banks_model extends CI_Model {
        
        const MAX_SHARES = 10000;
        
        public function __construct() {
            parent::__construct();
        }
        
        private function num_arab2roman($nr) {
            $values = array(1=>"I", 5=>"V", 10=>"X", 50=>"L", 100=>"C", 500=>"D", 1000=>"M");
            $result = "";
            $item = 1000;
            $div = 2;
            $xor = 2^5;
            while($nr > 0) {
                if($nr >= $item) {
                    $result .= $values[$item];
                    $nr -= $item;
                } else {
                    $item /= $div;
                    $div = $div ^ $xor;
                }
            }
            for($i = 1; $i < 1000; $i *= 10) {
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
        
        public function getClausesByContract($cid) {
            $clauses = array();
            $query = $this->db->where("contracts_id", $cid)->get("clausevalues");
            foreach($query->result_array() as $val) {
                $clauses[$val['clauses_id']]['vals'][] = array("name"=>$val['ord'], "value"=>$val['value']);
            }
            $query = $this->db->select("*")->from("contracts_has_clauses cc")->join("clauses c", "cc.clauses_id = c.id")->where("cc.contracts_id", $cid)->get();
            foreach($query->result_array() as $cls) {
                $clauses[$cls['id']]['text'] = $cls['info'];
                $clauses[$cls['id']]['name'] = $cls['tag'];
            }
            return $clauses;
        }
        
        public function getFullJob($id) {
            $query = $this->db->select("contracttypes.name as ctype, contracttypes.info as ctext, 
                contracts.start_date, contracts.end_date, contracts.signed_firstparty,
                contracts.signed_secondparty, banks.name as bname, banks.tag as btag,
                jobpositions.name as pname, jobs.contracts_id as cid, jobs.banks_id as bid")->
                    from("jobs")->
                    where("jobs.id", $id)->
                    join("jobpositions", "jobs.jobpositions_id = jobpositions.id", "left")->
                    join("contracts", "jobs.contracts_id = contracts.id", "left")->
                    join("contracttypes", "contracts.contracttypes_id = contracttypes.id", "left")->
                    join("banks", "jobs.banks_id = banks.id", "left")
                    ->get();
            $job = $query->row_array();
            $job['clauses'] = $this->getClausesByContract($job['cid']);
            return $job;
        }
        
        public function getFreeJob($user, $position) {
            $query = $this->db->where(array("available"=>"3", "jobpositions_id"=>$position))->get("jobs");
            if($query->num_rows() == 0) {
                return false;
            } else {
                $this->db->set(array('available'=>"0"))->where(array("id"=>$query->row()->id))->update("jobs");
                $this->db->set(array("jobs_id"=>$query->row()->id))->where(array("id"=>$user))->update("users");
                return $query->row()->id;
            }
        }
        
        public function createBotCeo($name, $bank) {
            $name = explode(" ", $name);
            $name = $name[0];
            $job = $this->createJob($bank, 2, array(), array(), 0);   //hardcoded -> needs to be changed!
            $data = array("username"=>$name."trader",
                        "email"=>$name."@bots.traderion.com",
                        "password"=>"imposible",
                        "usertypes_id"=>6, //hardcoded -> needs to be changed!
                        "jobs_id"=>$job,
                        "countries_id"=>2  //hardcoded -> needs to be changed!
                );
            $this->db->set($data)->insert("users");
            return $this->db->insert_id();
        }
        
        public function createBank($owners, $ceo = false) {
            $name = $this->generateBankName();
            $tag = $this->createTag($name);
            $this->db->set(array("name"=>$name, "tag"=>$tag))->insert("banks");
            $bank_id = $this->db->insert_id();
            $currencies = $this->db->get("currencies");
            $data = array();
            foreach($currencies->result() as $currency) {
                $amount = 0;
                if($currency->shortname == "RIK") {
                    $amount = 252000000;
                }
                $data[] = array("banks_id"=>$bank_id, "currencies_id"=>$currency->id, "amount"=>$amount);
            }
            $this->db->insert_batch("banks_balances", $data);
            $data_shares = array();
            foreach($owners as $player=>$percent) {
                $data_shares[] = array('users_id'=>$player, "banks_id"=>$bank_id, "amount"=>((int)($percent * Banks_model::MAX_SHARES)));
            }
            $this->db->insert_batch("shares", $data_shares);
            if($ceo === false) {
                $ceo = $this->createBotCeo($name, $bank_id);
            }
            return $bank_id;
        }
        
        public function createEmploymentContract($clauses, $clauses_values) {
            $type = $this->db->select("id")->where("name", "Employment contract")->get("contracttypes");
            $type = $type->row()->id;
            $this->db->set(array("contracttypes_id"=>$type))->insert("contracts");
            $contract_id = $this->db->insert_id();
            $data_clauses = array();
            $data_clausesvalues = array();
            foreach($clauses as $clause) {
                $data_clauses[] = array("contracts_id"=>$contract_id, "clauses_id"=>$clause);
                if(isset($clauses_values[$clause])) {
                    foreach($clauses_values[$clause] as $cvalue) {
                        list($ord, $value) = explode("=", $cvalue);
                        $data_clausesvalues[] = array("contracts_id"=>$contract_id, "clauses_id"=>$clause, "value"=>$value, "ord"=>$ord);
                    }
                }
            }
            if(count($data_clauses) > 0) {
                $this->db->insert_batch("contracts_has_clauses", $data_clauses);
            }
            if(count($data_clausesvalues) > 0) {
                $this->db->insert_batch("clausevalues", $data_clausesvalues);
            }
            return $contract_id;
        }
        
        public function createJob($bank, $position, $clauses, $clauses_values, $availability) {
            $contract = $this->createEmploymentContract($clauses, $clauses_values);
            $this->db->set(array("contracts_id"=>$contract, "banks_id"=>$bank, "jobpositions_id"=>$position, "available"=>$availability))->insert("jobs");
            return $this->db->insert_id();
        }
        
        public function signContract($cid) {
            $this->db->set(array("signed_secondparty"=>1))->where("id", $cid)->update("contracts");
        }
    }
?>
