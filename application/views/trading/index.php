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
        <th>buy</th>
        <th>sell</th>
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
        var new_sts = this.data.status;
        if(this.where == "outgoing") {
            if(new_sts == 1) {
                var html = "";
                html += "<td>"+this.data.second_code+"</td>";
                html += "<td>"+this.data.second_bname+"</td>";
                html += "<td>"+this.data.pair+"</td>";
                html += "<td>"+this.data.amount+"</td>";
                html += "<td colspan=3>waiting...</td>";
                html += "<td>action</td>";
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
                html += "<td>action</td>";
                $("#enq"+this.data.id).html(html);
            }
        } else if(this.where == "incoming") {
            if(new_sts == 3) {
                
            } else if(new_sts == 4) {
                
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
        }
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
        cache: false,
        dataTypeString: "json"
    }).done(function(data) {
        data = jQuery.parseJSON(data);
        var i;
        for(i in data) {
            list[cnt] = new enquiry();
            list[cnt].init(data[i], -1, "outgoing");
            list[cnt].calc();
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
        cache: false,
        dataTypeString: "json"
    }).done(function(data) {
        console.log(data);
        data = jQuery.parseJSON(data);
        var i;
        for(i in data) {
            if(data[i].status == 0) {
                deleteEnq(data[i].id);
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
    //setTimeout("check_existing();", 500);
}

function check_new() {
    var l = new Array();
    var i;
    for(i in list) {
        l[i] = list[i].getId();
    }
    $.ajax({
        type: "POST",
        data: l,
        url: "/trading/trading/newen/",
        cache: false,
        dataTypeString: "json"
    }).done(function(data) {
        var i;
        for(i in data) {
            list[cnt] = new enquiry();
            list[cnt].init(data[i].id, data[i].status, "queue");
            list[cnt].setData(data[i]);
            list[cnt].calc();
            cnt++;
        }
    });
    setTimeout("check_new();", 500);
}

</script>

</body>
</html>