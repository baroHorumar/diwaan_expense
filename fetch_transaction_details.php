<?php
// fetch_transaction_details.php

include 'includes/conn.php';

if (isset($_POST['transaction_id'])) {
    $transaction_id = $_POST['transaction_id'];

    $sql = "SELECT * FROM cust_transaction WHERE cust_tra_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $transaction_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $transaction = $result->fetch_assoc();
        echo json_encode($transaction); // Return transaction details as JSON
    } else {
        echo "Transaction not found";
    }
} else {
    echo "Transaction ID not provided";
}
