<?php
include '../config/db.php';

$id = $_GET['id'] ?? 0;
$record = [];
$qty = 0;
$srNoRequired = 0;
$detailRequired = 0;
$companyNames = [];

$conn = connectDB('Inventory_System');
if ($id) {
    // Fetch record details
    $sql = "SELECT * FROM Inventory_System.dbo.CategoryAssign WHERE Id = ?";
    $stmt = sqlsrv_query($conn, $sql, [$id]);
    $record = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $qty = (int) ($record['Qty'] ?? 0);

    // Fetch category details
    $categoryId = $record['Category_Id'] ?? 0;
    $categorySql = "SELECT SrNo_Required, Detail_Required FROM DeviceCategory WHERE Id = ?";
    $categoryStmt = sqlsrv_query($conn, $categorySql, [$categoryId]);
    $category = sqlsrv_fetch_array($categoryStmt, SQLSRV_FETCH_ASSOC);
    $srNoRequired = $category['SrNo_Required'] ?? 0;
    $detailRequired = $category['Detail_Required'] ?? 0;

    // Fetch company names
    $companyQuery = "SELECT Company_Name FROM CompanyName";
    $companyResult = sqlsrv_query($conn, $companyQuery);
    while ($row = sqlsrv_fetch_array($companyResult, SQLSRV_FETCH_ASSOC)) {
        $companyNames[] = $row['Company_Name'];
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Serial Numbers</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<!-- <body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100 min-h-screen"> -->
<?php include '../includes/sidebar.php'; ?>

<!-- <div class="ml-64 flex flex-col min-h-screen max-h-screen"> -->
    <?php include '../includes/header.php'; ?>

    <!-- <main class="flex-1 p-6 overflow-y-auto"> -->
  <div class="main-content p-4 transition-all duration-300 dark:bg-gray-900 dark:text-gray-100">

        <h2 class="mb-6 text-3xl font-bold">Assign Serial Numbers</h2>

        <!-- Record Details Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 mb-6">
  <h3 class="text-xl font-semibold mb-4">Record Details</h3>
  <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
    <div>
      <label for="Sr_No" class="block mb-1 text-sm font-medium">Sr_No</label>
      <input type="text" id="Sr_No" name="Sr_No" value="<?= $record['Sr_No'] ?? '' ?>" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 rounded border border-gray-300 dark:border-gray-600" disabled>
    </div>
    <div>
      <label for="location" class="block mb-1 text-sm font-medium">Location</label>
      <input type="text" id="location" name="location" value="<?= $record['Location'] ?? '' ?>" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 rounded border border-gray-300 dark:border-gray-600" disabled>
    </div>
    <div>
      <label for="po_no" class="block mb-1 text-sm font-medium">PO No</label>
      <input type="text" id="po_no" name="po_no" value="<?= $record['PO_No'] ?? '' ?>" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 rounded border border-gray-300 dark:border-gray-600" disabled>
    </div>

    <div>
      <label for="invoice_no" class="block mb-1 text-sm font-medium">Invoice No</label>
      <input type="text" id="invoice_no" name="invoice_no" value="<?= $record['Invoice_No'] ?? '' ?>" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 rounded border border-gray-300 dark:border-gray-600" disabled>
    </div>
    <div>
      <label for="party_name" class="block mb-1 text-sm font-medium">Party Name</label>
      <input type="text" id="party_name" name="party_name" value="<?= $record['Party_Name'] ?? '' ?>" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 rounded border border-gray-300 dark:border-gray-600" disabled>
    </div>
    <div>
      <label for="item" class="block mb-1 text-sm font-medium">Item</label>
      <input type="text" id="item" name="item" value="<?= $record['Item'] ?? '' ?>" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 rounded border border-gray-300 dark:border-gray-600" disabled>
    </div>

    <div>
      <label for="qty" class="block mb-1 text-sm font-medium">Qty</label>
      <input type="number" id="qty" name="qty" value="<?= $qty ?>" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-900 rounded border border-gray-300 dark:border-gray-600" disabled>
    </div>
    <div>
      <label for="rate" class="block mb-1 text-sm font-medium">Basic Rate</label>
      <input type="text" id="rate" name="rate" value="<?= $record['Basic_Rate'] ?? '' ?>" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 rounded border border-gray-300 dark:border-gray-600" disabled>
    </div>
    <div>
      <label for="remarks" class="block mb-1 text-sm font-medium">Remarks</label>
      <input type="text" id="remarks" name="remarks" value="<?= $record['Remarks'] ?? '' ?>" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 rounded border border-gray-300 dark:border-gray-600" disabled>
    </div>
    <div>
      <label for="category" class="block mb-1 text-sm font-medium">Category</label>
      <input type="text" id="category" name="category" value="<?= $record['Category_Name'] ?? '' ?>" class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 rounded border border-gray-300 dark:border-gray-600" disabled>
    </div>
  </div>
</div>


        <form method="POST" action="../backend/db_serialnoassign_form.php">
        <input type="hidden" name="assign_id" value="<?= $id ?>">
        <input type="hidden" name="Sr_No" value="<?= $record['Sr_No'] ?? '' ?>">

            <input type="hidden" name="location" value="<?= $record['Location'] ?? '' ?>">
            <input type="hidden" name="po_no" value="<?= $record['PO_No'] ?? '' ?>">
            <input type="hidden" name="invoice_no" value="<?= $record['Invoice_No'] ?? '' ?>">
            <input type="hidden" name="party_name" value="<?= $record['Party_Name'] ?? '' ?>">
            <input type="hidden" name="item" value="<?= $record['Item'] ?? '' ?>">
            <input type="hidden" name="qty" id="hidden_qty" value="<?= $qty ?>">

            <input type="hidden" name="rate" value="<?= $record['Basic_Rate'] ?? '' ?>">
            <input type="hidden" name="remarks" value="<?= $record['Remarks'] ?? '' ?>">
            <input type="hidden" name="category" value="<?= $record['Category_Name'] ?? '' ?>">



            <!-- Warranty & Guarantee Details Section -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 mb-6">
    <h3 class="text-xl font-semibold mb-4">Warranty & Guarantee Details</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
        <div>
            <label class="block mb-1 text-sm font-medium">Warranty Type</label>
            <select name="warranty_type" class="form-input">
                <option value="">Select</option>
                <option value="Standard" <?= $record['Warranty_Type'] == 'Standard' ? 'selected' : '' ?>>Standard</option>
                <<option value="On-Site" <?= $record['Warranty_Type'] == 'On-Site' ? 'selected' : '' ?>>On Site</option>
                    <option value="Extended" <?= $record['Warranty_Type'] == 'Extended' ? 'selected' : '' ?>>Extended</option>
                    <option value="Other" <?= $record['Warranty_Type'] == 'Other' ? 'selected' : '' ?>>Other</option>
                </select>
        </div>
        <div>
            <label class="block mb-1 text-sm font-medium">Warranty Start Date</label>
            <input type="date" name="warranty_start" class="form-input">
        </div>
        <div>
            <label class="block mb-1 text-sm font-medium">Warranty End Date</label>
            <input type="date" name="warranty_end" class="form-input">
        </div>
        <div>
            <label class="block mb-1 text-sm font-medium">Guarantee Type</label>
            <select name="guarantee_type" class="form-input">
                <option value="">Select</option>
                <option value="Replacement"  <?= $record['Guarantee_Type'] == 'Replacement' ? 'selected' : '' ?>>Replacement</option>
                <option value="Repair"  <?= $record['Guarantee_Type'] == 'Repair' ? 'selected' : '' ?>>Repair</option>
                <option value="Other" <?= $record['Guarantee_Type'] == 'Other' ? 'selected' : '' ?>>Other</option>
            </select>
        </div>
        <div>
            <label class="block mb-1 text-sm font-medium">Guarantee Start Date</label>
            <input type="date" name="guarantee_start" class="form-input" >
        </div>
        <div>
            <label class="block mb-1 text-sm font-medium">Guarantee End Date</label>
            <input type="date" name="guarantee_end" class="form-input">
        </div>
        <?php if ($srNoRequired == 0): ?>
            <div class="mt-2 md:mt-0">
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Company Name</label>
                <select name="company_name" class="form-input">
                    <option value="">Select Company</option>
                    <?php foreach ($companyNames as $companyName): ?>
                    <option value="<?= htmlspecialchars($companyName) ?>"
                        <?= ($record['Company_Name'] ?? '') == $companyName ? 'selected' : '' ?>>
                        <?= htmlspecialchars($companyName) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if ($srNoRequired || $detailRequired): ?>
    <div id="card-container" class="space-y-6">
        <!-- Dynamic cards will be injected here -->
    </div>
<?php endif; ?>

            <div class="mt-6">
            <button type="button" onclick="window.location.href='serialnoassign.php'" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg focus:outline-none">
        Cancel
    </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">Save</button>
            </div>
        </form>
    

    <?php include '../includes/footer.php'; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const qtyInput = document.getElementById('qty');
        const cardContainer = document.getElementById('card-container');

        // Inject PHP values for conditional rendering
        const srNoRequired = <?= $srNoRequired ? 'true' : 'false' ?>;
        const detailRequired = <?= $detailRequired ? 'true' : 'false' ?>;

        const existingRows = <?= json_encode($serialDetails ?? []) ?>;

        function generateCards(count) {
            if (!cardContainer) return;
            cardContainer.innerHTML = '';

            for (let i = 0; i < count; i++) {
                const data = existingRows[i] || {}; // Use saved row if exists

                const card = document.createElement('div');
                card.className = 'bg-white dark:bg-gray-800 rounded-xl shadow-md p-6';

                let serialSection = '';
                let detailsSection = '';

                if (srNoRequired) {
                    serialSection = `
                        <h4 class="text-lg font-semibold mb-2">Serial Info (Item ${i + 1})</h4>
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
                            <input type="text" name="serial_number[]" class="form-input" placeholder="Serial Number" required>
                            <input type="text" name="model_number[]" class="form-input" placeholder="Model Number">
                            <select name="company_name[]" class="form-input">
                                <?php foreach ($companyNames as $company): ?>
                                     <option value="<?= htmlspecialchars($company) ?>"><?= htmlspecialchars($company) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="date" name="manufacture_date[]" class="form-input" >
                            <input type="text" name="remark[]" class="form-input" placeholder="Remark" >
                        </div>
                    `;
                }

                if (detailRequired) {
                    detailsSection = `
                        <h4 class="text-lg font-semibold mb-2">Detail Info</h4>
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <input type="text" name="mac_address[]" class="form-input" placeholder="MAC Address" >
                            <input type="text" name="ip_address[]" class="form-input" placeholder="IP Address" >
                    <input type="text" name="ram[]" class="form-input" placeholder="RAM" >
                    <input type="text" name="hard_disk[]" class="form-input" placeholder="Hard Disk">
                    <input type="text" name="graphics[]" class="form-input" placeholder="Graphics">
                        </div>
                    `;
                }

                card.innerHTML = serialSection + detailsSection;
                cardContainer.appendChild(card);
            }
        }

        function updateCards() {
            const qty = parseInt(qtyInput.value) || 0;
            generateCards(qty);
        }

        qtyInput.addEventListener('input', updateCards);
        updateCards(); // Generate initially
    });
</script>

<style>
    .form-input {
        width: 100%;
        padding: 0.5rem 0.75rem;
        border-radius: 0.375rem;
        border: 1px solid #d1d5db;
        background-color: #f9fafb;
    }
    .dark .form-input {
        background-color: #374151;
        color: white;
        border-color: #4b5563;
    }
</style>
</body>
</html>
