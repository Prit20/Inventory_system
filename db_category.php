<?php
include '../config/db.php';
$conn = connectDB('Inventory_System');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    // Get the category name from the form submission
    $category_name = isset($_POST['category_name']) ? trim($_POST['category_name']) : '';
    $category_id = isset($_POST['category_id']) ? trim($_POST['category_id']) : null;
    $action = isset($_POST['action']) ? trim($_POST['action']) : '';
    $category_type = $_POST['category_type'] ?? 'General';  // Default to General if not sett
    $srno_required = $_POST['srno_required'] ?? 'Yes';  // Default to yes if not sett
    $detail_required = $_POST['detail_required'] ?? 'Yes';  // Default to yes if not sett

    $current_user = $_SESSION['username'];

    // Validate the category name
    if ($action !== 'delete_category' && empty($category_name)) {
        echo 'Category name is required.';
        exit;
    }

    if ($action === 'delete_category' && $category_id) {
        // Update delete logic to perform a soft delete
        $delete_query = 'UPDATE DeviceCategory SET Is_Deleted = 1 WHERE Id = ?';
        $params = array($category_id);
        $stmt = sqlsrv_query($conn, $delete_query, $params);
        if ($stmt === false) {
            echo 'Error updating category: ' . print_r(sqlsrv_errors(), true);
            header('Location: error.php');
            exit();
        } else {
            header('Location: ../frontend/category.php?success=deleted');
            exit();
        }
    } else if ($category_id) {
        // Update existing category
        $update_query = 'UPDATE DeviceCategory SET Device_Category = ?, Type = ?,SrNo_Required = ?,Detail_Required = ?, UpdatedAt = ?, UpdatedBy = ? WHERE Id = ?';
        $params = array($category_name, $category_type,$srno_required,$detail_required,date('Y-m-d H:i:s'), $current_user, $category_id);
        $stmt = sqlsrv_query($conn, $update_query, $params);
        if ($stmt === false) {
            echo 'Error updating category: ' . print_r(sqlsrv_errors(), true);
            header('Location: error.php');
            exit();
        } else {
            header('Location: ../frontend/category.php?success=edited');
            exit();
        }
    } else {
        // Insert new category
        $insert_query = 'INSERT INTO DeviceCategory (Device_Category, Type,SrNo_Required,Detail_Required,Is_Deleted, CreatedBy) VALUES (?, ?,?,?,0,?)';
        $params = array($category_name, $category_type, $srno_required,$detail_required,$current_user);
        $stmt = sqlsrv_query($conn, $insert_query, $params);
        if ($stmt === false) {
            echo 'Error inserting category: ' . print_r(sqlsrv_errors(), true);
            header('Location: error.php');
            exit();
        } else {
            header('Location: ../frontend/category.php?success=added');
            exit();
        }
    }
}
// Update the fetch query to exclude soft deleted categories
$cat_query = 'SELECT * FROM deviceCategory WHERE is_deleted = 0 ORDER BY type DESC';

?>
