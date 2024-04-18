<?php
session_start();
require './includes/conn.php';
require './includes/header.php';
require './includes/sidebar.php';

$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['full_name']) && isset($_POST['email'])) {
        $full_name = trim($_POST['full_name']);
        $email = trim($_POST['email']);
        $user_id = $_SESSION['user_id'];
        $update_query = "UPDATE users SET full_name=?, username=? WHERE user_id=?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssi", $full_name, $email, $user_id);
        if ($stmt->execute()) {
            echo "<script>window.location.href = './profile.php';</script>";
            exit();
        } else {
            $errorMessage = "Failed to update profile";
        }
    }
} else {
    $errorMessage = "All fields are required";
}

$user_id = $_SESSION['user_id'];
$user_query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user_data = $user_result->fetch_assoc();

?>

<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-title">Edit Profile</h3>
                </div>
            </div>
        </div>
        <?php echo $errorMessage; ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Macluumaadka Isticmaalaha</h4>
                        <form action="edit_profile.php" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Magaca</label>
                                        <input type="text" required class="form-control" name="full_name" value="<?php echo htmlspecialchars($user_data['full_name']); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" class="form-control" name="email" value="<?php echo htmlspecialchars($user_data['username']); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Update Profile</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/plugins/select2/js/select2.min.js"></script>
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/datatables.min.js"></script>
<script src="assets/plugins/moment/moment.min.js"></script>
<script src="assets/js/bootstrap-datetimepicker.min.js"></script>
<script src="assets/js/script.js"></script>
</body>

</html>