<?php
include '../config/db.php';
$conn = connectDB('Inventory_System');
// Initialize variables
$company_id = isset($_GET['id']) ? trim($_GET['id']) : null;
$company_name = '';
$message = '';

// Check for success message
$showToast = false;
if (isset($_GET['success'])) {
    $showToast = true;
    $toastMessage =
        $_GET['success'] === 'edited'
            ? 'Company edited successfully!'
            : ($_GET['success'] === 'added'
                ? 'Company added successfully!'
                : 'Company deleted successfully!');
}

// Check if editing a company
if ($company_id) {
    // Fetch the company data for editing
    $query = 'SELECT * FROM CompanyName WHERE id = ? AND Is_Deleted = 0';
    $params = array($company_id);
    $stmt = sqlsrv_query($conn, $query, $params);
    if ($stmt === false) {
        echo 'Error fetching company: ' . print_r(sqlsrv_errors(), true);
    } else {
        $company_data = sqlsrv_fetch_array($stmt);
        $company_name = $company_data['Company_Name'];
    }
}

// Fetch all companies for display
$cat_query = 'SELECT * FROM CompanyName WHERE Is_Deleted = 0 ORDER BY CreatedAt ASC';
$cat_run_query = sqlsrv_query($conn, $cat_query);
if ($cat_run_query) {
    $companies = [];
    while ($row = sqlsrv_fetch_array($cat_run_query)) {
        $companies[] = $row;
    }
} else {
    echo 'Error fetching companies: ' . print_r(sqlsrv_errors(), true);
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

    <!-- <div class="flex flex-col h-screen"> -->
        <?php include '../includes/header.php'; ?>
        <!-- <div class="lex-1 overflow-y-auto px-4 py-8">
             -->
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
                        <?php echo $company_id ? 'Edit Company' : 'Add Company'; ?>
                    </li>
                </ol>
            </nav>

            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden ">
                <!-- Card Header -->
                <div class="text-white p-6 flex justify-between items-center" style="background-color: #007bff;">

                    <h1 class="text-2xl font-bold flex items-center">
                        <i class="fa-solid fa-folder-plus mr-3 mt-1 text-xl"></i>
                        <?php echo $company_id ? 'Edit Company' : 'Add Company'; ?>
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

                <form method="POST" action="../backend/db_company.php" id="companyForm">
                    <input type="hidden" name="action"
                        value="<?php echo $company_id ? 'edit_company' : 'add_company'; ?>">
                    <input type="hidden" name="company_id" value="<?php echo $company_id; ?>">
                    <section class="mb-8 p-4">
                        <h2 class="text-xl font-semibold mb-4 border-b pb-2 dark:border-gray-700">
                            <i class="fas fa-tag mr-2 text-primary-500"></i>
                            Company Details
                        </h2>
                        <div class="grid md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-2">Company
                                    Name</label>
                                <input type="text" name="company_name"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200"
                                    placeholder="Enter company name..."
                                    value="<?php echo htmlspecialchars($company_name); ?>" required>
                            </div>
                            <div class="flex justify-between space-x-4 mt-7 mb-4">
                                <button type="submit" class="custom-btn text-white px-4 py-2 rounded-md">
                                    <?php echo $company_id ? 'Edit Company' : 'Add Company'; ?>
                                </button>
                            </div>
                        </div>
                    </section>


                </form>
            </div>

            <!-- Companies List -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden mt-8">
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-4 border-b pb-2 dark:border-gray-700">
                        <i class="fas fa-list mr-2 text-primary-500"></i>
                        Companies List
                    </h2>
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-100 dark:bg-gray-700 border-b dark:border-gray-600 ">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-bold  text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                    ID</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-bold  text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                    Company Name</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-bold  text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                            <?php foreach ($companies as $company): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <?php echo $company['Id']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <?php echo htmlspecialchars($company['Company_Name']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <div class="flex items-center space-x-2">
                                        <a href="company.php?id=<?php echo $company['Id']; ?>"
                                            class="fa fa-edit text-blue-600 hover:text-blue-900">
                                        </a>
                                        <form method="POST" action="../backend/db_company.php"
                                            style="display:inline;">
                                            <input type="hidden" name="company_id"
                                                value="<?php echo $company['Id']; ?>">
                                            <input type="hidden" name="action" value="delete_company">
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
            var companyName = document.querySelector('input[name="company_name"]').value;
            if (!companyName) {
                alert('Please enter a company name before submitting.');
                event.preventDefault(); // Prevent form submission
            }
        });
        </script>