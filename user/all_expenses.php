<?php
include 'includes/conn.php';
if (isset($_GET['company_id'])) {
    $company_id = $_GET['company_id'];
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM expenses WHERE company_id = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $company_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $expenses = array();
    while ($row = $result->fetch_assoc()) {
        $expenses[] = $row;
    }
} else {
}
