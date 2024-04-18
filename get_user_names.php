<?php
// Include necessary files and initialize session if needed

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the required parameters are set
    if (isset($_POST['created_by']) && isset($_POST['updated_by'])) {
        // Retrieve the user IDs from the POST parameters
        $created_by = $_POST['created_by'];
        $updated_by = $_POST['updated_by'];

        // Query the database to get the full name of the user with created_by ID
        $stmt_created_by = $conn->prepare("SELECT full_name FROM users WHERE user_id = ?");
        $stmt_created_by->bind_param("i", $created_by);
        $stmt_created_by->execute();
        $stmt_created_by->bind_result($created_by_name);
        $stmt_created_by->fetch();
        $stmt_created_by->close();

        // Query the database to get the full name of the user with updated_by ID
        $stmt_updated_by = $conn->prepare("SELECT full_name FROM users WHERE user_id = ?");
        $stmt_updated_by->bind_param("i", $updated_by);
        $stmt_updated_by->execute();
        $stmt_updated_by->bind_result($updated_by_name);
        $stmt_updated_by->fetch();
        $stmt_updated_by->close();

        // Prepare the data to be sent back as JSON
        $data = array(
            'created_by_name' => $created_by_name,
            'updated_by_name' => $updated_by_name
        );

        // Send the data back as JSON response
        echo json_encode($data);
    } else {
        // If required parameters are not set, return an error
        http_response_code(400);
        echo "Missing required parameters.";
    }
} else {
    // If the request is not a POST request, return an error
    http_response_code(405);
    echo "Method Not Allowed";
}
