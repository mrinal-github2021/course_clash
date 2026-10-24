<?php
session_start();
$conn = new mysqli("localhost", "root", "", "course_clash");
if ($conn->connect_error) { die("Database connection failed."); }

function require_role($role) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        header("Location: login.php"); exit;
    }
}
