<?
    if(isset($error) && !is_null($error)) {
        echo $error."<hr />";
    }
 
    
    $search = array("%secondparty%", "%firstparty%", "%jobposition%");
    $replace = array($contract['uname'], $contract['bname'], $contract['pname']);
    
    $clauses = "<ul>";
    
    foreach($contract['clauses'] as $clause) {
        $txt = $clause['text'];
        if(isset($clause['vals'])) {
            foreach($clause['vals'] as $val) {
                $txt = str_replace("%value\${$val['name']}%", $val['value'], $txt);
            }
        }
        $clauses .= "<li>".str_replace($search, $replace, $txt)."</li>";
    }
    $clauses .= "</ul>";
    $search[] = "%clauses%";
    $replace[] = $clauses;
    $title = $contract['ctype'];
    $content = str_replace($search, $replace, $contract['ctext']);
?>
<strong><?=$title?></strong>
<br /><br /><br />
<?=$content?>
<br />
<hr />
<?=$contract['bname']?>: <strong>SIGNED</strong> <br />
<?=$contract['uname']?>: <a href="<?=$signurl?>">Sign contract now</a>
