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
	<script>var base_url = "<?= base_url() ?>";</script>
	
  <script src="<?php print base_url () ?>js/jquery-1.8.2.min.js"></script>
  <script src="<?php print base_url () ?>js/jquery-ui-1.9.1.custom.js"></script>
	<script src="<?php print base_url () ?>js/validate.js"></script>
  <script src="<?php print base_url () ?>js/observable.js"></script>
  <script src="<?php print base_url () ?>js/corporate_clients.js"></script>
  <script src="<?php print base_url () ?>js/retail_clients.js"></script>
  <script src="<?php print base_url () ?>js/trading/ebroker.js"></script>
  <script src="<?php print base_url () ?>js/trading/matching-deals.js"></script>
 
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
					<?php include_once ($_SERVER['DOCUMENT_ROOT']."/tradingpit/application/views/menu.php"); ?>
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
              <li class="second">
              	<span class="light-green">TER/RIK</span> 
            	<span id = "TRPAT"> <?php echo $spot_positions[0]['position_amount'] ?> </span>
              	<span id = "TRPRT" class="gray">@<?php if(!$spot_positions[0]['position_rate'] ) 
              						echo "0.0000" ;
              					      else
              						echo round($spot_positions[0]['position_rate'],4) ?> </span></li>
              
              <li>
              	<span class="green">HAT/RIK</span> 
              	<span id = "HRPAT"> <?php echo $spot_positions[1]['position_amount'] ?> </span>
                <span id = "HRPRT" class="gray">@<?php 
                				    if(!$spot_positions[1]['position_rate'] ) 
              						echo "0.0000" ;
              					    else
              					      echo round($spot_positions[1]['position_rate'],4) ?> </span></li>
              
              <li class="last">
              	<span class="green">HAT/TER</span>
              	<span id = "HTPAT"> <?php echo $spot_positions[2]['position_amount'] ?> </span>
                <span id = "HTPRT" class="gray">@<?php 
                			    	   if(!$spot_positions[2]['position_rate'] ) 
              						echo "0.0000" ;
              					   else
              					      echo round($spot_positions[2]['position_rate'],4) ?> </span></li>
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

                <tbody id="matching">

                

                </tbody>

                <tbody id="deals">
              
                </tbody>

              </table>

              <div class="on-hold-overlay" id="hold-overlay">
                <span><strong>ON HOLD</strong></span>
              </div><!-- end on-hold-overlay -->

            </div><!-- end table-container black -->

          </div><!-- end main-section-content --> 
		
					<?php include_once ($_SERVER['DOCUMENT_ROOT']."/tradingpit/application/views/trading/ebroker.php"); ?>
					
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
    
    if( !validate_price(pbuy) || !validate_price(psell) ) 
    	return ;
    
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
            setTimeout(function(){deleteEnq(idv)}, 1000);
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
            setTimeout(function(){deleteEnq(idv)}, 1000);
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
            setTimeout(function(){deleteEnq(idv)}, 1000);
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
                html += "<td class='dark-silver-bg one' colspan='2'><button onclick='sellEnq("+this.data.id+")'>"+calcPips(this.data.price_buy)+"</button>";
                html += "<span class='decimal-indicator'><span>"+calcBf(this.data.price_buy)+"</span></td>";
                html += "<td class='dark-silver-bg two'><button onclick='buyEnq("+this.data.id+")'>"+calcPips(this.data.price_sell)+"</button></td>";
                html += "<td class='quote-td lnk' onclick='cancelEnq("+this.data.id+")'>Nothing there</td>";
                $("#enq"+this.data.id).html(html);
            }
        } else if(this.where == "incoming") {
            var dis = this;
            if(new_sts == 3) {
                $("#enq"+this.data.id+"_trn").html("");
                $("#enq"+this.data.id+" td").css("background-color", "#E09F9F");
                setTimeout(function(){deleteEnq(dis.data.id)}, 1000);
            } else if(new_sts == 4) {
                $("#enq"+this.data.id+"_trn").html("");
                $("#enq"+this.data.id+" td").css("background-color", "#B9DFBC");
                setTimeout(function(){deleteEnq(dis.data.id)}, 1000);
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
                setTimeout(function(){deleteEnq(data[i].id)}, 1000);
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
    setTimeout("check_existing();", 1000);////////////////////////////////////
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
    setTimeout("check_new();", 1000); ////////////////////////////////////////
}
$(function() {
    check_new();
    check_existing();
});



</script>

<script>
	$('#hold-overlay').hide ();
</script>
<!--
<?php
if(isset($pnl)) {
	echo"<pre>";print_r($pnl);echo"</pre>";
	echo"<script>
	var pnl=".json_encode($pnl).";
	function makePNL(v) {
		
		$('#pnlval').text(pnl[v]);
		$('.current').removeClass('current');
		$('#cr'+v).addClass('current');
	}
	makePNL(1);
	</script>";
}
?>
-->


<script>
function get_spot_positions() {

	var url = base_url + "trading/blotters/get_spot_positions" ; 
	
	$.ajax({
		action: 'POST',
      		url: url,
     		dataType: 'json',
      
      		success: function (data,textStatus, jqXHR) {                    
				console.log (data);    		
    			        display_spot_positions (data);
			 }, 
	  
	        error: function(XMLHttpRequest, textStatus, errorThrown) {
        			//console.log(textStatus, errorThrown);
      			}

	});
}

function get_fx_pnl() {

	var url = base_url + "trading/blotters/get_fx_pnl" ; 
	
	$.ajax({
		action: 'POST',
      		url: url,
     		dataType: 'json',
      
      		success: function (data,textStatus, jqXHR) {                    
				console.log (data);    		
    			        display_fx_pnl (data);
			 }, 
	  
	        error: function(XMLHttpRequest, textStatus, errorThrown) {
        			//console.log(textStatus, errorThrown);
      			}

	});
}



function display_spot_positions( spot_positions ) { 

	console.log(spot_positions);
	
	$("#TRPAT").text( display_amount(spot_positions[0]['position_amount']) ) ;
	$("#TRPRT").text( "@" + parseFloat( spot_positions[0]['position_rate'] ).toFixed(4) ) ;

	$("#HRPAT").text( display_amount(spot_positions[1]['position_amount'] ) ) ;
	$("#HRPRT").text( "@" + parseFloat( spot_positions[1]['position_rate'] ).toFixed(4) ) ;

	$("#HTPAT").text( display_amount(spot_positions[2]['position_amount']) ) ;
	$("#HTPRT").text( "@" + parseFloat( spot_positions[2]['position_rate'] ).toFixed(4) ) ;
	
}

function display_fx_pnl ( fx_pnl ) { 
	
	//alert(PNL_CCY);
	$('#pnlval').text(fx_pnl[PNL_CCY]);
	
}

get_spot_positions();
get_fx_pnl() ;
setInterval("get_spot_positions()",1000);
setInterval("get_fx_pnl()",1000);

</script>

</body>
</html>

