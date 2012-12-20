<?php
    class Trading_model extends CI_Model {
        
        public function updateBalances($user, $bank, $amount, $currency_pair, $rate) {
            $pair = $this->getCurrenciesByPair($currency_pair); 
            $sumrate = $amount * $rate;
            $this->db->set("amount", "amount + {$amount}", false)->set("sumrate", "sumrate + {$sumrate}", false)->where(array("users_id"=>$user, "ccy_pair"=>$currency_pair))->update("users_fx_positions");
            $query = $this->db->where(array("users_id"=>$user, "ccy_pair"=>$currency_pair))->get();
            $data = $query->row();
			if(($data->amount > 0 && $data->amount - $amount < 0) || ($data->amount < 0 && $data->amount - $amount > 0)) {
				$rest = $data->amount - 0;
				$pnlvol = ($data->sumrate - $rest * $rate) * -1;
				$this->db->set('amount', "amount + {$pnlvol}", false)->where(array('users_id'=>$user, 'currencies_id'=>$pair->currency1))->update('users_fx_pnl');
				$this->db->set("amount", $rest)->set("sumrate", $rest * $rate)->where(array("users_id"=>$user, "ccy_pair"=>$currency_pair))->update("users_fx_positions");
			} else if($data->sumrate > 0 && $data->sumrate - $sumrate < 0) {
				$prate = ($data->sumrate - $sumrate) / ($data->amount - $amount);
				$pnlvol = ($rate-$prate) * $amount * -1;
				$this->db->set('amount', "amount + {$pnlvol}", false)->where(array('users_id'=>$user, 'currencies_id'=>$pair->currency1))->update('users_fx_pnl');
				$this->db->set("sumrate", $data->amount * $prate)->where(array("users_id"=>$user, "ccy_pair"=>$currency_pair))->update("users_fx_positions");
			}
           
            //update bank's balance
            $this->db->set("amount", "amount + {$amount}", false)->where(array("banks_id"=>$bank, "currencies_id"=>$pair->currency0))->update("banks_balances");
            $this->db->set("amount", "amount - ".($amount * $rate), false)->where(array("banks_id"=>$bank, "currencies_id"=>$pair->currency1))->update("banks_balances");
        }
        
		public function getPnl($user, $game_settings) {
			$q = $this->db->where('users_id',$user)->get('users_fx_pnl');
			$val = array(); $ret = array();
			foreach($q->results as $row) {
				$val[$row->currencies_id] = $row->amount;
			}
			foreach($val as $c=>$a) {
				$ret[$c] = $a;
				foreach($val as $cz=>$az) {
					if($cz != $c) {
						$ret[$c] += $this->convertCurr($cz, $c, $az, $game_settings);
					}
				}
			}
			return $ret;
		}
		
		public function convertCurr($cr1, $cr2, $am, $game_settings) {
			if($cp = $this->getPair($cr1, $cr2) ) {
				return $am * $game_settings["bot_bprice{$cp->id}"]->value;
			} else if($cp = $this->getPair($cr2, $cr1)) {
				return $am / $game_settings["bot_bprice{$cp->id}"]->value;
			} else {
				return 0;
			}
		}
		
        public function createEnquiries($user, $bank, $number, $pair, $amount, $game_settings) {
            $pairs = $this->getPairs();
            $toadd = array();
            $query = $this->db->select("users.*, jobs.banks_id as bid, banks.name as bname, up.username as username_p, bp.name as bname_p")->from("users")->from("users up")->from("banks bp")->where("up.id", $user)->where("bp.id", $bank)->join("jobs", "users.jobs_id = jobs.id", "left")->join("banks", "banks.id = jobs.banks_id")->where(array("banks.id !="=>$bank, "users.last_trading >="=>(time()-60)))->order_by("id", "random")->limit($number)->get();
            if($query->num_rows() > 0) {
                foreach($query->result() as $row) {
                    $toadd[] = array("first_bank"=>$bank,
                                    "second_bank"=>$row->bid,
                                    "first_user"=>$user,
                                    "second_user"=>$row->id,
                                    "currency_pair"=>$pair,
                                    "amount"=>$amount,
                                    "time"=>time(),
                                    "status"=>1,
                                    "first_code"=>$row->username_p,
                                    "first_bname"=>$row->bname_p,
                                    "second_code"=>$row->username,
                                    "second_bname"=>$row->bname,
                                    "pair"=>$pairs[$pair]
                        );
                }
            }
            $number -= count($toadd);
            if($number > 0) { //add some bot banks
                $query2 = $this->db->select("users.*, jobs.banks_id as bid, banks.name as bname, up.username as username_p, bp.name as bname_p")->from("users")->from("users up")->from("banks bp")->where("up.id", $user)->where("bp.id", $bank)->join("jobs", "users.jobs_id = jobs.id", "left")->join("banks", "banks.id = jobs.banks_id")->where(array("banks.id !="=>$bank, "users.usertypes_id"=>6))->order_by("id", "random")->limit($number)->get();
                if($query2->num_rows() > 0) {
                    foreach($query2->result() as $row) {
                        $toadd[] = array("first_bank"=>$bank,
                                        "second_bank"=>$row->bid,
                                        "first_user"=>$user,
                                        "second_user"=>$row->id,
                                        "currency_pair"=>$pair,
                                        "amount"=>$amount,
                                        "time"=>time(),
                                        "price_buy"=>$game_settings["bot_bprice{$pair}"]->value,
                                        "price_sell"=>$game_settings["bot_sprice{$pair}"]->value,
                                        "status"=>2,
                                        "first_code"=>$row->username_p,
                                        "first_bname"=>$row->bname_p,
                                        "second_code"=>$row->username,
                                        "second_bname"=>$row->bname,
                                        "pair"=>$pairs[$pair]
                            );
                    }
                }
            }
            //add to db and make return
            $return = array();
            foreach($toadd as $add) {
                $this->db->set($add)->insert("enquiries");
                $return[] = $this->db->insert_id();
            }
            return $return;
        }
        
        public function respondEnquiry($id, $user, $buy, $sell) {
            $this->db->set(array("price_buy"=>$buy, "price_sell"=>$sell, "status"=>2))->where(array("id"=>$id, "second_user"=>$user))->update("enquiries");
            return 2;
        }
        
        public function buyEnquiry($id, $user) {
            $now = time();
            $query = $this->db->where(array("id"=>$id, "first_user"=>$user))->get("enquiries");
            if($query->num_rows() > 0 && $query->row()->status == 2) {
                $this->db->set(array("status"=>3, "time"=>$now))->where(array("id"=>$id, "first_user"=>$user))->update("enquiries");
                $deal = $query->row();
                $this->db->set(array(
                    "ccy_pair"=>$deal->currency_pair,
                    "amount_base_ccy"=>$deal->amount * 1000000,
                    "price"=>$deal->price_sell,
                    "counter_party"=>$deal->second_user,
                    "value_date"=>$now,
                    "trade_date"=>$now,
                    "user_id"=>$user,
					"type"=>1
                ))->insert("deals");
                $this->updateBalances($deal->first_user, $deal->first_bank, $deal->amount * 1000000, $deal->currency_pair, $deal->price_sell);
                $this->updateBalances($deal->second_user, $deal->second_bank, $deal->amount * -1000000, $deal->currency_pair, $deal->price_sell);
                return 3;
            } else {
                return false;
            }
        }
        
        public function sellEnquiry($id, $user) {
            $now = time();
            $query = $this->db->where(array("id"=>$id, "first_user"=>$user))->get("enquiries");
            if($query->num_rows() > 0 && $query->row()->status == 2) {
                $this->db->set(array("status"=>4, "time"=>$now))->where(array("id"=>$id, "first_user"=>$user))->update("enquiries");
                $deal = $query->row();
                $this->db->set(array(
                    "ccy_pair"=>$deal->currency_pair,
                    "amount_base_ccy"=>0-$deal->amount * 1000000,
                    "price"=>$deal->price_buy,
                    "counter_party"=>$deal->second_user,
                    "value_date"=>$now,
                    "trade_date"=>$now,
                    "user_id"=>$user,
					"type"=>1
                ))->insert("deals");
                $pair = $this->db->where("id", $deal->currency_pair)->get("currency_pairs");
                $pair = $pair->row();
                $curr1 = $pair->currency0;
                $curr2 = $pair->currency1;
                $this->updateBalances($deal->first_user, $deal->first_bank, $deal->amount * -1000000, $deal->currency_pair, $deal->price_buy);
                $this->updateBalances($deal->second_user, $deal->second_bank, $deal->amount * 1000000, $deal->currency_pair, $deal->price_buy);
                return 4;
            } else {
                return false;
            }
        }
        
        public function cancelEnquiry($id, $user, $reason = false) {
            //ignore reason for now...
            $this->db->set(array("status"=>0))->where("id", $id)->where("(second_user = {$user} OR first_user = {$user})")->update("enquiries");
            return true;
        } 
        
        public function statusEnquiry($user, $ids, $sts) {
            $query = $this->db->where_in("id", $ids)->where("(first_user = {$user} OR second_user = {$user})")->get("enquiries");
            $return = array();
            foreach($query->result() as $row) {
                if($row->status != $sts[$row->id]) {
                    $return[] = $row;
                }
            }
            return $return;
        }
        
        public function newEnquiries($user, $ids) {
            if($ids) {
                $this->db->where_not_in("id", $ids);
            }
            $query = $this->db->where(array("second_user"=>$user, "time >="=>(time() - 55), "status"=>1))->get("enquiries");
            $return = array();
            foreach($query->result() as $row) {
                    $return[] = $row;
            }
            return $return;
        }
        
        public function getCurrenciesByPair($pair) {
            $query = $this->db->where("id", $pair)->get("currency_pairs");
            return $query->row();
        }
        
        public function getPairs() {
            $query = $this->db->select("cp.id, c1.shortname as curr1, c2.shortname as curr2")->from("currency_pairs cp")->join("currencies c1", "cp.currency0 = c1.id", "left")->join("currencies c2", "cp.currency1 = c2.id", "left")->get();
            $ret = array();
            foreach($query->result() as $row) {
                $ret[$row->id] = $row->curr1."/".$row->curr2;
            }
            return $ret;
        }
		
		public function getPair($c1, $c2) {
			$q = $this->db->where(array('currency0'=>$c1, 'currency1'=>$c2))->get('currency_pairs');
			if($q->num_rows <= 0) {
				return false;
			} else {
				return $q->row();
			}
		}
    }
?>
