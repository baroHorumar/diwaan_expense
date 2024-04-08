<?php
session_start();

if (isset($_SESSION['user'])) {
    echo json_encode(['loggedIn' => true]);
} else {
    echo json_encode(['loggedIn' => false]);
}
