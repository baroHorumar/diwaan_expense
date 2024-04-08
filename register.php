<?php
require './includes/conn.php';
$businessName = $businessType = $location = $full_name = $username = $phoneNumber = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    function validateInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $businessName = validateInput($_POST["business_name"]);
    $businessType = validateInput($_POST["business_type"]);
    $location = validateInput($_POST["location"]);
    $full_name = validateInput($_POST["mulkiile_name"]);
    $username = validateInput($_POST["username"]);
    $phoneNumber = validateInput($_POST["mulkiile_phone"]);
    $password = validateInput($_POST["password"]);
    $confirmPassword = validateInput($_POST["confirm_password"]);

    // Check if passwords match
    if ($password !== $confirmPassword) {
        $errorMessage = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Error!</strong> Passwords do not match.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    } else {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errorMessage = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Error!</strong> Username already taken!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO company (business_name, phone_number, business_type, registration_date) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $businessName, $phoneNumber, $businessType, date('Y-m-d'));
            if ($stmt->execute()) {
                $companyId = $stmt->insert_id;
                $role = "Admin";
                $status = 0;
                $deactivation_date = date('Y-m-d', strtotime('+30 days'));

                // Insert admin user information
                $stmt = $conn->prepare("INSERT INTO users (full_name, username, password, registration_date, deactivation_date, role, status, company_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssii", $full_name, $username, $hashedPassword, date('Y-m-d'), $deactivation_date, $role, $status, $companyId);

                if ($stmt->execute()) {
                    $userId = $stmt->insert_id;
                    $branch = $businessName . ' branch 1';

                    // Insert company branch information
                    $stmt = $conn->prepare("INSERT INTO company_branch (company_id, branch_name, location) VALUES (?, ?, ?)");
                    $stmt->bind_param("iss", $companyId, $branch, $location);

                    if ($stmt->execute()) {
                        // Redirect to login page upon successful registration
                        header("Location: login.php");
                        exit();
                    } else {
                        $errorMessage = "Error inserting branch information";
                    }
                } else {
                    $errorMessage = "Error creating admin";
                }
            } else {
                $errorMessage = "Error creating company";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="main-wrapper login-body">
        <div class="login-wrapper">
            <div class="container">
                <?php
                if (isset($errorMessage)) {
                    echo $errorMessage;
                }
                ?>
                <div class="loginbox">
                    <div class="login-right">
                        <div class="login-right-wrap">
                            <h1>Register</h1>
                            <p class="account-subtitle">Ku soo Dhawaw Diwaan</p>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                <div class="form-group">
                                    <label class="form-control-label">Macaga Ganacsiga</label>
                                    <input class="form-control" type="text" name="business_name" value="<?php echo htmlspecialchars($businessName); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Nooca Ganacsiga</label>
                                    <input class="form-control" type="text" name="business_type" value="<?php echo htmlspecialchars($businessType); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Goobta uu ku yaalo</label>
                                    <input class="form-control" type="text" name="location" value="<?php echo htmlspecialchars($location); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Magaca Mulkiilaha</label>
                                    <input class="form-control" type="text" name="mulkiile_name" value="<?php echo htmlspecialchars($full_name); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Number Mulkiilaha</label>
                                    <input class="form-control" type="text" name="mulkiile_phone" value="<?php echo htmlspecialchars($phoneNumber); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Username</label>
                                    <input class="form-control" type="text" name="username" value="<?php echo htmlspecialchars($username); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Password</label>
                                    <input class="form-control" type="password" name="password">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Confirm Password</label>
                                    <input class="form-control" type="password" name="confirm_password">
                                </div>
                                <div class="form-group mb-0">
                                    <button class="btn btn-lg btn-block btn-primary w-100" type="submit" name="register">Register</button>
                                </div>
                            </form>
                            <div class="text-center dont-have">Already have an account? <a href="login.php">Login</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
</body>

</html>