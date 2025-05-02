<?php
include '../config/db.php';
$conn = connectDB('Inventory_System');

// Initialize categories array
$categories = [];
$category_id = null;
$category_name = '';
$category_type = '';
$srno_required = '';
$detail_required = '';

$message = '';

// Check for success message
$showToast = false;
if (isset($_GET['success'])) {
    $showToast = true;
    $toastMessage =
        $_GET['success'] === 'edited'
            ? 'Category edited successfully!'
            : ($_GET['success'] === 'added'
                ? 'Category added successfully!'
                : 'Category deleted successfully!');
}

// Check if editing a category
if (isset($_GET['id'])) {
    $category_id = $_GET['id'];
    // Fetch the category data for editing
    $edit_query = 'SELECT * FROM DeviceCategory WHERE Id = ?';
    $params = array($category_id);
    $edit_run_query = sqlsrv_query($conn, $edit_query, $params);
    if ($edit_run_query) {
        $category_data = sqlsrv_fetch_array($edit_run_query);
        $category_name = $category_data['Device_Category'];
        $category_type = $category_data['Type'];  // Updated to use 'type' column
        $srno_required = $category_data['SrNo_Required'];  // Updated to use 'srno_required' column
        $detail_required = $category_data['Detail_Required'];  // Updated to use 'detail_required' column

    } else {
        echo 'Error fetching category: ' . print_r(sqlsrv_errors(), true);
    }
}

// Fetch all categories for display
$cat_query = 'SELECT * FROM DeviceCategory WHERE Is_Deleted = 0 ORDER BY Type DESC';
$cat_run_query = sqlsrv_query($conn, $cat_query);
if ($cat_run_query) {
    while ($row = sqlsrv_fetch_array($cat_run_query)) {
        $categories[] = $row;
    }
} else {
    echo 'Error fetching categories: ' . print_r(sqlsrv_errors(), true);
}

?>
<style>
[x-cloak] {
    display: none !important;
}

.toast {
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: #4caf50;
    color: white;
    padding: 16px;
    border-radius: 5px;
    display: none;
    z-index: 1000;
}
</style>

<!-- <body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100"> -->

    <?php include '../includes/sidebar.php'; ?>

    <!-- <div class="ml-64 flex flex-col  h-screen"> -->
        <?php include '../includes/header.php'; ?>

        <!-- <div class="flex-1 overflow-y-auto px-4 py-8"> -->
  <div class="main-content p-4 transition-all duration-300 dark:bg-gray-900 dark:text-gray-100">


            <!-- Toast Notification -->
            <div class="toast" id="toast">
                <?php echo isset($toastMessage) ? htmlspecialchars($toastMessage) : ''; ?>
            </div>

            <!-- Breadcrumb -->
            <nav class="mb-6 text-sm text-gray-600 dark:text-gray-400" aria-label="Breadcrumb">
                <ol class="list-none p-0 inline-flex">
                    <li class="flex items-center">
                        <a href="dashboard.php" class="hover:text-primary-600">Home</a>
                        <span class="mx-2">/</span>
                    </li>
                    <li class="text-gray-500 dark:text-gray-300">
                        <?php echo $category_id ? 'Edit Category' : 'Add Category'; ?>
                    </li>
                </ol>
            </nav>

            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden ">
                <!-- Card Header -->
                <div class="text-white p-6 flex justify-between items-center" style="background-color: #007bff;">
                    <h1 class="text-2xl font-bold flex items-center">
                        <i class="fa-solid fa-folder-plus mr-3 mt-1 text-xl"></i>
                        <?php echo $category_id ? 'Edit Category' : 'Add Category'; ?>
                    </h1>
                </div>

                <?php if ($showToast): ?>
                <script>
                document.getElementById('toast').style.display = 'block';
                setTimeout(() => {
                    document.getElementById('toast').style.display = 'none';
                    // Clean up the URL by removing the success parameter
                    const url = new URL(window.location);
                    url.searchParams.delete('success');
                    window.history.replaceState({}, document.title, url);
                }, 1500);
                </script>
                <?php endif; ?>

                <form method="POST" action="../backend/db_category.php" id="categoryForm">
                    <input type="hidden" name="action"
                        value="<?php echo $category_id ? 'edit_category' : 'add_category'; ?>">
                    <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
                    <section class="mb-8 p-4">
                        <h2 class="text-xl font-semibold mb-4 border-b pb-2 dark:border-gray-700">
                            <i class="fas fa-tag mr-2 text-primary-500"></i>
                            Category Details
                        </h2>
                        <div class="grid md:grid-cols-12 gap-4">
                            <div class="col-span-3">
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-2">Category
                                    Name</label>
                                <input type="text" name="category_name"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200"
                                    placeholder="Enter category name..."
                                    value="<?php echo htmlspecialchars($category_name); ?>" required>
                            </div>
                            <div class="col-span-3">
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-2">Category
                                    Type</label>
                                <select name="category_type"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
                                    <option value="">Select Category Type</option>
                                    <option value="user" <?php echo $category_type === 'user' ? 'selected' : ''; ?>>User
                                    </option>
                                    <option value="general"
                                        <?php echo $category_type === 'general' ? 'selected' : ''; ?>>General</option>
                                </select>
                            </div>
                            <div class="col-span-3">
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-2">Sr.No
                                    Required?</label>
                                <select name="srno_required"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
                                    <option value="">Select Option</option>
                                    <option value="1" <?php echo $srno_required == '1' ? 'selected' : ''; ?>>Yes
                                    </option>
                                    <option value="0" <?php echo $srno_required == '0' ? 'selected' : ''; ?>>No
                                    </option>
                                </select>
                            </div>
                            <!-- detail_required -->
                            <div class="col-span-3">
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-2">Details
                                    Required?</label>
                                <select name="detail_required"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
                                    <option value="">Select Option</option>
                                    <option value="1" <?php echo $detail_required == '1' ? 'selected' : ''; ?>>Yes
                                    </option>
                                    <option value="0" <?php echo $detail_required == '0' ? 'selected' : ''; ?>>No
                                    </option>
                                </select>
                            </div>
                            <!-- Submit Button Row -->
                            <div class="col-span-12 flex justify-end mt-4">
                                <button type="submit" class="custom-btn text-white px-4 py-2 rounded-md">
                                    <?php echo $category_id ? 'Edit Category' : 'Add Category'; ?>
                                </button>
                            </div>
                        </div>
                    </section>
                </form>
            </div>

            <!-- Categories List -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden mt-8">
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-4 border-b pb-2 dark:border-gray-700">
                        <i class="fas fa-list mr-2 text-primary-500"></i>
                        Categories List
                    </h2>
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-100 dark:bg-gray-700 border-b dark:border-gray-600 ">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-bold  text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                    ID</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-bold  text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                    Category Name</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-bold  text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                    Category Type</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-bold  text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                    Sr.No Required?</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-bold  text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                    Details Required?</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-bold  text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                            <?php foreach ($categories as $category): ?>
                            <tr>
                                <?php
                                    // echo "<pre>"; print_r($category); die;
                                ?>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <?php echo $category['Id']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <?php echo $category['Device_Category']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <?php echo $category['Type'] === 'user' ? 'User' : 'General'; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <?php echo $category['SrNo_Required'] == '1' ? 'Yes' : 'No'; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <?php echo $category['Detail_Required'] == '1' ? 'Yes' : 'No'; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <div class="flex items-center space-x-2">
                                        <a href="category.php?id=<?php echo $category['Id']; ?>"
                                            class="fa fa-edit text-blue-600 hover:text-blue-900">
                                        </a>
                                        <form method="POST" action="../backend/db_category.php" style="display:inline;">
                                            <input type="hidden" name="category_id"
                                                value="<?php echo $category['Id']; ?>">
                                            <input type="hidden" name="action" value="delete_category">
                                            <button type="submit" class="text-red-600 hover:text-red-900 ml-4"><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php include '../includes/footer.php'; ?>

        <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            var categoryType = document.querySelector('select[name="category_type"]').value;
            if (!categoryType) {
                alert('Please select a category type before submitting.');
                event.preventDefault(); // Prevent form submission
            }
        });
        </script>