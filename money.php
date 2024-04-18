<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('location: login.php');
}
require './includes/conn.php';
require './includes/header.php';
require './includes/sidebar.php';

// Fetch currencies for the current company
$company_id = $_SESSION['company_id'];
$stmt = $conn->prepare("SELECT * FROM currencies WHERE company_id = ?");
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();
$currencies = $result->fetch_all(MYSQLI_ASSOC);

// Toggle default status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['toggle_default'])) {
    $currency_id = $_POST['currency_id'];

    $stmt = $conn->prepare("SELECT is_default FROM currencies WHERE id = ?");
    $stmt->bind_param("i", $currency_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $currency = $result->fetch_assoc();
    $current_default = $currency['is_default'];

    // Toggle the default status in the database
    $new_default = $current_default === 'Yes' ? 'No' : 'Yes';
    $stmt = $conn->prepare("UPDATE currencies SET is_default = ? WHERE id = ?");
    $stmt->bind_param("si", $new_default, $currency_id);
    $stmt->execute();

    echo '<script>
        // Redirect to currencies.php
        window.location.href = \'./money.php\';
    </script>';
}
?>

<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Currencies</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Currencies</li>
                    </ul>
                </div>
                <div class="col-auto">
                    <a href="add_money.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <?php foreach ($currencies as $currency) : ?>
                <div class="col-sm-6 col-lg-4 col-xl-3">
                    <div class="card <?php echo $currency['is_default'] === 'Yes' ? 'bg-success-light' : 'bg-warning-light'; ?>">
                        <div class="card-body">
                            <div class="inv-header mb-3">
                                <h3 class="text-center"><?php echo $currency['name']; ?></h3>
                            </div>
                            <div class="invoice-id mb-3">
                                <p class="text-yellow text-center">Symbol: <?php echo $currency['symbol']; ?></p>
                            </div>
                            <div class="row align-items-center">
                                <div class="col">
                                    <span class="text-sm text-muted"><i class="far fa-money-bill-alt"></i>Isu badalka</span>
                                    <h6 class="mb-0"><?php echo $currency['rate_exchange']; ?></h6>
                                </div>
                                <div class="col-auto text-end">
                                    <form action="" method="post">
                                        <input type="hidden" name="currency_id" value="<?= $currency['id'] ?>">
                                        <?php if ($currency['is_default'] === 'Yes') : ?>
                                            <button type="submit" class="btn btn-sm btn-primary" name="toggle_default">Set as Default</button>
                                        <?php else : ?>
                                            <button type="submit" class="btn btn-sm btn-secondary" name="toggle_default">Set as Default</button>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <?php if ($currency['is_default'] == 'Yes') {
                                    ?>
                                        <span class="badge bg-success-light"> <?php echo $currency['is_default']; ?></span>
                                    <?php
                                    } else {
                                    ?>
                                        <span class="badge bg-warning-light">
                                            <?php echo $currency['is_default']; ?>
                                        </span>
                                    <?php
                                    } ?>

                                </div>
                                <div class="col d-flex justify-content-end">
                                    <form action="delete_money.php" method="POST">
                                        <input type="hidden" name="currency_id" value="<?php echo $currency['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm rounded-pill circle-btn c" name="delete_currencies" style="margin-right: 5px;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    <form action="edit_money.php" method="POST">
                                        <input type="hidden" name="currencies" value="<?php echo $currency['id']; ?>">
                                        <button type="submit" class="btn btn-primary btn-sm rounded-pill circle-btn c" name="update_currencies">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>

<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/plugins/select2/js/select2.min.js"></script>
<script src="assets/plugins/moment/moment.min.js"></script>
<script src="assets/js/bootstrap-datetimepicker.min.js"></script>
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/datatables.min.js"></script>
<script src="assets/js/script.js"></script>
</body>

</html>