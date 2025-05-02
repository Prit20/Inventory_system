<?php
session_start();
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $_POST['employee_id'];
    $password = $_POST['password'];

    // Connect to WorkBook DB
    $conn = connectDB("WorkBook");

    // Query to check login
    $sql = "SELECT * FROM [user] WHERE employee_id = ? AND password = ?";
    $params = array($employee_id, $password);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt && sqlsrv_has_rows($stmt)) {
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $_SESSION['user'] = $row['employee_id'];
        $_SESSION['username'] = $row['user_name'];  // Optional: name field
        header('Location: ../frontend/dashboard.php');
    } else {
        echo "<script>alert('Invalid Employee ID or Password.'); window.location.href='login.php';</script>";
    }
}
?>
