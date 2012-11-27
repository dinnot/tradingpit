<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />

  <title>Trading Page - Trading Pit</title>
   <meta name="description" content="" />
  <link rel="stylesheet" href="<?php print base_url () ?>/css/style.css" />
  <link href="<?php print base_url () ?>/css/jquery-ui-1.9.1.custom.css" rel="stylesheet">
  <style>
      .lnk:hover {
          cursor: hand;
          cursor: pointer;
      }
  </style>
  <script src="<?php print base_url () ?>/js/jquery-1.8.2.min.js"></script>
  <script src="<?php print base_url () ?>/js/jquery-ui-1.9.1.custom.js"></script>
  <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
  <script>
  $(function() {
    $( "#datepickerStart, #datepickerEnd" ).datepicker({
      showOn: "button",
      buttonImage: "images/calendar-icon.png",
      buttonImageOnly: true
    });
  });
  </script>
  <script>
    $(document).ready(function() {
      $('.table-container tbody tr').click(function() {
        $('.table-container tbody tr').removeClass('active');
        $(this).addClass('active');
      })
    })
  </script>
 
</head>

<body>
  <header>
      <article class="top-section container">
        <section class="top-header">
        </section><!-- end top-header -->
        <section class="navigation-bar">
					<?php include_once ($_SERVER['DOCUMENT_ROOT']."/application/views/menu.php"); ?>
          <span class="nav-bar-bottom-bg"></span>
        </section><!-- end navigation-bar -->

        </article><!-- end top-section container -->

      </header>

    <article class="container">

      <article class="main-content">

        <section class="top-main">
          <div class="spot-positions">
            <ul>
              <li class="first green-gradient"><span>SPOT POSITIONS</span></li>
              <li class="second"><span class="light-green">TER/RIK</span> 7.000.000<span class="gray">@3.9210 </span></li>
              <li><span class="green">HAT/RIK</span> -5.000.000<span class="gray">@4.1180 </span></li>
              <li class="last"><span class="green">HAT/TER</span>0<span class="gray">@1.0490 </span></li>
            </ul>
          </div><!-- end spot-positions -->
        
        </section><!-- end top-main -->

        <section class="main-section incoming-calls">

          <div class="section-title">
            <h2><span class="down orange-triangle"></span>Incoming calls</h2>
          </div><!-- end section-title -->

          <div class="main-section-content">

            <div class="table-container orange">
              <table>
                <thead>
                  <tr>
                    <th class="first">Code</th>
                    <th>Full name</th>
                    <th>Pair</th>
                    <th>Amt</th>
                    <th>Period</th>
                    <th>BF</th>
                    <th>BID</th>
                    <th>ASK</th>
                    <th class="last action">Action</th>

                  </tr>
                </thead>
                
                <tbody id="incoming">

                  
                </tbody>
              </table>
            </div><!-- end table-container orange -->

          </div><!-- end main-section-content --> 

          <div class="sidebar">
            <div class="widget calls-queue">

              <div class="widget-title">
                <h2>Calls Queue<span class="counter" id="queue_number">0</span></h2>
              </div><!-- end widget-title -->

              <div class="widget-content">
                <div class="widget-table-container">
                   <table>
                      <thead>
                        <tr>
                          <th>bank</th>
                          <th>ccy(pair)</th>
                          <th>amt</th>
                          <th>Period</th>
                        </tr>
                      </thead>

                      <tbody id="queue">

                      </tbody>
                    </table>
                </div><!-- end widget-table-container -->

              </div><!-- end widget-content -->

            </div><!-- end widget calls-queue -->

          </div><!-- end sidebar -->

        </section><!-- end main-section -->

        <section class="main-section">

          <div class="section-title">
            <h2><span class="up blue-triangle"></span>Outgoing calls</h2>
          </div><!-- end section-title -->


          <div class="main-section-content">
              <form id="call_form">
            <div class="call-top-bar">
              <ul>
                <li>
                  <select>
                    <option>FX</option>
                  </select>
                </li>

                <li>
                  <select id="f_pair">
                    <?php
                    foreach($pairs as $id=>$val) {
                        echo "<option value='{$id}'>{$val}</option>";
                    }
                    ?>
                </select>
                </li>

                <li>
                  <select id="f_amount">
                    <?php
                    foreach($amounts as $val) {
                        echo "<option value='{$val}'>{$val}</option>";
                    }
                    ?>
                </select>
                </li>

                <li>
                  <select>
                    <option>SPOT</option>
                  </select>
                </li>

                <li>
                  <select>
                    <option>Default</option>
                  </select>
                </li>
              </ul>
              <div class="call-blue-button">
                <a href="#" onclick="return submitform();"><span class="blue-arrow"></span>CALL</a>
              </div>
            </div><!-- end call-top-bar -->
            </form>
            <div class="table-container dark-blue">
              <table>
                <thead>
                  <tr>
                    <th class="first">Code</th>
                    <th>Full name</th>
                    <th>Pair</th>
                    <th>Amt</th>
                    <th>Period</th>
                    <th class="last quote" colspan="4">Quote</th>
                  </tr>
                </thead>

                <tbody id="outgoing">

                </tbody>
              </table>
            </div><!-- end table-container orange -->

            <div class="table-container black">
              <table>
                <thead>
                  <tr>
                    <th class="first" colspan="9">Matching deals (0)</th>
                  </tr>
                </thead>

                <tbody>

                  <tr class="first">
                    <td>BFBZ</td>
                    <td>BRING FORTH</td>
                    <td>SELL</td>
                    <td>TER/RIK</td>
                    <td>2</td>
                    <td>SPOT</td>
                    <td>3.9265</td>
                    <td></td>
                    <td>CANCEL</td>
                  </tr>

                  <tr>
                    <td>BFBZ</td>
                    <td>BRING FORTH</td>
                    <td>SELL</td>
                    <td>TER/RIK</td>
                    <td>3</td>
                    <td>SPOT</td>
                    <td>3.9260</td>
                    <td class="light-blue">KART</td>
                    <td>DONE</td>
                  </tr>
                  <tr class="last">
                    <td>BFBZ</td>
                    <td>BRING FORTH</td>
                    <td>SELL</td>
                    <td>TER/RIK</td>
                    <td>3</td>
                    <td>SPOT</td>
                    <td>3.9260</td>
                    <td class="light-blue"></td>
                    <td>DONE</td>
                  </tr>

                </tbody>
              </table>
              <div class="on-hold-overlay">
                <span><strong>ON HOLD</strong></span>
              </div><!-- end on-hold-overlay -->
            </div><!-- end table-container black -->

          </div><!-- end main-section-content --> 

          <div class="sidebar">
            <div class="widget rik light-bg">

              <div class="widget-title">
                <h2>TER/RIK</h2>
              </div><!-- end widget-title -->

              <div class="widget-content">
               
               <div class="info-box">
                <ul>
                  <li>3.92</li>
                  <li><span>35/65</span></li>
                  <li>3.92</li>
                </ul>
                <div class="middle-bar">
                  <span>30/75</span>
                  <div class="left-box"></div>
                  <div class="right-box"></div>
                </div><!-- end middle-bar -->
                <div class="second-info-box">
                  <span>1 (2X2) 2</span>
                </div><!-- end second-info-box -->

               </div><!-- end info-box -->

               <div class="evolution">
                 <span>3.9210 P<span class="evolution-arrow green"></span></span>
               </div><!-- end evolution -->

               <div class="box-actions">
                <ul>
                  <li class="first"><a href="#">hold</a></li>
                  <li><a href="#">cancel</a></li>
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
                <ul>
                  <li>3.92</li>
                  <li><span>35/65</span></li>
                  <li>3.92</li>
                </ul>
                <div class="middle-bar">
                  <span>30/75</span>
                  <div class="left-box"></div>
                  <div class="right-box"></div>
                </div><!-- end middle-bar -->
                <div class="second-info-box">
                  <span>1 (2X2) 2</span>
                </div><!-- end second-info-box -->

               </div><!-- end info-box -->

               <div class="evolution">
                 <span>4.1110 G<span class="evolution-arrow red"></span></span>
               </div><!-- end evolution -->

               <div class="box-actions">
                <ul>
                  <li class="first"><a href="#">hold</a></li>
                  <li><a href="#">cancel</a></li>
                </ul>
               </div><!-- end box-actions -->

              </div><!-- end widget-content -->

            </div><!-- end widget rik -->

          </div><!-- end sidebar -->

        </section><!-- end main-section -->

        <section class="main-section darker-bg">

          <div class="section-title">
            <h2><span class="green-square"></span>Positions</h2>
          </div><!-- end section-title -->

          <div class="main-section-content">

            <div class="ticket-conv-tabs">
              <ul>
                <li class="current"><a href="#">Ticket/Conv</a></li>
                <li><a href="#">Filters</a></li>
              </ul>
            </div><!-- end ticket-conv-tabs -->

            <div class="table-container ticket-conv first">
              <table>
                <thead>
                  <tr>
                    <th class="first">Code</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>User</th>
                    <th>Swift</th>
                    <th>Period</th>
                    <th>Deal</th>
                    <th>Rate</th>
                    <th>Volume</th>
                    <th>Status</th>
                    <th>Maker</th>
                    <th class="last">Taker</th>
                  </tr>
                </thead>

                <tbody>

                  <tr class="first">
                    <td>BFBZ</td>
                    <td>05 JUL</td>
                    <td>13:33</td>
                    <td>FLRN</td>
                    <td>HAT/RIK</td>
                    <td>SPOT</td>
                    <td>SELL</td>
                    <td>4.1180</td>
                    <td>2MIO</td>
                    <td>123456</td>
                    <td>BFBZ</td>
                    <td>AATK</td>
                  </tr>

                  <tr class="last">
                    <td>HSAN</td>
                    <td>05 JUL</td>
                    <td>13:11</td>
                    <td>FLRN</td>
                    <td>TER/RIK</td>
                    <td>SPOT</td>
                    <td>BUY</td>
                    <td>3.9245</td>
                    <td>2MIO</td>
                    <td>EB1234</td>
                    <td>AATK</td>
                    <td>HSAN</td>
                  </tr>

                </tbody>
              </table>
            </div><!-- end table-container ticket-conv first -->

            <div class="table-container ticket-conv second">
              <div class="table-container-title">
                <h2>FX OPEN POSITIONS</h2>
              </div><!-- end table-container-title -->
              <table>
                <thead>
                  <tr>
                    <th class="first">CCY</th>
                    <th class="dark-bg">Open Amount<br><span>Currency</span></th>
                    <th>Open Amount<br><span>Reporting currency</span></th>
                    <th class="dark-bg">Position Limit</th>
                    <th>Rate</th>
                    <th class="last">RISK</th>
                  </tr>

                  <tr class="second-row">
                    <th class="first" colspan="6">
                      <ul>
                        <li class="green"><a href="#">TER</a></li>
                        <li><a href="#">HAT</a></li>
                        <li class="last"><a href="#">RIK</a></li>
                      </ul>
                    </th>
                  </tr>
                </thead>

                <tbody>

                  <tr class="first even">
                    <td>TER</td>
                    <td class="dark-bg">-7,000,000</td>
                    <td>-7,000,000</td>
                    <td class="dark-bg">5,040,000</td>
                    <td>3.9210</td>
                    <td>BREAK</td>
                  </tr>

                  <tr class="odd">
                    <td>HAT</td>
                    <td class="dark-bg">-5,000,000</td>
                    <td>-7,000,000</td>
                    <td class="dark-bg">5,040,000</td>
                    <td>3.9210</td>
                    <td>IN LIMIT</td>
                  </tr>

                  <tr class="even">
                    <td>TER</td>
                    <td class="dark-bg">-7,000,000</td>
                    <td>-7,000,000</td>
                    <td class="dark-bg">5,040,000</td>
                    <td>3.9210</td>
                    <td>IN LIMIT</td>
                  </tr>

                  <tr class="odd">
                    <td>TER</td>
                    <td class="dark-bg">-7,000,000</td>
                    <td>-7,000,000</td>
                    <td class="dark-bg">5,040,000</td>
                    <td>3.9210</td>
                    <td>IN LIMIT</td>
                  </tr>

                  <tr class="even agg">
                    <td>AGG</td>
                    <td class="dark-bg">-7,000,000</td>
                    <td>-7,000,000</td>
                    <td class="dark-bg">5,040,000</td>
                    <td>N/A</td>
                    <td>BREAK</td>
                  </tr>

                </tbody>
              </table>
            </div><!-- end table-container ticket-conv first -->

          </div><!-- end main-section-content --> 

          <div class="sidebar">
            <div class="widget tier1">

              <div class="widget-title">
                <h2>TIER 1<span>(Last update 03:20 AM)</span></h2>
              </div><!-- end widget-title -->

              <div class="widget-content">
                <div class="widget-tier1-table">
                  <table>
                    <tbody>
                      <tr>
                        <td>OWN CAPITAL</td>
                        <td>TER</td>
                        <td class="last">63,000,000</td>
                      </tr>
                      <tr class="last">
                        <td>OWN FUNDS</td>
                        <td>TER</td>
                        <td class="last">63,000,000</td>
                      </tr>
                    </tbody>
                  </table>
                </div><!-- end widget-tier1-table -->

              </div><!-- end widget-content -->

            </div><!-- end widget tier1 -->

            <div class="widget-separator-two"></div>

             <div class="widget acc-central-bank">

              <div class="widget-title">
                <h2>Accounts with the <span>Central bank</span></h2>
              </div><!-- end widget-title -->

              <div class="widget-content">
                <div class="widget-acc-central-bank-table">
                  <table>
                    <tbody>
                      <tr class="even">
                        <td>TER</td>
                        <td class="second">56,000,000</td>
                        <td></td>
                      </tr>

                      <tr class="odd">
                        <td class="green-td">HAT</td>
                        <td class="second">-5,000,000</td>
                        <td class="overdraft">OVERDRAFT</td>
                      </tr>

                      <tr class="even">
                        <td>RIK</td>
                        <td class="second">47,990,000</td>
                        <td></td>
                      </tr>
                    </tbody>
                  </table>
                </div><!-- end widget-acc-central-bank-table -->

              </div><!-- end widget-content -->

            </div><!-- end widget acc-central-bank -->

          </div><!-- end sidebar -->

        </section><!-- end main-section -->

      </article><!-- end main-content -->

    </article><!-- end container -->

</body>
</html>


<script type="text/javascript">
    
//helper functions
function calcBf(nr) {
    return nr.substring(0, nr.indexOf(".")+3);
}

function calcPips(nr) {
    return nr.substring(nr.indexOf(".")+3, nr.indexOf(".")+5);
}

function setQueueNumber() {
    $("#queue_number").text(queue_number);
}

//real shit
var user = <?=$user?>;
var list = new Array();
var cnt = 0;
var queue_number = 0;

function deleteEnq(id) {
    console.log("delete "+id);
    var i;
    for(i = 0; i < cnt; i++) {
       if(list[i].getId() == id) {
           list[i].del();
           var j;
           for(j = i; j < cnt - 1; j++) {
               list[j] = list[j+1];
           }
           cnt--;
           return;
       }
    }
}

function toutEnq(idv) {
    var i;
    for(i = 0; i < cnt; i++) {
       if(list[i].getId() == idv) {
           if(list[i].getStatus() < 2) {
               cancelEnq(idv);
           }
           return;
       }
    }
}

function incEnq(idv) {
    var i;
    for(i = 0; i < cnt; i++) {
       if(list[i].getId() == idv) {
           list[i].inc();
           queue_number--;
           setQueueNumber();
           return;
       }
    }
}

function trnEnq(idv) {
    for(i = 0; i < cnt; i++) {
       if(list[i].getId() == idv) {
           list[i].setInAction(true);
           break;
       }
    }
    
    $("#enq"+idv+"_bf").attr("disabled", "disabled");
    $("#enq"+idv+"_buy").attr("disabled", "disabled");
    $("#enq"+idv+"_sell").attr("disabled", "disabled");
    $("#enq"+idv+"_trn").html("...");
    $("#enq"+idv+"_trn").click(function() {return false;});
    var bf = $("#enq"+idv+"_bf").val();
    var pbuy = parseInt($("#enq"+idv+"_buy").val());
    var psell = parseInt($("#enq"+idv+"_sell").val());
    bf = parseFloat(bf);
    if(psell < pbuy) {
        var t = bf+1;
        psell = t+ psell / 10000;
    } else {
        psell = bf + psell / 10000;
    }
    
    pbuy = bf + pbuy / 10000;
    
    $.ajax({
        type: "POST",
        data: {id: idv, buy: pbuy, sell: psell},
        url: "<?php print base_url () ?>trading/trading/respond/",
        cache: false
    }).done(function(data) {
        data = jQuery.parseJSON(data);
        if(!data.error) {
            var vhtm = "RISK";
            setTimeout(function(){
                $("#enq"+idv+"_trn").html(vhtm);
                $("#enq"+idv+"_trn").click(function(){cancelEnq(idv)});;
                }, 15000);
        } else {
            $("#enq"+idv+" td").css("background-color", "yellow");
        }
        for(i = 0; i < cnt; i++) {
           if(list[i].getId() == idv) {
               list[i].setInAction(false);
               break;
           }
        }
    });
}

function buyEnq(idv) {
    var i;
    for(i = 0; i < cnt; i++) {
       if(list[i].getId() == idv) {
           list[i].setInAction(true);
           break;
       }
    }
    $.ajax({
        type: "POST",
        data: {id: idv},
        url: "<?php print base_url () ?>trading/trading/buy/",
        cache: false
    }).done(function(data) {
        data = jQuery.parseJSON(data);
        if(!data.error) {
            $("#enq"+idv+" td").css("background-color", "#B9DFBC");
            setTimeout(function(){deleteEnq(idv)}, 5000);
        } else {
            for(i = 0; i < cnt; i++) {
               if(list[i].getId() == idv) {
                   list[i].setInAction(false);
                   break;
               }
            }
        }
    });
}

function sellEnq(idv) {
    var i;
    for(i = 0; i < cnt; i++) {
       if(list[i].getId() == idv) {
           list[i].setInAction(true);
           break;
       }
    }
    $.ajax({
        type: "POST",
        data: {id: idv},
        url: "<?php print base_url () ?>trading/trading/sell/",
        cache: false
    }).done(function(data) {
        data = jQuery.parseJSON(data);
        if(!data.error) {
            $("#enq"+idv+" td").css("background-color", "#E09F9F");
            setTimeout(function(){deleteEnq(idv)}, 5000);
        } else {
            for(i = 0; i < cnt; i++) {
               if(list[i].getId() == idv) {
                   list[i].setInAction(false);
                   break;
               }
            }
        }
    });
}

function cancelEnq(idv) {
    var i;
    for(i = 0; i < cnt; i++) {
       if(list[i].getId() == idv) {
           list[i].setInAction(true);
           break;
       }
    }
    $.ajax({
        type: "POST",
        data: {id: idv},
        url: "<?php print base_url () ?>trading/trading/cancel/",
        cache: false
    }).done(function(data) {
        data = jQuery.parseJSON(data);
        if(!data.error) {
            $("#enq"+idv+" td").css("background-color", "black");
            setTimeout(function(){deleteEnq(idv)}, 5000);
        } else {
            for(i = 0; i < cnt; i++) {
               if(list[i].getId() == idv) {
                   list[i].setInAction(false);
                   break;
               }
            }
        }
    });
}

function enquiry() {
    this.status = -1;
    this.where = "queue";
    this.loaded = false;
    this.in_action = false;
    this.data = new Object();
    this.deleted = 0;
    
    this.init = function(id, sts, where) {
        this.data.id = id;
        this.data.status = sts;
        this.where = where
    }
    
    this.setInAction = function(val) {
        this.in_action = val;
    }
    
    this.del = function() {
        console.log("remove "+this.data.id);
        $("#enq"+this.data.id).remove();
    }
    
    this.getId = function() {
        return this.data.id;
    }
    
    this.getStatus = function() {
        return this.data.status;
    }
    
    this.setData = function(d) {
        this.data = d;
    }
    
    this.recalc = function() {
        if(this.in_action) {
            return;
        }
        var new_sts = this.data.status;
        if(this.where == "outgoing") {
            if(new_sts == 1) {
                var html = "";
                html += "<td>"+this.data.second_code+"</td>";
                html += "<td>"+this.data.second_bname+"</td>";
                html += "<td>"+this.data.pair+"</td>";
                html += "<td>"+this.data.amount+"</td>";
                html += "<td>SPOT</td>";
                html += "<td colspan=3>waiting...</td>";
                $("#enq"+this.data.id).html(html);
            } else if(new_sts == 2) {
                var html = "";
                html += "<td>"+this.data.second_code+"</td>";
                html += "<td>"+this.data.second_bname+"</td>";
                html += "<td>"+this.data.pair+"</td>";
                html += "<td>"+this.data.amount+"</td>";
                html += "<td>SPOT</td>";
                html += "<td class='dark-silver-bg one' colspan='2'><button onclick='buyEnq("+this.data.id+")'>"+calcPips(this.data.price_buy)+"</button>";
                html += "<span class='decimal-indicator'><span>"+calcBf(this.data.price_buy)+"</span></td>";
                html += "<td class='dark-silver-bg two'><button onclick='sellEnq("+this.data.id+")'>"+calcPips(this.data.price_sell)+"</button></td>";
                html += "<td class='quote-td lnk' onclick='cancelEnq("+this.data.id+")'>Nothing there</td>";
                $("#enq"+this.data.id).html(html);
            }
        } else if(this.where == "incoming") {
            var dis = this;
            if(new_sts == 3) {
                $("#enq"+this.data.id+"_trn").html("");
                $("#enq"+this.data.id+" td").css("background-color", "#E09F9F");
                setTimeout(function(){deleteEnq(dis.data.id)}, 5000);
            } else if(new_sts == 4) {
                $("#enq"+this.data.id+"_trn").html("");
                $("#enq"+this.data.id+" td").css("background-color", "#B9DFBC");
                setTimeout(function(){deleteEnq(dis.data.id)}, 5000);
            }
        }
    }
    
    this.calc = function() {
        if(this.where == "outgoing") {
            if(this.data.status == -1) {
                var html = "<tr id='enq"+this.data.id+"'>";
                html += "<td colspan=8>Loading...</td>";
                html += "</tr>";
                if($('#outgoing tr:last').length) {
                    $('#outgoing tr:last').after(html);
                } else {
                    $('#outgoing').html(html);
                }
            }
        } else if(this.where == "queue") {
            if(this.data.status == 1) {
                var html = "<tr class='lnk' onclick='incEnq("+this.data.id+")' id='enq"+this.data.id+"'>";
                html += "<td>"+this.data.first_bname+"</td>";
                html += "<td class='dark-silver-bg'>"+this.data.pair+"</td>";
                html += "<td class='bold'>"+this.data.amount+"</td>";
                html += "<td class='last bold'>SPOT</td>";
                html += "</tr>";
                if($('#queue tr:last').length) {
                    $('#queue tr:last').after(html);
                } else {
                    $('#queue').html(html);
                }
            }
        }
    }
    
    this.inc = function() {
        $("#enq"+this.data.id).remove();
        var html = "<tr id='enq"+this.data.id+"'>";
        html += "<td>"+this.data.first_code+"</td>";
        html += "<td>"+this.data.first_bname+"</td>";
        html += "<td>"+this.data.pair+"</td>";
        html += "<td class='silver-bg'>"+this.data.amount+"</td>";
        html += "<td>SPOT</td>";
        html += "<td><input type='text' size='4' class='general-td-input' id='enq"+this.data.id+"_bf' /></td>";
        html += "<td><input type='text' size='2' class='general-td-input' id='enq"+this.data.id+"_buy' /></td>";
        html += "<td><input type='text' size='2' class='general-td-input' id='enq"+this.data.id+"_sell' /></td>";
        html += "<td id='enq"+this.data.id+"_trn' class='dark-red-bg lnk' onclick='trnEnq("+this.data.id+")'>TRANSMIT</td>";
        html += "</tr>";
        if($('#incoming tr:last').length) {
            $('#incoming tr:last').after(html);
        } else {
            $('#incoming').html(html);
        }
        this.where = "incoming";
    }
    
    this.activate = function() {
        if(this.where == "queue") {
            this.where = "incoming";
        } else {
            return false;
        }
    }
}
function submitform() {
    var p = $("#f_pair").val();
    var a = $("#f_amount").val();
    $.ajax({
        type: "POST",
        data: {pair: p, amount: a},
        url: "<?php print base_url () ?>trading/trading/add/",
        cache: false
    }).done(function(data) {
        data = jQuery.parseJSON(data);
        var i;
        for(i in data) {
            list[cnt] = new enquiry();
            list[cnt].init(data[i], -1, "outgoing");
            list[cnt].calc();
            setTimeout("toutEnq("+data[i]+")", 60000);
            cnt++;
        }
    });
    return false;
}

function check_existing() {
    var l = new Array();
    var s = new Array();
    var i;
    for(i=0; i < cnt; i++) {
        l[i] = list[i].getId();
        s[i] = list[i].getStatus();
    }
    $.ajax({
        type: "POST",
        data: {"ids": l, "sts": s},
        url: "<?php print base_url () ?>trading/trading/status/",
        cache: false
    }).done(function(data) {
        data = jQuery.parseJSON(data);
        var i;
        for(i in data) {
            if(data[i].status == 0) {
                $("#enq"+data[i].id+" td").css("background-color", "black");
                setTimeout(function(){deleteEnq(data[i].id)}, 5000);
            } else {
                var j;
                for(j = 0; j < cnt; j++) {
                    if(list[j].getId() == data[i].id) {
                        list[j].setData(data[i]);
                        list[j].recalc();
                    }
                }
            }
        }
    });
    setTimeout("check_existing();", 900);
}

function check_new() {
    var l = new Array();
    var i;
    for(i in list) {
        l[i] = list[i].getId();
    }
    $.ajax({
        type: "POST",
        data: {"ids": l},
        url: "<?php print base_url () ?>trading/trading/newen/",
        cache: false
    }).done(function(data) {
        data = jQuery.parseJSON(data);
        var i;
        for(i in data) {
            var j;
            var found = false;
            for(j in list) {
                if(list[j].getId() == data[i].id) {
                    found = true;
                } 
            }
            if(!found) {
                list[cnt] = new enquiry();
                list[cnt].init(data[i].id, data[i].status, "queue");
                list[cnt].setData(data[i]);
                list[cnt].calc();
                queue_number++;
                setQueueNumber();
                cnt++;
            }
        }
    });
    setTimeout("check_new();", 700);
}
$(function() {
    check_new();
    check_existing();
});
</script>

</body>
</html>
