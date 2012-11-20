<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />

  <title>Economic Indicators - Trading Pit</title>
  <meta name="description" content="" />
  <link href="http://tradingpit.thenewgeeksintown.com/css/jquery-ui-1.9.1.custom.css" rel="stylesheet">
  <script src="http://tradingpit.thenewgeeksintown.com/js/jquery-1.8.2.min.js"></script>
  <script src="http://tradingpit.thenewgeeksintown.com/js/date.js"></script>
  <script src="http://tradingpit.thenewgeeksintown.com/js/jquery-ui-1.9.1.custom.js"></script>
  <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
  <style>
    td{
        border: 1px solid black;
    } 
    th {
        border: 1px solid blue;
    }
    table {
        border: 1px solid green;
    }
</style>
</head>

<body>


<table id="queue">
    <tr id="queue_head">
        <th>bank</th>
        <th>ccy pair</th>
        <th>amount</th>
        <th>action</th>
    </tr>
</table>
<br /><hr /><br />
<table id="incoming">
    <tr id="incoming_head">
        <th>code</th>
        <th>name</th>
        <th>pair</th>
        <th>amount</th>
        <th>bf</th>
        <th>buy</th>
        <th>sell</th>
        <th>action</th>
    </tr>
</table>
<br /><hr /><br />
<form id="call_form">
    <select id="f_pair">
        <?php
        foreach($pairs as $id=>$val) {
            echo "<option value='{$id}'>{$val}</option>";
        }
        ?>
    </select>
    <select id="f_amount">
        <?php
        foreach($amounts as $val) {
            echo "<option value='{$val}'>{$val}</option>";
        }
        ?>
    </select>
    <input type="submit" value="CALL" />
</form>

<table id="outgoing">
    <tr id="outgoing_head">
        <th>code</th>
        <th>name</th>
        <th>pair</th>
        <th>amount</th>
        <th>bf</th>
        <th>sell</th>
        <th>buy</th>
        <th>action</th>
    </tr>
</table>

<script type="text/javascript">
    
//helper functions
function calcBf(nr) {
    return nr.substring(0, nr.indexOf(".")+3);
}

function calcPips(nr) {
    return nr.substring(nr.indexOf(".")+3, nr.indexOf(".")+5);
}

//real shit
var user = <?=$user?>;
var list = new Array();
var cnt = 0;

function deleteEnq(id) {
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
        url: "/trading/trading/respond/",
        cache: false
    }).done(function(data) {
        data = jQuery.parseJSON(data);
        if(!data.error) {
            $("#enq"+idv).css("background-color", "yellow");
            var vhtm = "<input type='button' value='RISK' onclick='cancelEnq("+idv+")' />";
            setTimeout(function(){$("#enq"+idv+"_trn").html(vhtm);}, 15000);
        } else {
            
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
        url: "/trading/trading/buy/",
        cache: false
    }).done(function(data) {
        data = jQuery.parseJSON(data);
        if(!data.error) {
            $("#enq"+idv).css("background-color", "green");
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
        url: "/trading/trading/sell/",
        cache: false
    }).done(function(data) {
        data = jQuery.parseJSON(data);
        if(!data.error) {
            $("#enq"+idv).css("background-color", "red");
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
        url: "/trading/trading/cancel/",
        cache: false
    }).done(function(data) {
        data = jQuery.parseJSON(data);
        if(!data.error) {
            $("#enq"+idv).css("background-color", "#555555");
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
    
    this.init = function(id, sts, where) {
        this.data.id = id;
        this.data.status = sts;
        this.where = where
    }
    
    this.setInAction = function(val) {
        this.in_action = val;
    }
    
    this.del = function() {
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
                html += "<td colspan=3>waiting...</td>";
                html += "<td>-</td>";
                $("#enq"+this.data.id).html(html);
            } else if(new_sts == 2) {
                var html = "";
                html += "<td>"+this.data.second_code+"</td>";
                html += "<td>"+this.data.second_bname+"</td>";
                html += "<td>"+this.data.pair+"</td>";
                html += "<td>"+this.data.amount+"</td>";
                html += "<td>"+calcBf(this.data.price_buy)+"</td>";
                html += "<td>"+calcPips(this.data.price_buy)+"</td>";
                html += "<td>"+calcPips(this.data.price_sell)+"</td>";
                html += "<td><input type='button' onclick='sellEnq("+this.data.id+")' value='Sell'><input type='button' onclick='buyEnq("+this.data.id+")' value='Buy'><input type='button' onclick='cancelEnq("+this.data.id+")' value='NTG'></td>";
                $("#enq"+this.data.id).html(html);
            }
        } else if(this.where == "incoming") {
            var dis = this;
            if(new_sts == 3) {
                $("#enq"+this.data.id+"_trn").html("");
                $("#enq"+this.data.id).css("background-color", "red");
                setTimeout(function(){deleteEnq(dis.data.id)}, 5000);
            } else if(new_sts == 4) {
                $("#enq"+this.data.id+"_trn").html("");
                $("#enq"+this.data.id).css("background-color", "green");
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
                $('#outgoing tr:last').after(html);
            }
        } else if(this.where == "queue") {
            if(this.data.status == 1) {
                var html = "<tr id='enq"+this.data.id+"'>";
                html += "<td>"+this.data.first_bname+"</td>";
                html += "<td>"+this.data.pair+"</td>";
                html += "<td>"+this.data.amount+"</td>";
                html += "<td><input type='button' onclick='incEnq("+this.data.id+")' value='Open' /></td>";
                html += "</tr>";
                $('#queue tr:last').after(html);
            }
        }
    }
    
    this.inc = function() {
        $("#enq"+this.data.id).remove();
        var html = "<tr id='enq"+this.data.id+"'>";
        html += "<td>"+this.data.first_code+"</td>";
        html += "<td>"+this.data.first_bname+"</td>";
        html += "<td>"+this.data.pair+"</td>";
        html += "<td>"+this.data.amount+"</td>";
        html += "<td><input type='text' id='enq"+this.data.id+"_bf' /></td>";
        html += "<td><input type='text' id='enq"+this.data.id+"_buy' /></td>";
        html += "<td><input type='text' id='enq"+this.data.id+"_sell' /></td>";
        html += "<td id='enq"+this.data.id+"_trn'><input type='button' value='Transmit' onclick='trnEnq("+this.data.id+")' /></td>";
        html += "</tr>";
        $('#incoming tr:last').after(html);
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
$("#call_form").submit(function() {
    var p = $("#f_pair").val();
    var a = $("#f_amount").val();
    $.ajax({
        type: "POST",
        data: {pair: p, amount: a},
        url: "/trading/trading/add/",
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
})

function check_existing() {
    var l = new Array();
    var s = new Array();
    var i;
    for(i in list) {
        l[i] = list[i].getId();
        s[i] = list[i].getStatus();
    }
    $.ajax({
        type: "POST",
        data: {"ids": l, "sts": s},
        url: "/trading/trading/status/",
        cache: false
    }).done(function(data) {
        data = jQuery.parseJSON(data);
        var i;
        for(i in data) {
            if(data[i].status == 0) {
                $("#enq"+data[i].id).css("background-color", "#555555");
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
    setTimeout("check_existing();", 500);
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
        url: "/trading/trading/newen/",
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
                cnt++;
            }
        }
    });
    setTimeout("check_new();", 500);
}
$(function() {
    check_new();
    check_existing();
});
</script>

</body>
</html>