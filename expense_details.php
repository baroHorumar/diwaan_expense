<?php
// Include necessary files and database connection
require_once 'includes/conn.php';

// Check if expense ID is provided and valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $expenseId = $_GET['id'];

    // Prepare and execute SQL query to fetch expense details
    $sql = "SELECT expense_id, amount, description, date, company_id, branch_id, created_by_user_id, updated_by_user_id, created_at, updated_at, status FROM expenses WHERE expense_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $expenseId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if expense exists
    if ($result->num_rows > 0) {
        $expense = $result->fetch_assoc();
        // Output expense details as JSON
        echo json_encode($expense);
    } else {
        echo "Expense not found";
    }
} else {
    echo "Invalid expense ID";
}
