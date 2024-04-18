<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('location:login.php');
}

if (isset($_POST['customer_id']) && is_numeric($_POST['customer_id'])) {
    require 'includes/conn.php'; // Include your database connection script
    $sql = "DELETE FROM customer WHERE cust_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_POST['customer_id']);
    if ($stmt->execute()) {

        header('location: all_customers.php');
    } else {
        $errorMessage = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Error!</strong> Failed to delete customer.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        echo $errorMessage;
    }
} else {
    $errorMessage = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Error!</strong> Invalid customer ID.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    echo $errorMessage;
}
