          <div class="sidebar">
            <div class="widget rik light-bg">

              <div class="widget-title">
                <h2>TER/RIK</h2>
              </div><!-- end widget-title -->

              <div class="widget-content">
               
               <div class="info-box">
                <ul id="1_bf">
                  <li>-</li>
                  <li>/</li>
                  <!--<li><span>35/65</span></li>-->
                  <li>-</li>
                </ul>
                <div class="middle-bar">
                  <span id="1_pips">-/-</span>
                  <div class="left-box" onclick=display_form(1,'buy')></div>
                  <div class="right-box" onclick=display_form(1,'sell')></div>
                </div><!-- end middle-bar -->
                <div class="second-info-box">
                  <span id="1_amount">- / -</span>
                </div><!-- end second-info-box -->

               </div><!-- end info-box -->

               <div class="evolution">
                 <span id="best_1">-</span>
               </div><!-- end evolution -->

               <div class="box-actions">
                <ul>
                 <!-- <li class="first"><a href="#">hold</a></li> -->
                  <li><a href="#" onclick=ebroker.cancel_users_prices(1)>cancel</a></li>
                </ul>
               </div><!-- end box-actions -->

              </div><!-- end widget-content -->

            </div><!-- end widget rik -->

            <div class="widget-separator">
            </div><!-- end widget-separator -->

            <div class="widget rik dark-bg">

              <div class="widget-title">
                <h2>HAT/RIK</h2>
              </div><!-- end widget-title -->

              <div class="widget-content">
               
               <div class="info-box">
                <ul id="2_bf">
                  <li>-</li>
                  <li>/</li>
                  <li>-</li>
                </ul>
                <div class="middle-bar">
                  <span id="2_pips">-/-</span>
                  <div class="left-box" onclick=display_form(2,'buy')></div>
                  <div class="right-box" onclick=display_form(2,'sell')></div>
                </div><!-- end middle-bar -->
                <div class="second-info-box">
                  <span id="2_amount">- / -</span>
                </div><!-- end second-info-box -->

               </div><!-- end info-box -->

               <div class="evolution">
                 <span id="best_2">-</span>
               </div><!-- end evolution -->

               <div class="box-actions">
                <ul>
                  <!-- <li class="first"><a href="#">hold</a></li> -->
                  <li><a href="#" onclick=ebroker.cancel_users_prices(2)>cancel</a></li>
                </ul>
               </div><!-- end box-actions -->

              </div><!-- end widget-content -->

            </div><!-- end widget rik -->

          </div><!-- end sidebar -->

