<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('location: login.php');
    exit();
}

include 'includes/conn.php';
if (isset($_GET['customer_id'])) {
    $customer_id = $_GET['customer_id'];
    $select_cust_id_query = "SELECT customer_id FROM cust_transaction WHERE cust_tra_id = ?";
    $stmt = $conn->prepare($select_cust_id_query);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $customer_id_from = $row['customer_id'];
        $delete_query = "DELETE FROM cust_transaction WHERE cust_tra_id = ?";
        $stmt_delete = $conn->prepare($delete_query);
        $stmt_delete->bind_param("i", $customer_id);

        if ($stmt_delete->execute()) {
            header("Location: customer_details.php?customer_id=" . urlencode($customer_id_from));
            exit();
        } else {
            echo "Error deleting transaction: " . $conn->error;
        }
    } else {
        echo "Transaction not found";
    }
}
