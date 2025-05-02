<?php
include '../config/db.php';
$conn = connectDB('Inventory_System');
?>
<!-- <body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100"> -->
    <?php include '../includes/sidebar.php'; ?>

    <!-- <div class="ml-64 flex flex-col h-screen"> -->
        <?php include '../includes/header.php'; ?>

        <!-- <main class="flex-1 p-6 overflow-y-auto"> -->
        <div class="main-content p-4 transition-all duration-300 dark:bg-gray-900 dark:text-gray-100">

            <div class="p-6">
                <div class="">
                    <h1 class="text-3xl font-bold mb-6">Stock Management</h1>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800 rounded-lg shadow-md">
                            <thead class="bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-sm leading-normal">
                                <tr>
                                    <th class="py-3 px-6 text-left">Sr No</th>
                                    <th class="py-3 px-6 text-left">Device Category</th>
                                    <th class="py-3 px-6 text-center">Purchased Qty</th>
                                    <th class="py-3 px-6 text-center">Assigned Qty</th>
                                    <th class="py-3 px-6 text-center">In Stock</th>
                                    <th class="py-3 px-6 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-black-600 dark:text-gray-300">
                                <?php
                                // Fetch Purchased Qty by counting Category_Name in SerialNumberAssignment
                                $query = "SELECT Category_Name, COUNT(*) AS TotalQty FROM SerialNumberAssignment GROUP BY Category_Name";
                                $result = sqlsrv_query($conn, $query);

                                if ($result) {
                                    $srno = 1;
                                    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                        $category = $row['Category_Name'];
                                        $purchasedQty = (int)$row['TotalQty'];

                                        // Fetch Assigned Qty by counting Category_Name in AssignedAssets
                                        $assignedQuery = "SELECT COUNT(*) AS AssignedQty FROM AssignedAssets WHERE Device_Category = '$category'";
                                        $assignedResult = sqlsrv_query($conn, $assignedQuery);
                                        $assignedQtyRow = sqlsrv_fetch_array($assignedResult, SQLSRV_FETCH_ASSOC);

                                        $assignedQty = (int)($assignedQtyRow['AssignedQty'] ?? 0);

                                        // Calculate In Stock
                                        $inStock = $purchasedQty - $assignedQty;

                                        echo '<tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">';
                                        echo '<td class="py-3 px-6 text-left whitespace-nowrap">' . $srno++ . '</td>';
                                        echo '<td class="py-3 px-6 text-left">' . htmlspecialchars($category) . '</td>';
                                        echo '<td class="py-3 px-6 text-center">' . $purchasedQty . '</td>';
                                        echo '<td class="py-3 px-6 text-center">' . $assignedQty . '</td>';
                                        echo '<td class="py-3 px-6 text-center font-semibold">' . $inStock . '</td>';
                                        // echo '<td class="py-3 px-6 text-center">
                                        //         <button class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 rounded-full text-xs">
                                        //             View Details
                                        //         </button>
                                        //       </td>';
                                        echo '<td class="py-3 px-6 text-center">
                                                    <a href="stockdetails.php?category=' . urlencode($category) . '" class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 rounded-full text-xs">
                                                        View Details
                                                    </a>
                                              </td>';

                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="6" class="text-center py-4">No stock data available.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <!-- </main> -->

        <?php include '../includes/footer.php'; ?>
    </div>
</body>
