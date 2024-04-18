<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('location: login.php');
}
require './includes/conn.php';
require './includes/header.php';
require './includes/sidebar.php';

$company_id = $_SESSION['company_id'];

$company_query = "SELECT * FROM company WHERE company_id = ?";
$stmt = $conn->prepare($company_query);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$company_result = $stmt->get_result();
$company_data = $company_result->fetch_assoc();


$company_branch_query = "SELECT * FROM company_branch WHERE company_id = ?";
$stmt = $conn->prepare($company_branch_query);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$company_branch_result = $stmt->get_result();
$company_branch_data = $company_branch_result->fetch_assoc();

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
        <div class="row justify-content-lg-center">
            <div class="col-lg-10">

                <div class="page-header">
                    <div class="row">
                        <div class="col">
                            <h3 class="page-title">Profile</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                <li class="breadcrumb-item active">Profile</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="text-center mb-5">
                    <h2> <?php echo $user_data['full_name']; ?> <i class="fas fa-certificate text-primary small" data-toggle="tooltip" data-placement="top" title="" data-original-title="Verified"></i></h2>
                    <ul class="list-inline">
                        <li class="list-inline-item">
                            <i class="far fa-building"></i> <span> <?php echo $company_data['business_name']; ?></span>
                        </li>
                        <li class="list-inline-item">
                            <i class="fas fa-map-marker-alt"></i><?php echo $company_branch_data['location']; ?>
                        </li>
                        <li class="list-inline-item">
                            <i class="far fa-calendar-alt"></i> <span><?php echo $company_data['registration_date']; ?></span>
                        </li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="card bg-white">
                        <div class="card-body">
                            <ul class="nav nav-tabs nav-tabs-solid nav-tabs-rounded nav-justified">
                                <li class="nav-item"><a class="nav-link active" href="#solid-rounded-justified-tab1" data-bs-toggle="tab">Profile</a></li>
                                <li class="nav-item"><a class="nav-link" href="#solid-rounded-justified-tab2" data-bs-toggle="tab">Company</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane show active" id="solid-rounded-justified-tab1">
                                    <div class="tab-pane show active" id="solid-rounded-justified-tab1">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5 class="card-title d-flex justify-content-between">
                                                        <span>Profile</span>
                                                        <a class="btn btn-sm btn-white" href="edit_profile.php">Edit</a>
                                                        <a class="btn btn-sm btn-success" href="change_password.php">Badal Passwordka</a>
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <ul class="list-unstyled mb-0">
                                                        <li class="py-0">
                                                            <h6>Ku saabsan Isticmaalaha</h6>
                                                        </li>
                                                        <li>
                                                            <h3>Magaca:
                                                                <strong><?php echo $user_data['full_name']; ?></strong>
                                                            </h3>

                                                        </li>
                                                        <li>
                                                            <h3>Username:
                                                                <strong><?php echo $user_data['username']; ?></strong>
                                                            </h3>
                                                        </li>


                                                        <li>
                                                            <h3>Telephoneka: <strong> <?php echo $company_data['phone_number']; ?>
                                                                </strong></h3>
                                                        </li>
                                                        <li class="pt-2 pb-0">
                                                            <h6>Taariikhda Lacag Bixinta</h6>
                                                        </li>
                                                        <li>
                                                            <strong> <?php echo $user_data['deactivation_date']; ?>, <?php
                                                                                                                        $deactivation_date = new DateTime($user_data['deactivation_date']);
                                                                                                                        $current_date = new DateTime();
                                                                                                                        $interval = $current_date->diff($deactivation_date);
                                                                                                                        echo $interval->format('%a cisho kadib');
                                                                                                                        ?>

                                                            </strong>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="solid-rounded-justified-tab2">
                                    <div class="tab-pane" id="solid-rounded-justified-tab2">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5 class="card-title d-flex justify-content-between">
                                                        <span>Macluumaadka Ganacsiga</span>
                                                        <a class="btn btn-sm btn-white" href="edit_company.php">Edit</a>
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <ul class="list-unstyled mb-0">
                                                        <li>
                                                            Magaca Ganacsiga: <?php echo $company_data['business_name']; ?>
                                                        </li>
                                                        <li>
                                                            Nooca Ganacsiga: <?php echo $company_data['business_type']; ?>
                                                        </li>

                                                        <li>
                                                            Phone Number: <a href="tel:<?php echo $company_data['phone_number']; ?>"><?php echo $company_data['phone_number']; ?></a>
                                                        </li>
                                                        <li class="pt-2 pb-0">
                                                            <p> Taariikhda Diwaangalinta: <strong><?php echo $company_data['registration_date']; ?>
                                                                </strong> </p>
                                                        </li>
                                                        <li>
                                                            Magaca Laanta: <?php echo $company_branch_data['branch_name']; ?>
                                                        </li>
                                                        <li>
                                                            Goobta: <?php echo $company_branch_data['location']; ?>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script data-cfasync="false" src="../../../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/js/script.js"></script>
    </body>

    </html>