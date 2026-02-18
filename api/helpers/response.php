<?php
header("Content-Type: application/json");

function response($status, $data = null){
    echo json_encode([
        "status"=>$status,
        "data"=>$data
    ]);
    exit;
}
?>
