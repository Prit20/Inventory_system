<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../config/db.php'; // Replace with your actual DB connection include
    $conn = connectDB('Inventory_System');

    // Get latest ID from SerialNumberAssignment table
$idQuery = "SELECT MAX(Id) AS LastID FROM SerialNumberAssignment";
$idResult = sqlsrv_query($conn, $idQuery);
$row = sqlsrv_fetch_array($idResult, SQLSRV_FETCH_ASSOC);
$lastId = $row['LastID'] ?? 0;


    $assignId = $_POST['assign_id'];

    // Record details
    $Sr_No = $_POST['Sr_No'] ?? '';
    $location = $_POST['location'] ?? '';
    $po_no = $_POST['po_no'] ?? '';
    $invoice_no = $_POST['invoice_no'] ?? '';
    $party_name = $_POST['party_name'] ?? '';
    $item = $_POST['item'] ?? '';
    $qty = $_POST['qty'] ?? 0;
    $rate = $_POST['rate'] ?? '';
    $remarks = $_POST['remarks'] ?? '';
    $category = $_POST['category'] ?? '';

    // Warranty and Guarantee Details
    $warrantyType = $_POST['warranty_type'] ?? '';
    $warrantyStartDate = $_POST['warranty_start'] ?? '';
    $warrantyEndDate = $_POST['warranty_end'] ?? '';
    $guaranteeType = $_POST['guarantee_type'] ?? '';
    $guaranteeStartDate = $_POST['guarantee_start'] ?? '';
    $guaranteeEndDate = $_POST['guarantee_end'] ?? '';

// Insert Warranty and Guarantee into CategoryAssign Table
$warrantySql = "UPDATE CategoryAssign SET 
Warranty_Type = ?, 
Warranty_Start = ?, 
Warranty_End = ?, 
Guarantee_Type = ?, 
Guarantee_Start = ?, 
Guarantee_End = ?,
Is_Assigned = 1
WHERE Sr_No = ?";

$params = [
$warrantyType, 
$warrantyStartDate, 
$warrantyEndDate, 
$guaranteeType, 
$guaranteeStartDate, 
$guaranteeEndDate, 
$Sr_No
];

$stmt = sqlsrv_prepare($conn, $warrantySql, $params);
if (!$stmt || !sqlsrv_execute($stmt)) {
die(print_r(sqlsrv_errors(), true)); // Handle error
}
    // Arrays from rows
    $serialNumbers = $_POST['serial_number'] ?? [];
    $modelNumbers = $_POST['model_number'] ?? [];
    $companyNames = $_POST['company_name'] ?? [];
    $manufactureDates = $_POST['manufacture_date'] ?? [];
    $srRemarks = $_POST['remark'] ?? [];

    $macAddresses = $_POST['mac_address'] ?? [];
    $ipAddresses = $_POST['ip_address'] ?? [];
    $rams = $_POST['ram'] ?? [];
    $hardDisks = $_POST['hard_disk'] ?? [];
    $graphicsCards = $_POST['graphics'] ?? [];
// var_dump($companyNames[$i]); die;
    for ($i = 0; $i < $qty; $i++) {
        $newId = $lastId + 1;
        $lastId = $newId; // Update for next loop

        $sql = "INSERT INTO SerialNumberAssignment (
            Id,Assign_Id,Sr_No,Location, PO_No, Invoice_No, Party_Name, Item, Qty, Basic_Rate, Remarks,Category_Name,
            Serial_Number, Model_Number, Company_Name, Manufacture_Date, Sr_Remark,
            MAC_Address, IP_Address, RAM, Hard_Disk, Graphics
        ) VALUES (?,?, ?,?, ?, ?, ?, ?, ?, ?, ?,?,
                  ?, ?, ?, ?, ?,
                  ?, ?, ?, ?, ?)";

        $params = [
            $newId,$assignId,$Sr_No, $location, $po_no, $invoice_no, $party_name, $item, $qty, $rate, $remarks,$category,
            $serialNumbers[$i] ?? null,
            $modelNumbers[$i] ?? null,
            $companyNames,
            $manufactureDates[$i] ?? null,
            $srRemarks[$i] ?? null,
            $macAddresses[$i] ?? null,
            $ipAddresses[$i] ?? null,
            $rams[$i] ?? null,
            $hardDisks[$i] ?? null,
            $graphicsCards[$i] ?? null
        ];
        // var_dump($params); die;

        // Prepare and execute the statement for asset_details
        $stmt = sqlsrv_prepare($conn, $sql, $params);

        if ($stmt === false) {
            $errors = sqlsrv_errors();
            error_log('Prepare Error: ' . print_r($errors, true));
            throw new Exception('Failed to prepare statement: ' . print_r($errors, true));
        }

        if (!sqlsrv_execute($stmt)) {
            $errors = sqlsrv_errors();
            error_log('Execute Error: ' . print_r($errors, true));
            throw new Exception('Failed to execute insert query: ' . print_r($errors, true));
        }
        echo 'Successfully inserted record into asset_details';
        echo '<br>';

        // $stmt = sqlsrv_query($conn, $sql, $params);

        
    // if ($stmt === false) {
    //     die(print_r(sqlsrv_errors(), true)); // Debug if something fails
    // } else {
    //     echo "Inserted row ".($i+1)." successfully.<br>"; // Confirm success
    // }
    }

    // Redirect or show success
    header("Location: ../frontend/serialnoassign.php?assigned=1");
    // header("Location: ../frontend/serialnoassign.php");
    // exit;
}
?>
