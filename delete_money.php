<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('location: login.php');
}
require './includes/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['currency_id'])) {
    // Retrieve currency ID from the form submission
    $currency_id = $_POST['currency_id'];

    // Delete currency from the database based on the currency ID
    $stmt = $conn->prepare("DELETE FROM currencies WHERE id = ?");
    $stmt->bind_param("i", $currency_id);
    $stmt->execute();

    // Redirect back to the currencies page
    header('location: money.php');
    exit;
} else {
    // If the currency ID is not provided, redirect back to the currencies page
    header('location: money.php');
    exit;
}
