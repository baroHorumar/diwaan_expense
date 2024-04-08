<?php
require './includes/conn.php';

if ($conn->connect_error) {
    echo "Connection failed";
    exit;
}

if (isset($_POST['expense_id']) && is_numeric($_POST['expense_id'])) {
    $expenseId = $_POST['expense_id'];
    $stmt = $conn->prepare("DELETE FROM expenses WHERE expense_id = ?");
    $stmt->bind_param("i", $expenseId);
    if ($stmt->execute()) {
        echo "Expense deleted successfully";
    } else {
        echo "Error deleting expense: " . $stmt->error;
    }
} else {
    echo "Invalid or missing expense ID";
}
