<?php
    class Trading_model extends CI_Model {
        
        public function updateBalances($user, $bank, $amount, $currency) {
            $this->db->set("amount", "amount + {$amount}", false)->where(array("users_id"=>$user, "currencies_id"=>$currency))->update("users_fx_positions");
            $this->db->set("amount", "amount + {$amount}", false)->where(array("banks_id"=>$user, "currencies_id"=>$currency))->update("banks_balances");
        }
        
        public function createEnquiries($user, $bank, $number, $pair, $amount) {
            $toadd = array();
            $query = $this->db->select("users.*, jobs.banks_id as bid")->from("users")->join("jobs", "users.jobs_id = jobs.id", "left")->where(array("users.id !="=>$user, "users.last_trading >="=>(time()-60)))->order_by("id", "random")->limit(0, $number)->get();
            if($query->num_rows() > 0) {
                foreach($query->result() as $row) {
                    $toadd[] = array("first_bank"=>$bank,
                                    "second_bank"=>$row->bid,
                                    "first_user"=>$user,
                                    "second_user"=>$row->id,
                                    "currency_pair"=>$pair,
                                    "amount"=>$amount,
                                    "time"=>time(),
                                    "status"=>1
                        );
                }
            }
            //add bot banks
        }
        
        public function respondEnquiry($id, $user, $buy, $sell) {
            $this->db->set(array("price_buy"=>$buy, "price_sell"=>$sell, "status"=>2))->where(array("id"=>$id, "second_user"=>$user))->update("enquiries");
        }
        
        public function buyEnquiry($id, $user) {
            $now = time();
            $query = $this->db->where(array("id"=>$id, "first_user"=>$user))->get("enquiries");
            if($query->num_rows() > 0 && $query->row()->status == 2) {
                $this->db->set(array("status"=>3, "time"=>$now))->where(array("id"=>$id, "first_user"=>$user))->update("enquiries");
                $deal = $query->row();
                $this->db->set(array(
                    "ccy_pair"=>$deal->currency_pair,
                    "amount_base_ccy"=>$deal->amount,
                    "price"=>$deal->price_sell,
                    "counter_party"=>$deal->second_user,
                    "value_date"=>$now,
                    "trade_date"=>$now,
                    "user_id"=>$user
                ))->insert("fx_deals");
                $pair = $this->db->where("id", $deal->currency_pair)->get("currency_pairs");
                $pair = $pair->row();
                $curr1 = $pair->currency0;
                $curr2 = $pair->currency1;
                $this->updateBalances($deal->first_user, $deal->first_bank, $deal->amount, $curr1);
                $this->updateBalances($deal->first_user, $deal->first_bank, 0 - $deal->amount, $curr2);
                $this->updateBalances($deal->second_user, $deal->second_bank, $deal->amount, $curr2);
                $this->updateBalances($deal->second_user, $deal->second_bank, 0 - $deal->amount, $curr1);
            }
        }
        
        public function sellEnquiry($id, $user) {
            
        }
        
        public function cancelEnquiry($id, $user, $reason) {
            
        } 
        
        public function statusEnquiry($ids, $sts) {
            
        }
    }
?>
