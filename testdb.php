<?php
echo "testing...<hr />";
$db = mysql_connect("tradingpit.thenewgeeksintown.com", "tpit_user", "trading002");
if($db !== false) {
    mysql_select_db("tpit_beta");
}

echo "<pre>";
$query = "SHOW TABLES";
$res = mysql_query($query);
while($row = mysql_fetch_array($res)) {
    print_r($row);
}
echo "</pre>";

echo "err:".mysql_error();
?>
