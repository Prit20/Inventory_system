<?php
include '../config/db.php';



$action = $_POST['action'] ?? '';

if ($action === 'getcategories') {
    $conn = connectDB('Inventory_System');
    $query = "SELECT Id, Device_Category FROM DeviceCategory WHERE Is_Deleted = 0 ORDER BY Device_Category ASC";
    $stmt = sqlsrv_query($conn, $query);

    $categories = [];
    if ($stmt !== false) {
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $categories[] = [
                'id' => $row['Id'],
                'name' => $row['Device_Category']
            ];
        }
    }

    echo json_encode($categories);
    exit;
}

if ($action === 'savecategoryassign') {
    $sr_no = $_POST['sr_no'];
    $location = $_POST['location'];
    $po_no = $_POST['po_no'];
    $invoice_no = $_POST['invoice_no'];
    $party_name = $_POST['party_name'];
    $item = $_POST['item'];
    $qty = $_POST['qty'];
    $basic_rate = $_POST['basic_rate'];
    $remarks = $_POST['remarks'];
    $category_id = $_POST['category_id'];
    $category_name = $_POST['category_name'];

    $conn = connectDB('Inventory_System');

  
    // Get new Id
    $idQuery = "SELECT ISNULL(MAX(Id), 0) + 1 AS NewId FROM CategoryAssign";
    $idResult = sqlsrv_query($conn, $idQuery);
    $idRow = sqlsrv_fetch_array($idResult, SQLSRV_FETCH_ASSOC);
    $newId = $idRow['NewId'];

    $sql = "INSERT INTO CategoryAssign 
        (Id,Sr_No, Location, PO_No, Invoice_No, Party_Name, Item, Qty, Basic_Rate, Remarks, Category_Id, Category_Name)
        VALUES 
        (?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $params = [
        $newId,$sr_no, $location, $po_no, $invoice_no, $party_name,
        $item, $qty, $basic_rate, $remarks, $category_id, $category_name
    ];

    $stmt = sqlsrv_query($conn, $sql, $params);

    echo $stmt ? 'success' : 'error';
    exit;
}


//main query
$conn = connectDB('RM_software');

header('Content-Type: application/json; charset=utf-8');

$sql = "
SELECT 
    b.sr_no,
    b.receive_at AS location,
    a.p_po_no AS po_no,
    FORMAT(b.receive_date, 'dd-MMM-yyyy') AS rec_data,
    FORMAT(b.invoice_date, 'dd-MMM-yyyy') AS invoice_data,
    b.invoice_no,
    c.party_name,
    g.category,
    f.main_grade AS grade,
    e.sub_grade,
    d.item,
    b.mat_ord_by,
    a.rec_qnty AS qty,
    a.p_unit AS unit,
    (a.rec_qnty * a.pur_rate) AS basic_rate,
    a.p_remark
FROM RM_software.dbo.inward_ind a
LEFT OUTER JOIN RM_software.dbo.inward_com b ON a.sr_no = b.sr_no AND a.receive_at = b.receive_at
LEFT OUTER JOIN RM_software.dbo.rm_party_master c ON c.pid = b.mat_from_party
LEFT OUTER JOIN RM_software.dbo.rm_item d ON d.i_code = a.p_item
LEFT OUTER JOIN RM_software.dbo.rm_s_grade e ON e.s_code = d.s_code
LEFT OUTER JOIN RM_software.dbo.rm_m_grade f ON f.m_code = d.m_code
LEFT OUTER JOIN RM_software.dbo.rm_category g ON g.c_code = d.c_code
LEFT OUTER JOIN Inventory_System.dbo.CategoryAssign ad ON b.sr_no = ad.sr_no
WHERE a.sr_no > 0 
  AND g.c_code IN (32, 35) 
  AND f.m_code IN (138, 143) 
  AND e.s_code IN (866, 867, 992, 1159, 11214) 
  AND d.isactive = 1
  AND ad.sr_no IS NULL
ORDER BY e.sub_grade DESC
";

$result = sqlsrv_query($conn, $sql);
$data = [];

if ($result) {
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        array_walk_recursive($row, function (&$item) {
            if (is_string($item)) {
                $item = utf8_encode($item); // Fix malformed UTF-8 issue
            }
        });
        $data[] = $row;
    }
    echo json_encode(['data' => $data], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(['data' => []]);
}
?>
