﻿<?php
require './includes/conn.php';
require './includes/header.php';
require './includes/sidebar.php';
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

                <div class="profile-cover">
                    <div class="profile-cover-wrap">
                        <img class="profile-cover-img" src="assets/img/profiles/avatar-02.jpg" alt="Profile Cover">

                        <div class="cover-content">
                            <div class="custom-file-btn">
                                <input type="file" class="custom-file-btn-input" id="cover_upload">
                                <label class="custom-file-btn-label btn btn-sm btn-white" for="cover_upload">
                                    <i class="fas fa-camera"></i>
                                    <span class="d-none d-sm-inline-block ms-1">Update Cover</span>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="text-center mb-5">
                    <label class="avatar avatar-xxl profile-cover-avatar" for="avatar_upload">
                        <img class="avatar-img" src="assets/img/profiles/avatar-02.jpg" alt="Profile Image">
                        <input type="file" id="avatar_upload">
                        <span class="avatar-edit">
                            <i data-feather="edit-2" class="avatar-uploader-icon shadow-soft"></i>
                        </span>
                    </label>
                    <h2>Charles Hafner <i class="fas fa-certificate text-primary small" data-toggle="tooltip" data-placement="top" title="" data-original-title="Verified"></i></h2>
                    <ul class="list-inline">
                        <li class="list-inline-item">
                            <i class="far fa-building"></i> <span>Hafner Pvt Ltd.</span>
                        </li>
                        <li class="list-inline-item">
                            <i class="fas fa-map-marker-alt"></i> West Virginia, US
                        </li>
                        <li class="list-inline-item">
                            <i class="far fa-calendar-alt"></i> <span>Joined November 2017</span>
                        </li>
                    </ul>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card card-body">
                            <h5>Complete your profile</h5>

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="progress progress-md flex-grow-1">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ms-4">30%</span>
                            </div>

                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title d-flex justify-content-between">
                                    <span>Profile</span>
                                    <a class="btn btn-sm btn-white" href="settings.html">Edit</a>
                                </h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">
                                    <li class="py-0">
                                        <h6>About</h6>
                                    </li>
                                    <li>
                                        Charles Hafner
                                    </li>
                                    <li>
                                        Hafner Pvt Ltd.
                                    </li>
                                    <li class="pt-2 pb-0">
                                        <h6>Contacts</h6>
                                    </li>
                                    <li>
                                        <a href="/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="72111a13001e17011a13141c170032170a131f021e175c111d1f">[email&#160;protected]</a>
                                    </li>
                                    <li>
                                        +1 (304) 499-13-66
                                    </li>
                                    <li class="pt-2 pb-0">
                                        <h6>Address</h6>
                                    </li>
                                    <li>
                                        4663 Agriculture Lane,<br>
                                        Miami,<br>
                                        Florida - 33165,<br>
                                        United States.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Activity</h5>
                            </div>
                            <div class="card-body card-body-height">
                                <ul class="activity-feed">
                                    <li class="feed-item">
                                        <div class="feed-date">Nov 16</div>
                                        <span class="feed-text"><a href="profile.html">Brian Johnson</a> has paid the invoice <a href="view-invoice.html">"#DF65485"</a></span>
                                    </li>
                                    <li class="feed-item">
                                        <div class="feed-date">Nov 7</div>
                                        <span class="feed-text"><a href="profile.html">Marie Canales</a> has accepted your estimate <a href="view-estimate.html">#GTR458789</a></span>
                                    </li>
                                    <li class="feed-item">
                                        <div class="feed-date">Oct 24</div>
                                        <span class="feed-text">New expenses added <a href="expenses.html">"#TR018756</a></span>
                                    </li>
                                    <li class="feed-item">
                                        <div class="feed-date">Oct 24</div>
                                        <span class="feed-text">New expenses added <a href="expenses.html">"#TR018756</a></span>
                                    </li>
                                    <li class="feed-item">
                                        <div class="feed-date">Oct 24</div>
                                        <span class="feed-text">New expenses added <a href="expenses.html">"#TR018756</a></span>
                                    </li>
                                    <li class="feed-item">
                                        <div class="feed-date">Oct 24</div>
                                        <span class="feed-text">New expenses added <a href="expenses.html">"#TR018756</a></span>
                                    </li>
                                    <li class="feed-item">
                                        <div class="feed-date">Oct 24</div>
                                        <span class="feed-text">New expenses added <a href="expenses.html">"#TR018756</a></span>
                                    </li>
                                    <li class="feed-item">
                                        <div class="feed-date">Jan 27</div>
                                        <span class="feed-text"><a href="profile.html">Robert Martin</a> gave a review for <a href="product-details.html">"Dell Laptop"</a></span>
                                    </li>
                                    <li class="feed-item">
                                        <div class="feed-date">Jan 14</div>
                                        <span class="feed-text">New customer registered <a href="profile.html">"Tori Carter"</a></span>
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


<script data-cfasync="false" src="../../../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
<script src="assets/js/jquery-3.6.0.min.js"></script>

<script src="assets/js/bootstrap.bundle.min.js"></script>

<script src="assets/js/feather.min.js"></script>

<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<script src="assets/js/script.js"></script>
</body>

</html>