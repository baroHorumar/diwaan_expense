<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['customer_id'])) {
    $customer_id = $_POST['customer_id'];
    $_SESSION['customer_id_go'] = $customer_id;
    echo 'Session variable set successfully.';
} else {
    http_response_code(400);
    echo 'Error: Invalid request.';
}
