<?php
include '../config/db.php';
$conn = connectDB('Inventory_System');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();

    // Get the company name from the form submission
    $company_name = isset($_POST['company_name']) ? trim($_POST['company_name']) : '';
    $company_id = isset($_POST['company_id']) ? trim($_POST['company_id']) : null;
    $action = isset($_POST['action']) ? trim($_POST['action']) : '';
    $current_timestamp = date('Y-m-d H:i:s');  // Get current timestamp
    $current_user = $_SESSION['username'];  // Placeholder for the current user, replace with actual user logic

    // Validate the company name
    if ($action !== 'delete_company' && empty($company_name)) {
        echo 'Company name is required.';
        exit();
    }

    if ($action === 'delete_company' && $company_id) {
        // Soft delete logic
        $delete_query = 'UPDATE CompanyName SET Is_Deleted = 1 WHERE Id = ?';
        $params = array($company_id);
        $stmt = sqlsrv_query($conn, $delete_query, $params);
        if ($stmt === false) {
            echo 'Error updating company: ' . print_r(sqlsrv_errors(), true);
            header('Location: error.php');
            exit();
        } else {
            header('Location: ../frontend/company.php?success=deleted');
            exit();
        }
    } else if ($company_id) {
        // Update existing company
        $update_query = 'UPDATE CompanyName SET Company_Name = ?, UpdatedAt = ?, UpdatedBy = ? WHERE Id = ?';
        $params = array($company_name, $current_timestamp, $current_user, $company_id);
        $stmt = sqlsrv_query($conn, $update_query, $params);
        if ($stmt === false) {
            echo 'Error updating company: ' . print_r(sqlsrv_errors(), true);
            header('Location: error.php');
            exit();
        } else {
            header('Location: ../frontend/company.php?success=edited');
            exit();
        }
    } else {
        // Insert new company
        $insert_query = 'INSERT INTO CompanyName (Company_Name, Is_Deleted, CreatedAt, CreatedBy) VALUES (?, 0, ?, ?)';
        $params = array($company_name, $current_timestamp, $current_user);
        $stmt = sqlsrv_query($conn, $insert_query, $params);
        if ($stmt === false) {
            echo 'Error inserting company: ' . print_r(sqlsrv_errors(), true);
        } else {
            header('Location: ../frontend/company.php?success=added');
            exit();
        }
    }
}
?>
