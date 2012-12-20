<?php
if(!$error) {
    echo json_encode($data);
} else {
    echo json_encode(array("error"=>"true"));
}
?>
