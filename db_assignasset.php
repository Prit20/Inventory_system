<?php
include('../config/db.php');
ob_clean();

function escape($conn, $str) {
    return htmlspecialchars(trim($str), ENT_QUOTES);
}

// User search
if (($_SERVER["REQUEST_METHOD"] === "POST") && isset($_POST["search"])) {
    $search = $_POST["search"];
    $query = "SELECT user_name, employee_id, department, designation FROM [user] WHERE user_name LIKE ? OR employee_id LIKE ?";
    $params = array("%$search%", "%$search%");
    $stmt = sqlsrv_query($connWorkBook, $query, $params);
    if ($stmt === false) die(print_r(sqlsrv_errors(), true));

    $users = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $users[] = $row;
    }
    echo json_encode($users);
}

// Fetch user details
if (isset($_POST['action']) && $_POST['action'] == 'fetch_user') {
    $userId = escape($connWorkBook, $_POST['userId']);
    $query = "SELECT * FROM [user] WHERE employee_id = '$userId'";
    $result = sqlsrv_query($connWorkBook, $query);
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    echo json_encode($row);
    exit;
}

// Fetch companies based on category
if (isset($_POST['action']) && $_POST['action'] == 'fetch_company') {
    $category = escape($connInventory, $_POST['category']);
    // $query = "SELECT DISTINCT Company_Name FROM SerialNumberAssignment WHERE Category_Name = '$category'";
    $query = "
SELECT DISTINCT Company_Name 
FROM SerialNumberAssignment AS s
WHERE Category_Name = '$category'
AND EXISTS (
    SELECT 1 
    FROM SerialNumberAssignment AS s2
    WHERE s2.Company_Name = s.Company_Name 
      AND s2.Category_Name = s.Category_Name
      AND s2.Serial_Number NOT IN (SELECT Serial_Number FROM AssignedAssets)
)";
    $result = sqlsrv_query($connInventory, $query);
    $options = "<option value=''>Select Company</option>";
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $options .= "<option value='" . $row['Company_Name'] . "'>" . $row['Company_Name'] . "</option>";
    }
    echo $options;
    exit;
}

// Fetch serials based on company
if (isset($_POST['action']) && $_POST['action'] == 'fetch_serial') {
    $company = escape($connInventory, $_POST['company']);
    // $Srnocheck = "SELECT Serial_Number FROM AssignedAssets";
    $query = "SELECT Serial_Number FROM SerialNumberAssignment WHERE Company_Name = '$company' AND Serial_Number NOT IN (SELECT Serial_Number FROM AssignedAssets)";
    $result = sqlsrv_query($connInventory, $query);
    $options = "<option value=''>Select Serial</option>";
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $options .= "<option value='" . $row['Serial_Number'] . "'>" . $row['Serial_Number'] . "</option>";
    }
    echo $options;
    exit;
}

// Save assigned assets

// Check if action is set
if (isset($_POST['action']) && trim($_POST['action']) === 'save_assignment') {

    // Get next available Assignment_Id
    $findMax = "SELECT ISNULL(MAX(Assignment_Id), 0) + 1 AS next_id FROM AssignedAssets";
    $findMaxResult = sqlsrv_query($connInventory, $findMax);

    if ($findMaxResult === false) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to fetch next Assignment_Id',
            'details' => sqlsrv_errors()
        ]);
        exit;
    }

    $nextIdRow = sqlsrv_fetch_array($findMaxResult, SQLSRV_FETCH_ASSOC);
    $nextId = $nextIdRow['next_id'];

    // Collect input values
    $userName     = escape($connInventory, $_POST['user_name']);
    $plant        = escape($connInventory, $_POST['plant']);
    $assignDate   = $_POST['assignment_date']; // should be in 'Y-m-d' format
    $employeeId   = escape($connInventory, $_POST['employee_id']);
    $designation  = escape($connInventory, $_POST['designation']);
    $department   = escape($connInventory, $_POST['department']);
    $description  = escape($connInventory, $_POST['description']);
    $assets       = json_decode($_POST['assets'], true);

    // Check if assets array is empty
    if (empty($assets)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'No assets found in request!',
            'raw_assets' => $_POST['assets']
        ]);
        exit;
    }

    // Loop through each asset and insert into DB
    foreach ($assets as $asset) {
        $category = escape($connInventory, $asset['deviceCategory']);
        $company  = escape($connInventory, $asset['companyName']);
        $serial   = escape($connInventory, $asset['serialNumber']);
        $remark   = escape($connInventory, $asset['remark']);

        // Debug output to check data
        var_dump($category, $company, $serial, $remark);

        // Prepare and execute insert query
        $insertQuery = "
            INSERT INTO AssignedAssets (
                Assignment_Id, User_Name, Plant_Name, Assignment_Date, 
                Employee_Id, Designation, Department, Description,
                Device_Category, Company_Name, Serial_Number, Remark
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )
        ";

        $params = [
            $nextId, $userName, $plant, $assignDate,
            $employeeId, $designation, $department, $description,
            $category, $company, $serial, $remark
        ];

        $result = sqlsrv_query($connInventory, $insertQuery, $params);

        if ($result === false) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Insert failed',
                'details' => sqlsrv_errors(),
                'asset' => $asset
            ]);
            exit;
        }
    }

    // If all inserts succeeded
    echo json_encode([
        'status' => 'success',
        'message' => 'Assignment saved successfully'
    ]);
    exit;
}

// Fetch assignment history
if (isset($_POST['action']) && $_POST['action'] == 'fetch_user_assets') {
    $userId = escape($connInventory, $_POST['userId']);
    $query = "SELECT Assignment_Id, Employee_Id, Device_Category, Company_Name, Serial_Number, Assignment_Date 
              FROM AssignedAssets 
              WHERE Employee_Id = '$userId'";

    $result = sqlsrv_query($connInventory, $query);

    if ($result && sqlsrv_has_rows($result)) {
        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            echo "<tr>
                <td class='px-4 py-2'>{$row['Assignment_Id']}</td>
                <td class='px-4 py-2'>{$row['Employee_Id']}</td>
                <td class='px-4 py-2'>{$row['Device_Category']}</td>
                <td class='px-4 py-2'>{$row['Company_Name']}</td>
                <td class='px-4 py-2'>{$row['Serial_Number']}</td>
                <td class='px-4 py-2'>" . ($row['Assignment_Date'] ? $row['Assignment_Date']->format('Y-m-d') : '') . "</td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='6' class='text-center py-4'>No assets assigned yet.</td></tr>";
    }
    exit;
}
?>
