          <div class="main-section-inside" id="pair_1">
            <div class="client-action-box sells">
              <div class="action-box-inside">
                <p>Client sells</p>
                <p class="amount" id="retail_sell_1"><?php print $amount[1]['sell'];?></p>
                <p>Bank buys</p>
              </div><!-- end action-box-inside -->
            </div><!-- end client-action-box sells -->

            <div class="retail-box">
              <p class="retail-box-title">Retail</p>
              <ul>
                <li class="prev-control"><a href="#" onClick=swap(2) ></a></li>
                <li>TER/RIK</li>
                <li class="next-control"><a href="#" onClick=swap(2)></a></li>
              </ul>
              <div class="retail-box-white-zone">

								<div class="top-values">
                  <span class="top-value left"><input type="text" value="<?php print $retail_rate[1]['sell_bf'] ?>" style="width:20px;" id="bf_sell_1"/></span>
                  <span class="top-value right"><input type="text" value="<?php print $retail_rate[1]['buy_bf'] ?>" style="width:20px;" id="bf_buy_1"/></span>
                </div><!-- end top-values -->

                <div class="middle-values">
                  <span class="middle-value left"><input type="text" value="<?php print $retail_rate[1]['sell_pips'] ?>" style="width:35px;margin-left:8px;" id="pips_sell_1"/></span>
                  <span class="middle-value right"><input type="text" value="<?php print $retail_rate[1]['buy_pips'] ?>" style="width:35px;margin-left:8px;" id="pips_buy_1" /></span>
                </div><!-- end middle-values -->
                <button onClick=retail_client.set_exchange_rate(1)>Send</button>
              </div><!-- end retail-box-white-zone -->

            </div><!-- end retail-box -->

            <div class="client-action-box buys">
              <div class="action-box-inside">
                <p>Client buys</p>
                <p class="amount" id="retail_buy_1"><?php print $amount[1]['buy'];?></p>
                <p>Bank sells</p>
              </div><!-- end action-box-inside -->
            </div><!-- end client-action-box buys -->

            <div class="widget client-ter">

              <div class="widget-title">
                <ul>
                <li class="first current"><a href="#">TER/RIK</a></li>             
              </ul>
              </div><!-- end widget-title -->

              <div class="widget-content">
                <p>TOTAL VOLUME</p>
                <p class="value" id="total_volume_1"><?php print $amount[1]['sell'] + $amount[1]['buy']; ?></p>
                <p>NET POSITION<span>FROM CLIENTS</span></p>
                <p class="value" id="net_position_1"><?php print -$amount[1]['sell'] + $amount[1]['buy']; ?></p>
                <p>Pnl</p>
                <p class="value">-</p>
              </div><!-- end widget-content -->

            </div><!-- end widget tier1 -->
          </div><!-- end main-section-inside -->

					<!-----------------------  second pair  ------------------------------------------------->

          <div class="main-section-inside" id="pair_2" hidden="hidden">
            <div class="client-action-box sells">
              <div class="action-box-inside">
                <p>Client sells</p>
                <p class="amount" id="retail_sell_2"><?php print $amount[2]['sell'];?></p>
                <p>Bank buys</p>
              </div><!-- end action-box-inside -->
            </div><!-- end client-action-box sells -->

            <div class="retail-box">
              <p class="retail-box-title">Retail</p>
              <ul>
                <li class="prev-control"><a href="#" onClick=swap(1)></a></li>
                <li>HAT/RIK</li>
                <li class="next-control"><a href="#" onClick=swap(1)></a></li>
              </ul>
              <div class="retail-box-white-zone">

								<div class="top-values">
                  <span class="top-value left"><input type="text" value="<?php print $retail_rate[2]['sell_bf'] ?>" style="width:20px;" id="bf_sell_2"/></span>
                  <span class="top-value right"><input type="text" value="<?php print $retail_rate[2]['buy_bf'] ?>" style="width:20px;" id="bf_buy_2"/></span>
                </div><!-- end top-values -->

                <div class="middle-values">
                  <span class="middle-value left"><input type="text" value="<?php print $retail_rate[2]['sell_pips'] ?>" style="width:35px;margin-left:8px;" id="pips_sell_2"/></span>
                  <span class="middle-value right"><input type="text" value="<?php print $retail_rate[2]['buy_pips'] ?>" style="width:35px;margin-left:8px;" id="pips_buy_2" /></span>
                </div><!-- end middle-values -->
                <button onClick=retail_client.set_exchange_rate(2)>Send</button>
              </div><!-- end retail-box-white-zone -->

            </div><!-- end retail-box -->

            <div class="client-action-box buys">
              <div class="action-box-inside">
                <p>Client buys</p>
                <p class="amount" id="retail_buy_2"><?php print $amount[2]['buy'];?></p>
                <p>Bank sells</p>
              </div><!-- end action-box-inside -->
            </div><!-- end client-action-box buys -->

            <div class="widget client-ter">

              <div class="widget-title">
                <ul>
                <li class="first current"><a href="#">HAT/RIK</a></li>
              </ul>
              </div><!-- end widget-title -->

              <div class="widget-content">
                <p>TOTAL VOLUME</p>
                <p class="value" id="total_volume_2"><?php print $amount[2]['sell'] + $amount[2]['buy']; ?></p>
                <p>NET POSITION<span>FROM CLIENTS</span></p>
                <p class="value" id="net_position_2"><?php print -$amount[2]['sell'] + $amount[2]['buy']; ?></p>
                <p>Pnl</p>
                <p class="value">-</p>
              </div><!-- end widget-content -->

            </div><!-- end widget tier1 -->
          </div><!-- end main-section-inside -->

