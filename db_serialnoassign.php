<?php
include '../config/db.php';


// Handle DataTable AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'GET' && 
    isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' &&
    isset($_GET['status'])) {

    $status = $_GET['status']; // 'assigned' or 'unassigned'
    $conn = connectDB('Inventory_System');

    if ($status === 'assigned') {
        $query = "SELECT CategoryAssign.Id, CategoryAssign.Location, CategoryAssign.PO_No, CategoryAssign.Invoice_No, CategoryAssign.Party_Name, CategoryAssign.Item, CategoryAssign.Qty, CategoryAssign.Basic_Rate, CategoryAssign.Remarks, CategoryAssign.Category_Name,SerialNumberAssignment.Serial_Number
        FROM CategoryAssign JOIN SerialNumberAssignment ON CategoryAssign.Id = SerialNumberAssignment.Assign_Id 
        WHERE CategoryAssign.Is_Deleted = 0 AND CategoryAssign.Is_Assigned = 1
        ORDER BY CategoryAssign.Id DESC";
    } else { $query = "SELECT Id, Location, PO_No, Invoice_No, Party_Name, Item, Qty, Basic_Rate, Remarks, Category_Name
        FROM CategoryAssign
        WHERE Is_Deleted = 0 AND (Is_Assigned IS NULL OR Is_Assigned = 0)
        ORDER BY Id DESC";
        
    }

    $result = sqlsrv_query($conn, $query);
    $data = [];
    $sr = 1;
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $row['Sr_No'] = $sr++;
        $data[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode(['data' => $data]);
    exit;
}

// Function to fetch record details
function getSerialAssignDetails($id) {
    $conn = connectDB('Inventory_System');
    $query = "SELECT * FROM CategoryAssign WHERE Id = '$id'";
    $result = sqlsrv_query($conn, $query);

    $data = [];
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $data[] = $row;
    }

    return $data;
}

// Handle form submission for update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_serial_assign') {
    $conn = connectDB('Inventory_System');

    $assign_id = $_POST['record_id'];
    $serials = $_POST['serial_number'];
    $models = $_POST['model_number'];
    $companies = $_POST['company_name'];
    $dates = $_POST['manufacture_date'];
    $remarks = $_POST['remark'];

    // Optional: delete old records with same assign_id before re-inserting
    $deleteSql = "DELETE FROM CategoryAssign WHERE assign_id = '$assign_id'";
    sqlsrv_query($conn, $deleteSql);

    for ($i = 0; $i < count($serials); $i++) {
        $serial = $serials[$i];
        $model = $models[$i];
        $company = $companies[$i];
        $mfg = $dates[$i];
        $remark = $remarks[$i];

        $insert = "INSERT INTO CategoryAssign (assign_id, serial_number, model_number, company_name, manufacture_date, remark)
                   VALUES ('$assign_id', '$serial', '$model', '$company', '$mfg', '$remark')";

        sqlsrv_query($conn, $insert);
    }

    // Redirect after save
    header("Location: ../serialnoassign.php?success=1");
    exit;
}


function getSerialAssignHeaderDetails($id) {
    $conn = getInventorySystemConnection();
    $query = "SELECT * FROM CategoryAssign WHERE Id = '$id'";
    $result = sqlsrv_query($conn, $query);
    return sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
}
