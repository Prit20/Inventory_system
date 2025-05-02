<?php
include '../config/db.php';
$conn = connectDB('Inventory_System');

// Get category from URL
$category = isset($_GET['category']) ? $_GET['category'] : '';

if (!$category) {
    echo "No category selected.";
    exit;
}
?>

<!-- <body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100"> -->
    <?php include '../includes/sidebar.php'; ?>

    <!-- <div class="ml-64 flex flex-col h-screen"> -->
        <?php include '../includes/header.php'; ?>

        <!-- <main class="flex-1 p-6 overflow-y-auto"> -->
  <div class="main-content p-4 transition-all duration-300 dark:bg-gray-900 dark:text-gray-100">

            <div class="p-6">
                <div class="flex justify-between items-center mb-6">

                    <h1 class="text-3xl font-bold mb-6">Stock Details for "<?php echo htmlspecialchars($category); ?>"
                    </h1>
                    <div class="mb-4">
                        <a href="stock.php"
                            class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md">
                            ← Back to Stock
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white dark:bg-gray-800 rounded-lg shadow-md">
                        <thead
                            class="bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-sm leading-normal">
                            <tr>
                                <th class="py-3 px-6 text-left">Sr No</th>
                                <th class="py-3 px-6 text-left">Company Name</th>
                                <th class="py-3 px-6 text-left">Serial Number</th>
                                <th class="py-3 px-6 text-left">Warranty Type</th>
                                <th class="py-3 px-6 text-left">Warranty</th>
                                <th class="py-3 px-6 text-left">Guarantee Type</th>
                                <th class="py-3 px-6 text-left">Guarantee</th>
                            </tr>
                        </thead>
                        <tbody class="text-black-600 dark:text-gray-300">

                            <?php
                         // Query to get unassigned stock for this category, joining CategoryAssign
                            $query = "
                            SELECT
                                s.Company_Name,
                                s.Serial_Number,
                                ca.Warranty_Type,
                                ca.Warranty_Start,
                                ca.Warranty_End,
                                ca.Guarantee_Type,
                                ca.Guarantee_Start,
                                ca.Guarantee_End
                            FROM SerialNumberAssignment s
                            LEFT JOIN AssignedAssets a ON s.Serial_Number = a.Serial_Number
                            LEFT JOIN CategoryAssign ca ON s.Sr_No = ca.Sr_No
                            WHERE s.Category_Name = '$category'
                            AND a.Serial_Number IS NULL
                            ";

                            $result = sqlsrv_query($conn, $query);

                            if ($result) {
                                $srno = 1;
                                while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                    $warrantyStart = $row['Warranty_Start'] ? $row['Warranty_Start']->format('d-m-Y') : '-';
                                    $warrantyEnd = $row['Warranty_End'] ? $row['Warranty_End']->format('d-m-Y') : '-';
                                    $guaranteeStart = $row['Guarantee_Start'] ? $row['Guarantee_Start']->format('d-m-Y') : '-';
                                    $guaranteeEnd = $row['Guarantee_End'] ? $row['Guarantee_End']->format('d-m-Y') : '-';
                                
                                    echo '<tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">';
                                    echo '<td class="py-3 px-6 text-left">' . $srno++ . '</td>';
                                    echo '<td class="py-3 px-6 text-left">' . htmlspecialchars($row['Company_Name']) . '</td>';
                                    echo '<td class="py-3 px-6 text-left">' . htmlspecialchars($row['Serial_Number']) . '</td>';
                                    echo '<td class="py-3 px-6 text-left">' . htmlspecialchars($row['Warranty_Type']) . '</td>';
                                    echo '<td class="py-3 px-6 text-left">' . $warrantyStart . ' To ' . $warrantyEnd . '</td>';
                                    echo '<td class="py-3 px-6 text-left">' . htmlspecialchars($row['Guarantee_Type']) . '</td>';
                                    echo '<td class="py-3 px-6 text-left">' . $guaranteeStart . ' To ' . $guaranteeEnd . '</td>';
                                    echo '</tr>';
                                }
                            }                                
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>

        <?php include '../includes/footer.php'; ?>
    </div>
</body>