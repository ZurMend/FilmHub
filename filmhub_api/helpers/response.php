<?php
function jsonResponse($status, $data = null, $message = null) {
    header("Content-Type: application/json");
    echo json_encode([
        "status" => $status,
        "data" => $data,
        "message" => $message
    ]);
    exit;
}